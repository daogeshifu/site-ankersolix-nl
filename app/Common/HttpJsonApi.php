<?php

namespace App\Common;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class HttpJsonApi
{

	public function post($url, $data)
	{
		try {
			$client = new Client();
			$res    = $client->request('POST', $url, [
				'body' => $data
			]);


			$result = json_decode($res->getBody(), true);
			return $result;
		} catch (Exception $exception) {
			return false;
		}
	}

	public function get($url, $data)
	{
		try {
			$client = new Client();
			$res    = $client->request('GET', $url, [
				'query' => $data
			]);
			$result = json_decode($res->getBody(), true);
			return $result;
		} catch (Exception $exception) {
			return false;
		}
	}

	public function formPost($url, $data, $type = true)
	{
		try {
			$client = new Client();
			$res    = $client->request('POST', $url, [
				'form_params' => $data,
			]);
			$result = json_decode($res->getBody(), true);
			if (!$type) $result = (string)$res->getBody();
			return $result;
		} catch (Exception $exception) {
			return false;
		}
	}

	/**
	 * @param $url
	 * @param $data
	 * @param array $headers
	 * @param bool $type
	 * @return bool|mixed|string
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function jsonPost($url, $data, $headers = [], $type = true)
	{
		try {
			$client = new Client();
			$options = [
				'json' => $data,
				'headers' => $headers,
			];
	
			$res = $client->post($url, $options);
	
			// 检查响应状态码
			if ($res->getStatusCode() != 200) {
				Log::error('Request failed with status code: ' . $res->getStatusCode());
				return false;
			}
	
			// 检查响应内容类型
			$contentType = $res->getHeaderLine('Content-Type');
			if (strpos($contentType, 'application/json') === false) {
				Log::error('Invalid content type: ' . $contentType);
				return false;
			}
	
			$result = json_decode($res->getBody(), true);
	
			if (!$type) {
				$result = (string)$res->getBody();
			}
	
			return $result;
		} catch (RequestException $e) {
			// 捕获Guzzle请求异常
			Log::error('RequestException: ' . $e->getMessage());
			if ($e->hasResponse()) {
				Log::error('Response: ' . $e->getResponse()->getBody()->getContents());
			}
			return false;
		} catch (Exception $e) {
			// 捕获其他异常
			Log::error('Exception: ' . $e->getMessage());
			return false;
		}
	}
    /**
     * http://203.12.201.86:5000/api/chats/{engine}/{model}
     * @param $text
     * @return mixed|string
     */
    public function PostByAi($text){
        $engine =  'azure';
        $model = 'gpt-4o';
        $res = $this->jsonPost("http://203.12.201.86:5000/api/chats/$engine/$model",["text"=>$text,"token"=>"7284bf94-cf09-4891-989e-dafbd5838924","version"=>0]);
        return $res['re'];
    }

    /**
     * google  翻译
     * @param $locale
     * @param $content
     * @return mixed|string
     */
    public static  function translate($locale, $content)
    {
        if ($locale == 'zh-Hant') {
            $locale = 'zh-TW';
        }
        if ($locale == 'zh') {
            $locale = 'zh-CN';
        }

        $http = new HttpJsonApi();
        $res = $http->jsonPost("http://203.12.200.40/translate/from/auto/to/{$locale}", ['content' => $content ?? '']);
        return $res['result'] ?? '';
    }

	public static function transLateTitle($locale, $content)
	{
		$translation = self::translate($locale, $content);
		$filteredTranslation = preg_replace('/[^a-zA-Z0-9-]/', '', str_replace(' ', '-', $translation));
		return strtolower(substr($filteredTranslation, 0, 60));
	}


}

