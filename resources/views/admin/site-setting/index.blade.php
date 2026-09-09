@extends('layouts.admin.master')

@section('content')
<div class="admin-page-heading">
    <div>
        <span class="admin-eyebrow">Website</span>
        <h1>站点设置</h1>
        <p>查看上线域名与当前运行配置。</p>
    </div>
    <a href="https://www.bestenthuisbatterij.nl/" target="_blank" rel="noopener" class="btn btn-primary">
        <i class="fa fa-external-link"></i> 访问正式站点
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">基础信息</h5>
                <p class="admin-card-subtitle">部署时由环境变量统一配置</p>
            </div>
            <div class="card-body">
                <div class="admin-setting-list">
                    <div><span>站点名称</span><strong>{{ config('app.name') }}</strong></div>
                    <div><span>正式域名</span><a href="https://www.bestenthuisbatterij.nl/" target="_blank" rel="noopener">https://www.bestenthuisbatterij.nl/</a></div>
                    <div><span>当前应用 URL</span><code>{{ config('app.url') }}</code></div>
                    <div><span>默认语言</span><strong>{{ strtoupper(config('app.locale')) }}</strong></div>
                    <div><span>运行环境</span><span class="badge bg-light text-dark">{{ app()->environment() }}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">部署提示</h5>
                <p class="admin-card-subtitle">上线前建议核对以下配置</p>
            </div>
            <div class="card-body">
                <ul class="admin-check-list">
                    <li><i class="fa fa-check"></i><span><strong>APP_URL</strong><small>设置为正式 HTTPS 域名</small></span></li>
                    <li><i class="fa fa-check"></i><span><strong>APP_DEBUG</strong><small>生产环境保持关闭</small></span></li>
                    <li><i class="fa fa-check"></i><span><strong>管理员账号</strong><small>首次部署执行 php artisan db:seed</small></span></li>
                    <li><i class="fa fa-check"></i><span><strong>缓存刷新</strong><small>部署完成后执行 optimize</small></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
