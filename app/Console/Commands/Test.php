<?php

namespace App\Console\Commands;

use App\Service\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';


    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // $this->countWord();
        $this->aiTest();
    }
    
    private function countWord(){
        function countWords(string $sentence): int {
            // 1. 去除首尾空格
            $trimmed = trim($sentence);
            // 2. 按任意空白字符（空格、换行、制表符等）分割，并过滤空元素
            $words = preg_split('/\s+/', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
            return count($words);
        }
        
        // 示例测试
        echo countWords("The quick brown fox jumps.");          // 5
        echo countWords("Hello  world!   How are you?");        // 5（多个空格和标点不影响）
        echo countWords("This is a test\nwith a new line.");    // 7（换行符不影响）
        echo countWords("  Trim  extra  spaces  ");             // 3（首尾和中间多余空格不影响）
        echo countWords("123 apples, 45.6% oranges!");          // 4（数字和符号视为单词的一部分）
    }

    /*
     * AI测试
     */
    private function aiTest(){

        $aiService = new AIService();
        $pha = "As ChatGPT-generated content floods academia and social media (Oxford reports 76% AI-assisted essays in 2024), Phrasly AI Checker emerges as the literary bloodhound you never knew you needed. Through forensic linguistic analysis and real-time coaching, this AI detective doesn't just catch robot writers - it teaches humans to dance with machines. Discover how a Bali blogger's viral downfall exposed AI's telltale \"idyllic\" addiction, and learn 3 ninja tactics to cleanly hybridize human-AI content using phrasly.ai’s toolkit.";

        detector($aiService, $pha);
        function detector(AIService $aiService, String $pha){
            $prompt = '假设你是一个AI分析师，请严格分析以下文本，按段落评估其AI生成概率。
                要求：1. 自动划分合理段落（如每段3-5句）
                2. 对每个段落单独计算AI率（0-100%），精确到整数
                3. 返回json，输出格式严格遵循：
                {
                    "ai_probability": 总AI率,
                    "contents": {
                        "content_1": { "content": "...", "ai_rate": AI率 },
                        "content_2": { "content": "...", "ai_rate": AI率 }
                    }
                }
                ，空白段落则不计入，在内容中原文分段的位置添加换行符
                4，多个差值小于0.1的段落合并. 不提供额外分析，仅返回段落和对应AI率
                5. 如果整段明显为人类写作（AI率≤30%），可合并多个低AI率段落。
                ';
            
            $promptPha = "$prompt 以下为主要内容：\n\n$pha";
            $response = $aiService->chat($promptPha);
            Log::info('API 请求结果', ['response' => $response]);
            dump($response);
    
            $formattedResponse = [
                'errno' => 0, // 成功状态码
                'data' => $response // 原始数据放入 data 字段
            ];
            $totalProbability = 0;
            if ($formattedResponse && isset($formattedResponse['errno']) && 0 === $formattedResponse['errno']) {
                dump('0');
                dump('1');
                dump(json_decode($formattedResponse['data'])['ai_probability']);
                dump('2');
                if (isset($responseData['ai_probability'])) {
                    // $totalProbability += $responseData['ai_probability'];
                }
                dump($totalProbability);
                dd('success!');
            } else {
                throw new \Exception("API 请求失败，response: " . json_encode($response));
            }
        };

        function reducer(AIService $aiService, String $pha){
            $prompt = '你是一个AI人性化写作专家，请重写以下内容，使其尽可能降低AI生成概率。
                要求：
                1. 参考人类真实写作风格进行改写，避免模板化、重复结构、空洞语句；
                2. 段落自动划分为3-5句的自然段，符合中文写作逻辑；
                3. 不使用冗余连接词（如“首先”、“此外”等），保持语意连贯但自然；
                3. 返回json，输出格式严格遵循：
                {
                    "ai_probability": 总AI率,
                    "content_before": {
                        "content_1": { "content": "...原句", "ai_probability_before": 原句AI率, },
                        "content_2": { "content": "...原句", "ai_probability_before": 原句AI率, }
                    }
                    "content_after": {
                        "content_1": { "content": "...改写句", "ai_probability_after": 重写后AI率 },
                        "content_2": { "content": "...改写句", "ai_probability_after": 重写后AI率 }
                    }
                }
                4. 避免使用AI常见词汇模式及平均句长，应有长短句交错，语言风格具备一定多样性；
                5. 保留原意并尽量贴近真实人类思维表达过程；
                7. 每个段落在内容部分添加换行符，不要添加到结构部分。';
            
            $promptPha = "$prompt 以下为主要内容：\n\n$pha";
            $response = $aiService->chat($promptPha);
            Log::info('API 请求结果', ['response' => $response]);
            dump($response);
    
            $formattedResponse = [
                'errno' => 0, // 成功状态码
                'data' => $response // 原始数据放入 data 字段
            ];
            $totalProbability = 0;
            if ($formattedResponse && isset($formattedResponse['errno']) && 0 === $formattedResponse['errno']) {
                dump('0');
                dump('1');
                dump(json_decode($formattedResponse['data'])['ai_probability']);
                dump('2');
                if (isset($responseData['ai_probability'])) {
                    // $totalProbability += $responseData['ai_probability'];
                }
                dump($totalProbability);
                dd('success!');
            } else {
                throw new \Exception("API 请求失败，response: " . json_encode($response));
            }
        }
    }
}
