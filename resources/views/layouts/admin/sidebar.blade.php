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
                <img class="img-fluid" src="{{ asset('assets/images/logo/logo-small-light.png') }}" alt=""></a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="{{ route('index') }}">
                            <img class="img-fluid" src="{{ asset('assets/images/logo/logo-small-light.png') }}" alt=""></a>
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

                    <li class=" sidebar-main-title">
                        <div>
                            <h6>后台管理</h6>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav active" href="{{ route('admin.index') }}">
                            <i class="fas fa-user m-2"></i>
                            <span>用户列表</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.article_task.index') }}">
                            <i class="fas fa-user m-2"></i>
                            <span>文章任务</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.article.index') }}">
                            <i class="fas fa-file-alt m-2"></i>
                            <span>文章列表</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.product.index') }}">
                            <i class="fas fa-box-open m-2"></i>
                            <span>商品列表</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.category.index') }}">
                            <i class="fas fa-folder m-2"></i>
                            <span>分类管理</span>
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
