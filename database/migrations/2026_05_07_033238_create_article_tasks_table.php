<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_tasks', function (Blueprint $table) {

            $table->id();

            // 关键词
            $table->string('keyword')->comment('关键词');

            // 本地批次号
            $table->string('local_batch_no')
                ->nullable()
                ->comment('本地批次号，同一次提交的关键词共用同一批次');

            // 文章栏目
            $table->unsignedBigInteger('category_id')
                ->comment('文章栏目ID');

            // 写作模式
            $table->string('writer_category')
                ->default('seo')
                ->comment('远程写作模式：seo/geo');

            // 写作语言
            $table->string('writer_language')
                ->default('Dutch')
                ->comment('远程生成语言');

            // 补充信息
            $table->text('info')
                ->nullable()
                ->comment('额外写作信息');

            // 品牌信息
            $table->text('brand_info')
                ->nullable()
                ->comment('品牌信息');

            // 强制刷新
            $table->boolean('force_refresh')
                ->default(false)
                ->comment('是否强制刷新缓存');

            // 是否生成封面
            $table->boolean('include_cover')
                ->default(true)
                ->comment('是否生成封面图');

            // 正文图片数量
            $table->unsignedTinyInteger('content_image_count')
                ->default(3)
                ->comment('正文配图数量');

            // 状态
            $table->tinyInteger('status')
                ->default(0)
                ->comment('状态:
                    0=待处理,
                    1=已提交远程,
                    2=已获取任务ID,
                    3=已完成,
                    -1=失败');

            // 定时发布日期
            $table->date('scheduled_date')
                ->nullable()
                ->comment('定时发布日期，null=立即处理，>=今天才提交远程');

            // 远程批次ID
            $table->string('remote_batch_id')
                ->nullable()
                ->comment('远程批次ID');

            // 远程任务ID
            $table->string('remote_task_id')
                ->nullable()
                ->comment('远程任务ID');

            // 远程状态
            $table->string('remote_status')
                ->nullable()
                ->comment('远程任务状态');

            // 请求快照
            $table->longText('remote_request_payload')
                ->nullable()
                ->comment('提交远程时的请求快照');

            // 结果快照
            $table->longText('remote_result_payload')
                ->nullable()
                ->comment('远程返回结果快照');

            // 远程创建时间
            $table->timestamp('remote_created_at')
                ->nullable()
                ->comment('远程任务创建时间');

            // 最后轮询时间
            $table->timestamp('remote_last_polled_at')
                ->nullable()
                ->comment('最后轮询时间');

            // 结果同步时间
            $table->timestamp('result_synced_at')
                ->nullable()
                ->comment('结果同步时间');

            // 发布后的文章ID
            $table->unsignedBigInteger('article_id')
                ->nullable()
                ->comment('发布后对应的文章ID');

            // 错误信息
            $table->text('error_message')
                ->nullable()
                ->comment('错误信息');

            // 失败次数
            $table->unsignedTinyInteger('fail_count')
                ->default(0)
                ->comment('失败次数');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | 索引
            |--------------------------------------------------------------------------
            */

            $table->index('status');
            $table->index('category_id');
            $table->index('local_batch_no');
            $table->index('remote_batch_id');
            $table->index('remote_task_id');
            $table->index('scheduled_date');

            /*
            |--------------------------------------------------------------------------
            | 外键
            |--------------------------------------------------------------------------
            */

            $table->foreign('category_id')
                ->references('id')
                ->on('article_categorys')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tasks');
    }
};
