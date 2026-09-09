@php
    $contentActive = request()->routeIs('admin.article.*') || request()->routeIs('admin.category.*') || request()->routeIs('admin.tag.*') || request()->routeIs('admin.article_task.*');
    $productActive = request()->routeIs('admin.product.*') || request()->routeIs('admin.product_category.*');
@endphp

<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper admin-logo-wrapper">
            <a href="{{ route('admin.index') }}" class="admin-brand">
                <span class="admin-brand-mark">BT</span>
                <span class="admin-brand-copy"><strong>Beste Thuisbatterij</strong><small>Beheeromgeving</small></span>
            </a>
            <button type="button" class="back-btn" aria-label="关闭菜单"><i class="fa fa-angle-left"></i></button>
        </div>
        <div class="logo-icon-wrapper">
            <a href="{{ route('admin.index') }}"><span class="admin-brand-mark">BT</span></a>
        </div>

        <nav class="sidebar-main">
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>返回</span><i class="fa fa-angle-right ps-2"></i></div>
                    </li>
                    <li class="sidebar-main-title"><div><h6>工作台</h6></div></li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                            <i class="fa fa-th-large"></i><span>控制台</span>
                        </a>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
                            <i class="fa fa-users"></i><span>用户管理</span>
                        </a>
                    </li>

                    <li class="sidebar-main-title"><div><h6>内容运营</h6></div></li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ $contentActive ? 'active' : '' }}" href="javascript:void(0)">
                            <i class="fa fa-newspaper-o"></i><span>内容管理</span>
                        </a>
                        <ul class="sidebar-submenu" style="{{ $contentActive ? 'display:block;' : '' }}">
                            <li><a class="{{ request()->routeIs('admin.article.*') ? 'active' : '' }}" href="{{ route('admin.article.index') }}">文章列表</a></li>
                            <li><a class="{{ request()->routeIs('admin.article_task.*') ? 'active' : '' }}" href="{{ route('admin.article_task.index') }}">文章任务</a></li>
                            <li><a class="{{ request()->routeIs('admin.category.*') ? 'active' : '' }}" href="{{ route('admin.category.index') }}">文章分类</a></li>
                            <li><a class="{{ request()->routeIs('admin.tag.*') ? 'active' : '' }}" href="{{ route('admin.tag.index') }}">标签管理</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-main-title"><div><h6>产品目录</h6></div></li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ $productActive ? 'active' : '' }}" href="javascript:void(0)">
                            <i class="fa fa-cubes"></i><span>产品管理</span>
                        </a>
                        <ul class="sidebar-submenu" style="{{ $productActive ? 'display:block;' : '' }}">
                            <li><a class="{{ request()->routeIs('admin.product.*') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">产品列表</a></li>
                            <li><a class="{{ request()->routeIs('admin.product_category.*') ? 'active' : '' }}" href="{{ route('admin.product_category.index') }}">产品分类</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-main-title"><div><h6>系统</h6></div></li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ request()->routeIs('admin.site_setting.*') ? 'active' : '' }}" href="{{ route('admin.site_setting.index') }}">
                            <i class="fa fa-cog"></i><span>站点设置</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
