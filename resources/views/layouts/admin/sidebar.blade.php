@php
    $isUserActive = request()->routeIs('admin.index') || request()->routeIs('admin.user.*');
    $isArticleMenuActive = request()->routeIs('admin.article.*') || request()->routeIs('admin.category.*');
    $isProductMenuActive = request()->routeIs('admin.product.*') || request()->routeIs('admin.product_category.*');
    $isSiteSettingActive = request()->routeIs('admin.site_setting.*');
@endphp

<div class="sidebar-wrapper" sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper">
            <a href="{{ route('index') }}">
                <img class="img-fluid for-light" src="{{ asset('assets/images/logo/admin_logo.png') }}" style="width: 160px" alt="">
                <img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt="">
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle" data-feather="grid"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="{{ route('index') }}">
                <img class="img-fluid" src="{{ asset('assets/images/logo/logo-small-light.png') }}" alt="">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="{{ route('index') }}">
                            <img class="img-fluid" src="{{ asset('assets/images/logo/logo-small-light.png') }}" alt="">
                        </a>
                        <div class="mobile-back text-end">
                            <span>{{ trans('menu.back') }}</span>
                            <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
                        </div>
                    </li>

                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>后台管理</h6>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ $isUserActive ? 'active' : '' }}" href="{{ route('admin.user.index') }}">
                            <i class="fas fa-user m-2"></i>
                            <span>用户列表</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ $isArticleMenuActive ? 'active' : '' }}" href="javascript:void(0)">
                            <i class="fas fa-newspaper m-2"></i>
                            <span>文章管理</span>
                        </a>
                        <ul class="sidebar-submenu" style="{{ $isArticleMenuActive ? 'display: block;' : '' }}">
                            <li>
                                <a class="{{ request()->routeIs('admin.category.*') ? 'active' : '' }}" href="{{ route('admin.category.index') }}">
                                    文章分类
                                </a>
                            </li>
                            <li>
                                <a class="{{ request()->routeIs('admin.article.*') ? 'active' : '' }}" href="{{ route('admin.article.index') }}">
                                    文章列表
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title {{ $isProductMenuActive ? 'active' : '' }}" href="javascript:void(0)">
                            <i class="fas fa-box-open m-2"></i>
                            <span>产品管理</span>
                        </a>
                        <ul class="sidebar-submenu" style="{{ $isProductMenuActive ? 'display: block;' : '' }}">
                            <li>
                                <a class="{{ request()->routeIs('admin.product.index') || request()->routeIs('admin.product.edit') || request()->routeIs('admin.product.update') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">
                                    产品列表
                                </a>
                            </li>
                            <li>
                                <a class="{{ request()->routeIs('admin.product_category.*') ? 'active' : '' }}" href="{{ route('admin.product_category.index') }}">
                                    产品分类
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav {{ $isSiteSettingActive ? 'active' : '' }}" href="{{ route('admin.site_setting.index') }}">
                            <i class="fas fa-cog m-2"></i>
                            <span>站点设置</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>
