<div id="header" class="app-header">
    <!-- BEGIN desktop-toggler -->
    <div class="desktop-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed"
            data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END desktop-toggler -->

    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled"
            data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <a href="index.html" class="brand-logo w-100">
            <div class="mt-2">
                <i class="fa fa-fire fa-2x " style="color: red;"></i>
                <span class="brand-text fw-500 fs-3">SIGAP-IO</span>
            </div>
        </a>
    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu">





        <div class="menu-item dropdown">
            <a href="#" data-toggle="theme-panel-expand" class="menu-link menu-link-icon">
                <i class="fa fa-gear fa-2x"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end fade">
                <h6 class="dropdown-header">Settings</h6>

            </div>
        </div>
        <div class="menu-item dropdown dropdown-mobile-full">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static"
                class="menu-link d-flex align-items-center">
                <div class="menu-img online me-sm-2 ms-lg-0 ms-n2">
                    <img src="{{ url('public/komando') }}/assets/img/user/profile.jpg" alt="Profile"
                        class="" />
                </div>
                <div class="menu-text d-sm-block d-none">
                    <span class="d-block"><span>{{ auth()->user()->nama }}</span></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end me-lg-3 fs-10px fade">
                {{-- <h6 class="dropdown-header">USER OPTIONS</h6> --}}



                <form method="POST" action="{{ route('komando.logout') }}">
                    @csrf
                    <button class="dropdown-item ">
                        <span>LOG OUT</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
    <!-- END menu -->

    <!-- BEGIN menu-search-float -->
    <form class="menu-search-float" method="POST" name="header_search_form">
        <div class="menu-search-container">
            <div class="menu-search-icon"><i class="bi bi-search"></i></div>
            <div class="menu-search-input">
                <input type="text" class="form-control" placeholder="Search something..." />
            </div>
            <div class="menu-search-icon">
                <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i
                        class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
    <!-- END menu-search-float -->
</div>
