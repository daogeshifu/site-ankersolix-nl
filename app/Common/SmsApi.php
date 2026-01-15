<?php
namespace App\Common;


use Exception;
use Request;
use GuzzleHttp\Client;

class SmsApi {

    protected $adapter;
    protected $client;
    protected $reader;
    
    protected $apikey = "e1bf87ec5a844a1041f65774113d7b0e";
    protected $uri = "http://yunpian.com/";
    protected $company = "道格办公";

    const TPLID = '1579784';
    const TPLID_COFFEE = '1628976';

    const TEL = ':0512-62002332';

    
    /**
     * url 为服务的url地址
     * query 为请求串
     */
    private function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }
    
    /**
     * 模板接口发短信
     * apikey 为云片分配的apikey
     * tpl_id 为模板id
     * tpl_value 为模板值
     * mobile 为接受短信的手机号
     */
    private function tpl_send_sms($tpl_id, $tpl_value, $mobile){
        $url="http://yunpian.com/v1/sms/tpl_send.json";
        $encoded_tpl_value = urlencode("$tpl_value");
        $post_string="apikey=$this->apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }

    public function sendByTpl($mobile, $tplId, array $vals) {
        $i = 0;
        $tplValue = '';
        foreach($vals as $k => $v) {
            if ($i != 0) {
                $tplValue .= '&';
            }
            $tplValue .= "#$k#=$v";
            $i++;
        }
        return  $this->tpl_send_sms($tplId, $tplValue, $mobile);
    }
    
    /**
     * 普通接口发短信
     * apikey 为云片分配的apikey
     * text 为短信内容
     * mobile 为接受短信的手机号
     */
    private function send_sms($text, $mobile){
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$text");
        $post_string="apikey=$this->apikey&text=$encoded_text&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }
    
    public function sendVerify($cellphone, $code) {
        $tplValue = "#code#=".$code."&#company#=".$this->company;
        $data = $this->tpl_send_sms(1, $tplValue, $cellphone);
        $data = json_decode($data);
        if ($data->code > 0) {
        	throw new Exception($data->detail, 1037);
        }
        return true;
    }

    /**
     *  使用自定义内容发送
     */
    public function sendTextVerify($text, $mobile){
        $content = "【".$this->company."】".$text;
        $data = $this->send_sms($content, $mobile);
        $data = json_decode($data);
        if ($data->code > 0) {
            throw new Exception("cellphone send faild", 1037);
        }
        return true;
    }


}

?>