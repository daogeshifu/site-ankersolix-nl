<?php

namespace App\Common;

use GuzzleHttp\Client;
use Storage;
use Exception;

class Functions
{
	/**
	 * [保存网络文件]
	 * @param        $url
	 * @param string $extension 保存的文件名
	 * @param string $disk
	 * @return bool|path
	 */
	public static function StoreImage($url, $extension = '.jpg', $disk = 'game')
	{
		try {
			$client   = new Client([ 'verify' => false ]);
			$img      = $client->request('get', $url);
			$path     = date('Y-m-d');
			$filename = date('YmdHis') . mt_rand(100, 999) . $extension;
			if ($img->getStatusCode() == '200') {
				$data = $img->getBody()->getContents();
				Storage::disk($disk)->exists($path) or Storage::disk($disk)->makeDirectory($path);
				Storage::disk($disk)->put($path . '/' . $filename, $data);
				return Storage::disk('game')->url($path . '/' . $filename);
			} else {
				throw new Exception('文件获取失败~~');
			}
		} catch (Exception $e) {
			return false;
		}

	}

    /**
     * 从内容里获取图片当封面，todo根据ai生成最好
     * @param $str
     * @param $isopen
     * @return mixed|string
     */
	public static function getImg($str, $isopen = false)
	{

//        preg_match_all('/((http|ftp|https):\/\/)?[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?/', $str, $matches);
		preg_match_all("/<[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg|.jpeg|.png|.JPG]))['|\"].*?[\/]?>/", $str, $matches);
		if (isset($matches[1])) {
			$img = $matches[1];
			if ($isopen) {
				$cover = '';
				foreach ($img as $url) {
					if (@fopen($url, 'r') && !strstr($url, 'html')) {
						return $cover = $url;
					}
				}
				return $cover;
			} else {
                if (isset($img[1]) && filter_var($img[1], FILTER_VALIDATE_URL) != false) {
                    return $img[1];
                } else if (isset($img[0]) && filter_var($img[0], FILTER_VALIDATE_URL) != false) {
                    return $img[0];
                } else {
					return '';
				}
			}
		} else {
			return '';
		}
	}

	/**
	 * 输出安全的html
	 * @param      $text
	 * @param null $tags
	 * @param bool $istext
	 * @return mixed|null|string|string[]
	 */
	public static function h($text, $tags = null, $istext = false)
	{
		//去除style样式  "/src=.+?['|\"]/i",
		$text = preg_replace([ "/data-url=.+?['|\"]/i", "/border=.+?['|\"]/i", "/class=.+?['|\"]/i", "/style=.+?['|\"]/i", "/name=.+?['|\"]/i", "/id=.+?['|\"]/i", "/width=.+?['|\"]/i", "/height=.+?['|\"]/i", "/usemap=.+?['|\"]/i", "/shape=.+?['|\"]/i", "/coords=.+?['|\"]/i", "/target=.+?['|\"]/i", "/title=.+?['|\"]/i" ], "", $text);
		//完全过滤注释
		#$text = preg_replace("/<!--[^\!\[]*?(?<!\/\/)-->/", "", $text);
		$text = preg_replace('/<!--?.*-->/', '', $text);
		//完全过滤动态代码
		$text = preg_replace('/<\?|\?' . '>/', '', $text);
		//完全过滤js
		$text = preg_replace('/<script?.*\/script>/', '', $text);
		$text = str_replace('[', '&#091;', $text);
		$text = str_replace(']', '&#093;', $text);
		$text = str_replace('|', '&#124;', $text);
		//过滤换行符
		$text = preg_replace('/\r?\n/', '', $text);
		//br
//	$text = preg_replace('/<br(\s\/)?' . '>/i', '[br]', $text);
//	$text = preg_replace('/<p(\s\/)?' . '>/i', '[br]', $text);
//	$text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);

		//过滤危险的属性，如：过滤on事件lang js
//		while (preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
//			$text = str_replace($mat[0], $mat[1], $text);
//		}
		while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
			$text = str_replace($mat[0], $mat[1] . $mat[3], $text);
		}

		if (empty($tags)) {
			$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt';
		}

		#$text = preg_replace("/<(\/?a.*?)>/si", "", $text);
		//允许的HTML标签
		$text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)>/i', '[\1\2]', $text);
		$text = preg_replace('/<\/(' . $tags . ')>/Ui', '[/\1]', $text);
		//过滤多余html
		$text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|a)[^><]*>/i', '', $text);
		//过滤合法的html标签
		while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
			$text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
		}
		//转换引号
		while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
			$text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
		}
		//过滤错误的单个引号
		while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
			$text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
		}
		//转换其它所有不合法的 < >
//        $text = str_replace('<', '&lt;', $text);
//        $text = str_replace('>', '&gt;', $text);
//        $text = str_replace('"', '&quot;', $text);
		//反转换
		$text = str_replace('[', '<', $text);
		$text = str_replace(']', '>', $text);
		$text = str_replace('|', '"', $text);
		//过滤多余空格
		$text = str_replace('  ', '', $text);
		$text = str_replace('　　', '', $text);
		$text = str_replace(' ', '', $text);
