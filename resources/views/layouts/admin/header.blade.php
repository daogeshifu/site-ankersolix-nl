@php
    $adminPageTitle = match (true) {
        request()->routeIs('admin.index') => '控制台',
        request()->routeIs('admin.user.*') => '用户管理',
        request()->routeIs('admin.article_task.*') => '文章任务',
        request()->routeIs('admin.article.*') => '文章管理',
        request()->routeIs('admin.category.*') => '文章分类',
        request()->routeIs('admin.tag.*') => '标签管理',
        request()->routeIs('admin.product_category.*') => '产品分类',
        request()->routeIs('admin.product.*') => '产品管理',
        request()->routeIs('admin.site_setting.*') => '站点设置',
        default => '管理后台',
    };
@endphp

<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <a class="admin-mobile-brand" href="{{ route('admin.index') }}" aria-label="Beste Thuisbatterij beheer">
                <span class="admin-brand-mark">BT</span>
            </a>
            <button type="button" class="toggle-sidebar admin-icon-button" aria-label="导航菜单">
                <i class="status_toggle middle sidebar-toggle" data-feather="menu"></i>
            </button>
        </div>

        <div class="admin-header-context">
            <span>Beste Thuisbatterij</span>
            <strong>@yield('title', $adminPageTitle)</strong>
        </div>

        <div class="nav-right col-auto ms-auto p-0">
            <ul class="nav-menus">
                <li>
                    <a href="{{ route('index') }}" target="_blank" class="admin-site-link">
                        <i data-feather="external-link"></i><span>网站前台</span>
                    </a>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 py-0">
                    <div class="media profile-media admin-profile-trigger">
                        <span class="admin-avatar">{{ strtoupper(mb_substr(Auth::user()?->name ?? 'A', 0, 1)) }}</span>
                        <div class="media-body">
                            <strong>{{ Auth::user()?->name ?? 'Administrator' }}</strong>
                            <span>管理员</span>
                        </div>
                        <i data-feather="chevron-down"></i>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"><i data-feather="log-out"></i><span>退出登录</span></button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
