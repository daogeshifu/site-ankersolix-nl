<?php

namespace App\Service;

use App\Common\HttpJsonApi;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * 翻译服务
 * 提供API翻译和AI翻译两种方式
 * 
 * @package App\Service
 */
class TranslationService
{
    /** @var HttpJsonApi HTTP客户端实例 */
    protected HttpJsonApi $httpClient;

    /** @var AIService AI服务实例 */
    protected AIService $aiService;

    public static $model = 'gpt-4o';
    public static $engine = 'azure-suzhou';

    /** @var string 翻译API地址 */
    private const TRANSLATE_API_URL = 'http://203.12.200.40/translate/from/auto/to/';

    /**
     * 构造函数，初始化HTTP客户端和AI服务
     */
    public function __construct()
    {
        $this->httpClient = new HttpJsonApi();
        $this->aiService = new AIService();
    }

    /**
     * 调用翻译API进行翻译
     * 
     * @param string $content 待翻译的内容
     * @param string $targetLanguage 目标语言代码（如：zh-CN, en, ja等）
     * @param string $sourceLanguage 源语言代码（默认：auto自动检测）
     * @return string 翻译后的内容，失败返回空字符串
     */
    public function translateByApi(string $content, string $targetLanguage, string $sourceLanguage = 'auto'): string
    {
        try {
            // 验证输入参数
            if (empty($content)) {
                Log::warning('API翻译：内容为空');
                return '';
            }

            if (empty($targetLanguage)) {
                Log::warning('API翻译：目标语言为空');
                return '';
            }

            // 设置超时时间
            set_time_limit(300);

            // 构建完整的API URL
            $apiUrl = self::TRANSLATE_API_URL . $targetLanguage;

            // 设置请求头
            $headers = [
                'Authorization' => 'Bearer bbe9cf87-f330-4076-a92c-300a76f9092b'
            ];

            // 调用翻译API
            $response = $this->httpClient->jsonPost($apiUrl, [
                'content' => $content
            ], $headers);

            // 提取翻译结果
            $translatedText = $response['result'] ?? '';

            if (empty($translatedText)) {
                Log::error('API翻译结果为空', [
                    'target_language' => $targetLanguage,
                    'content_length' => strlen($content),
                    'response' => $response
                ]);
            }

            return $translatedText;

        } catch (Exception $e) {
            Log::error('API翻译异常', [
                'message' => $e->getMessage(),
                'target_language' => $targetLanguage,
                'content_length' => strlen($content ?? '')
            ]);
            return '';
        }
    }

    /**
     * 使用AI进行翻译（将内容分成三段分别翻译）
     * 使用GPT-4-1模型进行翻译
     * 
     * @param string $content 待翻译的内容
     * @param string $targetLanguage 目标语言（如：English, Chinese, Japanese等）
     * @return string 翻译后的完整内容，失败返回空字符串
     */
    public function translateByAI(string $content, string $targetLanguage): string
    {
        try {
            // 验证输入参数
            if (empty($content)) {
                Log::warning('AI翻译内容为空');
                return '';
            }

            if (empty($targetLanguage)) {
                Log::warning('AI翻译目标语言为空');
                return '';
            }

            // 设置超时时间
            set_time_limit(600);

            // 将内容分成三段
            $contentSegments = $this->splitContentIntoThreeParts($content);

            Log::info('AI翻译内容分成三段', [
                'total_length' => strlen($content),
                'segment_1_length' => strlen($contentSegments[0]),
                'segment_2_length' => strlen($contentSegments[1]),
                'segment_3_length' => strlen($contentSegments[2])
            ]);

            // 存储翻译结果
            $translatedSegments = [];

            // 分别翻译每一段
            foreach ($contentSegments as $index => $segment) {
                if (empty($segment)) {
                    $translatedSegments[] = '';
                    continue;
                }

                // 构建翻译提示词
                $prompt = $this->buildTranslationPrompt($segment, $targetLanguage);

                Log::info('AI翻译第'.($index + 1).'段', [
                    'segment_index' => $index + 1,
                    'segment_length' => strlen($segment)
                ]);

                // 调用AI进行翻译
                $translatedText = $this->aiService->chat(
                    $prompt,
                    AIService::MODEL_GPT_5
                );

                // 检查翻译结果
                if (empty($translatedText)) {
                    Log::error('AI翻译第'.($index + 1).'段失败:'.$translatedText, [
                        'segment_index' => $index + 1,
                        'segment_length' => strlen($segment)
                    ]);
                    $translatedSegments[] = $segment; // 翻译失败时保留原文
                } else {
                    // 清理AI返回的多余内容（如果有JSON包裹或markdown标记）
                    $cleanedText = $this->cleanAIResponse($translatedText);
                    $translatedSegments[] = $cleanedText;
                }
            }

            // 合并翻译后的内容
            $finalTranslation = implode('', $translatedSegments);

            Log::info('AI翻译完成', [
                'original_length' => strlen($content),
                'translated_length' => strlen($finalTranslation),
                'target_language' => $targetLanguage
            ]);

            return $finalTranslation;

        } catch (Exception $e) {
            Log::error('AI翻译异常:'.$e->getMessage(), [
                'message' => $e->getMessage(),
                'target_language' => $targetLanguage,
                'content_length' => strlen($content ?? '')
            ]);
            return '';
        }
    }