//		$text = str_replace(' ', '', $text);
		if ($istext) {
			$text = strip_tags($text);
		}
		return $text;
	}


	public static function CloseTags($html)
	{
		// strip fraction of open or close tag from end (e.g. if we take first x characters, we might cut off a tag at the end!)
		$html = preg_replace('/<[^>]*$/', '', $html); // ending with fraction of open tag
		// put open tags into an array
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$opentags = $result[1];
		// put all closed tags into an array
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closetags  = $result[1];
		$len_opened = count($opentags);
		// if all tags are closed, we can return
		if (count($closetags) == $len_opened) {
			return $html;
		}
		// close tags in reverse order that they were opened
		$opentags = array_reverse($opentags);
		// self closing tags
		$sc = array( 'br', 'input', 'img', 'hr', 'meta', 'link' );
		// ,'frame','iframe','param','area','base','basefont','col'
		// should not skip tags that can have content inside!
		for ($i = 0; $i < $len_opened; $i++) {
			$ot = strtolower($opentags[$i]);
			if (!in_array($opentags[$i], $closetags) && !in_array($ot, $sc)) {
				$html .= '</' . $opentags[$i] . '>';
			} else {
				unset($closetags[array_search($opentags[$i], $closetags)]);
			}
		}
		return $html;
	}


	/**
	 * 过滤A标签、空格
	 * @param $text
	 * @return mixed|null|string|string[]
	 */
	public static function FilterA($text)
	{
		//过滤隐藏的div
		$text = preg_replace("/<div[^>]+?style=\"display:none\"+?>.*?<\/div>/si", "", $text);
		//过滤隐藏的p
		$text = preg_replace("/<p[^>]+?style=\"display:none\"+?>.*?<\/p>/si", "", $text);
		$text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|a)[^><]*>/i', '', $text);
		//过滤注释
		$text = preg_replace('/<!--?.*-->/', '', $text);
		//过滤换行符
		$text = preg_replace('/\r?\n/', '', $text);
		//过滤多余空格
		$text = str_replace('  ', '', $text);
		$text = str_replace('　　', '', $text);
		$text = str_replace(' ', '', $text);
		$text = str_replace('	', '', $text);
		//过滤A
		$text = preg_replace("/<(\/?a.*?)>/si", "", $text);
		$text = preg_replace("/<(\/?div.*?)>/si", "", $text);
		$text = preg_replace([ "/href=.+?['|\"]/i", "/align=.+?['|\"]/i", "/border=.+?['|\"]/i", "/class=.+?['|\"]/i", "/style=.+?['|\"]/i", "/name=.+?['|\"]/i", "/id=.+?['|\"]/i", "/img_width=.+?['|\"]/i", "/img_height=.+?['|\"]/i", "/usemap=.+?['|\"]/i", "/shape=.+?['|\"]/i", "/coords=.+?['|\"]/i", "/target=.+?['|\"]/i" ], "", $text);
		$text = preg_replace("/<img.*?src=[\"|\'][\"|\'].*?>/", '', $text);
		return $text;
	}


	//仙峰内容定制
	public static function AddImgAlt($text, $title)
	{
		//过滤注释
		$text = preg_replace('/<!--?.*-->/', '', $text);
		//过滤换行符
		$text = preg_replace('/\r?\n/', '', $text);
		//过滤多余空格
		$text = str_replace('  ', '', $text);
		$text = str_replace('　　', '', $text);
		$text = str_replace(' ', '', $text);
		$text = str_replace('	', '', $text);
		//过滤A
		$text    = preg_replace("/<(\/?a.*?)>/si", "", $text);
		$text    = preg_replace("/<p[^>]+?style=\"display:none\"+?>.*?<\/p>/si", "", $text);
		$text    = preg_replace([ "/href=.+?['|\"]/i", "/align=.+?['|\"]/i", "/border=.+?['|\"]/i", "/class=.+?['|\"]/i", "/style=.+?['|\"]/i", "/name=.+?['|\"]/i", "/id=.+?['|\"]/i", "/img_width=.+?['|\"]/i", "/img_height=.+?['|\"]/i", "/usemap=.+?['|\"]/i", "/shape=.+?['|\"]/i", "/coords=.+?['|\"]/i", "/target=.+?['|\"]/i" ], "", $text);
		$preg    = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
		$replace = '<img src="$1" alt="' . $title . ' "title="' . $title . '" >';
		$text    = preg_replace($preg, $replace, $text);
		$text    = str_replace('6399', '', $text);
		$text    = str_replace('头条', '', $text);
		$text    = str_replace('返回搜狐，查看更多', '', $text);
		$text    = str_replace('搜狐', '', $text);
		$text    = str_replace('责任编辑', '', $text);
		$text    = str_replace('点击查看', '', $text);
		return $text;
	}

	/**
	 * 系统加密方法
	 * @param string $data 要加密的字符串
	 * @param string $key 加密密钥
	 * @param int    $expire 过期时间 单位 秒
	 * @return string
	 */
	public static function encrypt_str($data, $key = '', $expire = 0)
	{
		$key  = md5(empty($key));
		$data = base64_encode($data);
		$x    = 0;
		$len  = strlen($data);
		$l    = strlen($key);
		$char = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}

		$str = sprintf('%010d', $expire ? $expire + time() : 0);

		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
		}
		return str_replace(array( '+', '/', '=' ), array( '-', '_', '' ), base64_encode($str));
	}

	/**
	 * 系统解密方法
	 * @param string $data 要解密的字符串 （必须是encrypt_str方法加密的字符串）
	 * @param string $key 加密密钥
	 * @return string
	 */
	public static function decrypt_str($data, $key = '')
	{
		$key  = md5(empty($key));
		$data = str_replace(array( '-', '_' ), array( '+', '/' ), $data);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		$data   = base64_decode($data);
		$expire = substr($data, 0, 10);
		$data   = substr($data, 10);

		if ($expire > 0 && $expire < time()) {
			return '';
		}
		$x    = 0;
		$len  = strlen($data);
		$l    = strlen($key);
		$char = $str = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}

		for ($i = 0; $i < $len; $i++) {
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			} else {
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return base64_decode($str);
	}
}


