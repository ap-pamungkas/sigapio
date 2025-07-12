<div id="sidebar" class="app-sidebar">
    <!-- BEGIN scrollbar -->
    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
        <!-- BEGIN menu -->
        <hr>
        <div class="menu">


            <x-layouts.komando.sidebar.menu-item class="{{ request()->is('komando/beranda' ) ? 'active' : '' }}" url="komando/beranda" icon="fas fa-chart-simple" label="BERANDA" />
            {{-- <x-layouts.komando.sidebar.menu-item url="komando/tracking-petugas" icon="fas fa-map-location" label="TRACKING PETUGAS" />
            <x-layouts.komando.sidebar.menu-item url="komando/petugas" icon="fas fa-users" label="PETUGAS" />
            <x-layouts.komando.sidebar.menu-item url="komando/petugas/registrasi" icon="fas fa-camera" label="REGISTRASI PETUGAS" />
            <x-layouts.komando.sidebar.menu-item url="komando/perangkat" icon="fas fa-mobile" label="PERANGKAT" /> --}}
            <x-layouts.komando.sidebar.menu-item class="{{ request()->is('komando/insiden*' ) ? 'active' : '' }}" url="komando/insiden" icon="fas fa-triangle-exclamation" label="INSIDEN" />


        </div>
        <!-- END menu -->
    </div>
    <!-- END scrollbar -->
</div>
