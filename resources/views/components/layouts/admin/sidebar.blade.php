<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#" class="brand-link">
            <!--begin::Brand Image-->

            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <i class="fa fa-fire fa-2x " style="color: red;"></i>
            <span class="brand-text  fw-bold  fs-4">SIGAP-IO</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <li class="nav-item">

                    <x-layouts.admin.sidebar.menu-items :url="'admin.beranda'" icon="fas fa-home" class="nav-link"
                        :active="request()->is('admin/beranda')" label="Beranda" />
                </li>

                <li class="nav-item">

                    <x-layouts.admin.sidebar.menu-items icon="fas fa-mobile" :url="'admin.perangkat'" class="nav-link"
                        :active="request()->is('admin/perangkat')" label="Perangkat" />
                </li>

                <li class="nav-item">

                    <x-layouts.admin.sidebar.menu-items :url="'admin.insiden'" icon="fa-solid fa-circle-exclamation"
                        class="nav-link" :active="request()->is('admin/insiden') || request()->is('admin/insiden/*')" label="Insiden" />
                </li>
                <li class="nav-item">
                    <x-layouts.admin.sidebar.menu-items :url="'admin.petugas'" icon="fa-solid fa-user" class="nav-link"
                        :active="request()->is('admin/petugas')" label="Petugas" />
                </li>
                <li class="nav-item">
                    <x-layouts.admin.sidebar.menu-items :url="'admin.user'" icon="fa-solid fa-users" class="nav-link"
                        :active="request()->is('admin/user')" label="Manajemen User" />
                </li>
                <li class="nav-item">
                    <x-layouts.admin.sidebar.menu-items :url="'admin.logAktivitas'" icon="fa-solid fa-clipboard" class="nav-link"
                        :active="request()->is('admin/log-aktivitas')" label="Log Aktivitas" />
                </li>



            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