    /**
     * 将内容平均分成三段
     * 
     * @param string $content 待分割的内容
     * @return array 包含三个字符串元素的数组
     */
    private function splitContentIntoThreeParts(string $content): array
    {
        $totalLength = strlen($content);

        // 如果内容太短，直接返回
        if ($totalLength <= 3) {
            return [$content, '', ''];
        }

        // 计算每段的长度
        $segmentLength = (int) ceil($totalLength / 3);

        // 分割内容
        $segment1 = substr($content, 0, $segmentLength);
        $segment2 = substr($content, $segmentLength, $segmentLength);
        $segment3 = substr($content, $segmentLength * 2);

        return [$segment1, $segment2, $segment3];
    }

    /**
     * 构建翻译提示词
     * 
     * @param string $content 待翻译内容
     * @param string $targetLanguage 目标语言
     * @return string 完整的提示词
     */
    private function buildTranslationPrompt(string $content, string $targetLanguage): string
    {
        return "你是一名享誉世界的翻译家，翻译的时候遵循原则：信达雅。把下面的内容翻译成 {$targetLanguage}. 只输出翻译后的文本，保持原来的格式，不要添加任何解释、备注或其他额外内容。\n\n内容：\n{$content}";
    }

    /**
     * 清理AI响应中的多余内容
     * 移除可能存在的markdown标记、JSON包裹等
     * 
     * @param string $response AI返回的响应
     * @return string 清理后的内容
     */
    private function cleanAIResponse(string $response): string
    {
        // 移除JSON格式包裹
        $cleaned = preg_replace('/^```json\s*/i', '', $response);
        $cleaned = preg_replace('/\s*```$/i', '', $cleaned);
        $cleaned = preg_replace('/^```\s*/i', '', $cleaned);

        // 尝试解析JSON（如果AI返回了JSON格式）
        $decoded = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // 如果是JSON格式，尝试提取常见的字段
            if (isset($decoded['errno']) && $decoded['errno'] == 0 && isset($decoded['re'])) {
                return $decoded['re'];
            }
            if (isset($decoded['translated_text'])) {
                return $decoded['translated_text'];
            }
            if (isset($decoded['result'])) {
                return $decoded['result'];
            }
        }

        // 去除首尾空白
        return trim($cleaned);
    }

    /**
     * 获取支持的语言代码映射
     * 用于API翻译
     * 
     * @return array 语言名称到语言代码的映射
     */
    public static function getSupportedLanguageCodes(): array
    {
        return [
            'Chinese' => 'zh-CN',
            'English' => 'en',
        ];
    }
}

