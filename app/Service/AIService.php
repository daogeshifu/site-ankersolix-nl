<?php

namespace App\Service;

use App\Common\HttpJsonApi;
use App\Models\Geo\BrandGraderTask;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use Exception;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Http;
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
    const MODEL_MOONSHOT_V1_AUTO   = 'moonshot-v1-auto';
    const MODEL_GPT_4O_MINI        = 'gpt-4o-mini';
    const MODEL_GPT_4O             = 'gpt-4o';
    const MODEL_GPT_4_1            = 'gpt-4-1';
    const MODEL_GPT_5              = 'gpt-5';
    const MODEL_DS_R1              = 'r1';
    const MODEL_DS_V3              = 'v3';
    const MODEL_DS_Z1_9B           = 'z1-9b';
    const MODEL_DS_Z1_32B          = 'z1-32b';
    const MODEL_QWEN_32B           = 'qwq-32b';
    const MODEL_GEMINI_2_5_PRO     = 'gemini-2-5-pro';
    const MODEL_CLAUDE_4_SONNET    = 'claude-sonnet-4';
    const MODEL_CLAUDE_4_1_OPUS    = 'claude-opus-4-1';
    const MODEL_CLAUDE_4_5_SONNET  = 'claude-sonnet-4-5';

    // ==== 引擎常量 ====
    const ENGINE_KIMI              = 'kimi';
    const ENGINE_AZURE             = 'azure';
    const ENGINE_AZURE_SUZHOU      = 'azure-suzhou';
    const ENGINE_AZURE_SUZHOU_GPT5 = 'azure-suzhou-gpt5';
    const ENGINE_DEEPSEEK          = 'deepseek';
    const ENGINE_DEEPSEEK_V3       = 'deepseek-v3';
    const ENGINE_DEEPSEEK_Z1_9B    = 'deepseek-z1-9b';
    const ENGINE_DEEPSEEK_Z1_32B   = 'deepseek-z1-32b';
    const ENGINE_DEEPSEEK_OFFICE   = 'deepseek_office';
    const ENGINE_DEEPSEEK_OFFICE_V3= 'deepseek_office-v3';
    const ENGINE_QWEN              = 'qwen';
    const ENGINE_OPENROUTER        = 'openrouter';

    /**
     * 引擎与模型的映射
     * - 每个引擎可以对应多个模型
     */
    protected static array $engineModelMap = [
        self::ENGINE_KIMI              => [self::MODEL_MOONSHOT_V1_AUTO],

        self::ENGINE_AZURE             => [self::MODEL_GPT_4O_MINI],
        self::ENGINE_AZURE_SUZHOU      => [self::MODEL_GPT_4O, self::MODEL_GPT_4_1],
        self::ENGINE_AZURE_SUZHOU_GPT5 => [self::MODEL_GPT_5],

        self::ENGINE_DEEPSEEK          => [self::MODEL_DS_R1],
        self::ENGINE_DEEPSEEK_V3       => [self::MODEL_DS_V3],
        self::ENGINE_DEEPSEEK_Z1_9B    => [self::MODEL_DS_Z1_9B],
        self::ENGINE_DEEPSEEK_Z1_32B   => [self::MODEL_DS_Z1_32B],

        self::ENGINE_DEEPSEEK_OFFICE   => [self::MODEL_DS_R1],
        self::ENGINE_DEEPSEEK_OFFICE_V3=> [self::MODEL_DS_V3],

        self::ENGINE_QWEN              => [self::MODEL_QWEN_32B],

        self::ENGINE_OPENROUTER        => [
            self::MODEL_GEMINI_2_5_PRO,
            self::MODEL_CLAUDE_4_SONNET,
            self::MODEL_CLAUDE_4_1_OPUS,
            self::MODEL_CLAUDE_4_5_SONNET,
        ],
    ];

    /**
     * 根据 engine 获取默认 model（取第一个）
     */
    public static function getModelByEngine(?string $engine = null): ?string
    {
        $engine = $engine ?? self::$engine;

        return self::$engineModelMap[$engine][0] ?? null;
    }

    /**
     * 根据 model 获取对应的 engine
     * - 如果一个 model 对应多个 engine，返回第一个匹配的
     */
    public static function getEngineByModel(?string $model = null): ?string
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
     *  微软的 ai，并且 设置 jsonpost 的 请求时间永不超时
     * 
     */
    public function chat($content,$model = false,$engine = false){
        set_time_limit(-1);
        if($model == false){
            $model = self::$model;
        }
        if($engine == false){
            $engine = self::getEngineByModel($model);
        }
        $httpClient = new HttpJsonApi();
        $res = $httpClient->jsonPost("http://111.231.14.202:5555/api/chats/$engine/$model",["text"=>$content,"token"=> "10a8ed53-e497-4f59-9662-0c650dd889ff","version"=>0]);
        if (isset($res['errno']) && $res['errno'] == 0) {
            return $res['re'];
        } else {
            return json_encode(['errno' => 1, 're' => '']);
        }
    }


    /**
     * 聊天的异步模式， 返回聊天的任务 id
     */
    public function chatAsync($content,$model = null,$engine = false){
        set_time_limit(-1);
        if($model == false){
            $model = self::$model;
        }
        if($engine == false){
            $engine = self::getEngineByModel($model);
        }

        $httpClient = new HttpJsonApi();
        $res = $httpClient->jsonPost("http://111.231.14.202:5555/api/chatAsync/$engine/$model",[
            "text"=>$content,
            "token"=> "10a8ed53-e497-4f59-9662-0c650dd889ff",
            "version"=>0,
            "b64_result"=>1
        ]);
        
        if (isset($res['errno']) && $res['errno'] == 0) {
            return ['errno' => 0, 're' => $res['re']];
        } else {
            return ['errno' => 1, 're' => ''];
        }
    }

    /**
     * 获取聊天的任务结果
     * 
     * errno
    0:接口无异常且任务成功完成
    1：接口异常
    2：接口正常返回，但任务不是完成状态
   - message值为pending：任务未处理
   - message值为processing：任务正在处理
   - message值为failed：任务处理失败，但后续有重试操作可能会变为成功

     */
    public function getChatAsyncResult($task_id){
        $httpClient = new HttpJsonApi();
        $res = $httpClient->get("http://111.231.14.202:5555/api/chatResult/$task_id",[
            "token"=> "10a8ed53-e497-4f59-9662-0c650dd889ff",
            "version"=>0,
            "b64_result"=>1
        ]);

        //如果 errno 为 1，则返回错误
        if (isset($res['errno']) && $res['errno'] == 1) {
            return ['errno' => 1, 're' => ''];
        }

        //如果 errno 为 2，则 log 处理中
        if (isset($res['errno']) && $res['errno'] == 2) {
            // Log::info('getChatAsyncResult 处理中', ['task_id' => $task_id, 'res' => $res]);
            return ['errno' => 2, 're' => ''];
        }

        //如果 errno 为 0，则返回结果
        if (isset($res['errno']) && $res['errno'] == 0) {
            return ['errno' => 0, 're' => base64_decode($res['re'])];
        }
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
        $res = $httpClient->jsonPost("http://111.231.14.202:5555/api/chats/$engine/$model", ["text" => $arr_content, "token" => "10a8ed53-e497-4f59-9662-0c650dd889ff", "version" => 0]);
        if (isset($res['errno']) && $res['errno'] == 0) {
            return $res['re_list'];
        } else {
            return json_encode(['errno' => 1, 're_list' => '']);
        }
    }


    /**
     *  deepseek的ai
     */
    public function deepseekChat(string $content)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer sk-c69e68359c944319ad7127a4e2171d4f'
            ];

            $bodyArray = [
                "messages" => [
                    [
                        "content" => $content,
                        "role" => "system"
                    ],
                    [
                        "content" => "Hi",
                        "role" => "user"
                    ]
                ],
                "model" => "deepseek-chat",
                "frequency_penalty" => 0,
                "max_tokens" => 2048,
                "presence_penalty" => 0,
                "response_format" => [
                    "type" => "text"
                ],
                "stop" => null,
                "stream" => false,
                "stream_options" => null,
                "temperature" => 1,
                "top_p" => 1,
                "tools" => null,
                "tool_choice" => "none",
                "logprobs" => false,
                "top_logprobs" => null
            ];

            $body = json_encode($bodyArray);
            $request = new Request('POST', 'https://api.deepseek.com/chat/completions', $headers, $body);
            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            if (isset($res['choices'][0]['message']['content'])) {
                $content = $res['choices'][0]['message']['content'];
                $rst['errno'] = 0;
                $rst['re'] = $content;
                return $rst['re'];
            } else {
                return '';
            }
        } catch (\Exception $e) {
            Log::error('deepseekChat error: ' . $e->getMessage());
            return '';
        }
    }


    public function formatAiReturnToJson($content){
        $content = str_replace('```json', '', $content);
        $content = str_replace('```', '', $content);
        $content = json_decode($content, true);
        return $content;
    }
}