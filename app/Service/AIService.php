<?php

namespace App\Service;

use App\Common\HttpJsonApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;


/**
 * 【ai嗲用接口】
 * Class AliPayService
 * @package App\Services
 */
class AIService
{

    public static string $model = self::MODEL_GPT_4_1;
    public static string $engine = self::ENGINE_AZURE_SUZHOU;

    // ==== 模型常量 ====
    const MODEL_GPT_4O_MINI        = 'gpt-4o-mini';
    const MODEL_GPT_4O             = 'gpt-4o';
    const MODEL_GPT_4_1            = 'gpt-4-1';
    const MODEL_GPT_5              = 'gpt-5';
    const MODEL_GPT_52             = 'gpt-5-2';

    const MODEL_QWEN_32B           = 'qwq-32b';

    const MODEL_QWEN3_5_PLUS = 'qwen3.5-plus';


    // ==== 引擎常量 ====
    const ENGINE_AZURE             = 'azure';
    const ENGINE_AZURE_SUZHOU      = 'azure-suzhou';
    const ENGINE_AZURE_SUZHOU_GPT5 = 'azure-suzhou-gpt5';

    const ENGINE_QWEN              = 'qwen';

    /**
     * 引擎与模型的映射
     * - 每个引擎可以对应多个模型
     */
    protected static array $engineModelMap = [

        self::ENGINE_AZURE             => [self::MODEL_GPT_4O_MINI],
        self::ENGINE_AZURE_SUZHOU      => [self::MODEL_GPT_4O, self::MODEL_GPT_4_1],
        self::ENGINE_AZURE_SUZHOU_GPT5 => [self::MODEL_GPT_5, self::MODEL_GPT_52],

        self::ENGINE_QWEN              => [self::MODEL_QWEN_32B],
    ];

    /**
     * 阿里云百炼 DashScope API（OpenAI 兼容模式）
     * base_url: https://dashscope.aliyuncs.com/compatible-mode/v1
     */
    public function chatWithQwen(string $content, string $model = self::MODEL_QWEN3_5_PLUS, bool $enableThinking = false): string
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . env('QW_KEY', ''),
            ];

            $bodyArray = [
                'model'    => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $content],
                ],
                'stream' => false,
            ];

            $bodyArray['enable_thinking'] = false;
            $request = new Request(
                'POST',
                'https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions',
                $headers,
                json_encode($bodyArray)
            );

            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            if (isset($res['choices'][0]['message']['content'])) {
                return $res['choices'][0]['message']['content'];
            }

            Log::error('chatWithQwen 返回异常: ' . json_encode($res));
            return '';
        } catch (\Exception $e) {
            Log::error('chatWithQwen error: ' . $e->getMessage());
            return '';
        }
    }

    
    /**
     *  微软的 ai，并且 设置 jsonpost 的 请求时间永不超时
     */
    public function chatWithAzure($content,$model = false,$engine = false,$isOnline = false){
        set_time_limit(-1);
        if($model == false){
            $model = self::$model;
        }
        if($engine == false){
            $engine = self::getEngineByModel($model);
        }

        // 构建请求参数
        $postData = [
            "text" => $content,
            "token" => env('WR_KEY', ''),
            "version" => 0
        ];

        // 如果开启联网模式，添加 plugins 参数
        if($isOnline){
            $postData['params'] = [
                'extra_body' => [
                    'plugins' => [
                        ['id' => 'web','max_results'=>1]
                    ]
                ]
            ];
        }

        $httpClient = new HttpJsonApi();
        $res = $httpClient->jsonPost("http://111.231.14.202:5555/api/chats/$engine/$model", $postData);

        // $res === false 表示请求失败（网络错误或非 200 状态码）
        if ($res === false) {
            Log::error('AIService::chat error', ['model' => $model, 'response' => $res]);
            $message = isset($res['message']) ? $res['message'] : '请求失败';
            return ['errno' => 1, 're' =>'', 'message' => $message];
        }

        if (isset($res['errno']) && $res['errno'] == 0) {
            return $res['re'];
        } else {
            Log::error('AIService::chat error', ['model' => $model, 'response' => $res]);
            $message = isset($res['message']) ? $res['message'] : '请求失败';
            return ['errno' => 1, 're' =>'', 'message' => $message];
        }
    }


    /**
     * 根据 engine 获取默认 model（取第一个）
     */
    private static function getModelByEngine(?string $engine = null): ?string
    {
        $engine = $engine ?? self::$engine;

        return self::$engineModelMap[$engine][0] ?? null;
    }

    /**
     * 根据 model 获取对应的 engine
     * - 如果一个 model 对应多个 engine，返回第一个匹配的
     */
    private static function getEngineByModel(?string $model = null): ?string
    {
        $model = $model ?? self::$model;

        foreach (self::$engineModelMap as $engine => $models) {
            if (in_array($model, $models, true)) {
                return $engine;
            }
        }
        return null;
    }



    /**
     * 调用微软 api 的 openai
     */
    public function chatMulti( $arr_content,$model = null,$engine = null){

        //判断  arr_content 是否是个数组
        if (!is_array($arr_content)) {
            return json_encode(['errno' => 1, 're' => '这里是批量查询，内容必须是个数组']);
        }

        if ($model == null) {
            $model = self::$model;
        }
        if ($engine == null) {
            $engine = self::$engine;
        }
        
        $httpClient = new HttpJsonApi();
        $res = $httpClient->jsonPost("http://111.231.14.202:5555/api/chats/$engine/$model", [
            "text" => $arr_content, 
            "token" => env('WR_KEY', ''), 
            "version" => 0,
            'mitm'=>true]);
        if (isset($res['errno']) && $res['errno'] == 0) {
            return $res['re_list'];
        } else {
            return ['errno' => 1, 're_list' => ''];
        }
    }


}
