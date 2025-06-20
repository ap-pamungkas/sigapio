<!DOCTYPE html>
<html lang="id">

<!-- Mirrored from seantheme.com/quantum/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 21 May 2025 14:00:27 GMT -->
<head>
	<meta charset="utf-8" />
	<title> {{  config('app.name', '') }} || {{ $title ?? '' }}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- ================== BEGIN core-css ================== -->
	<link href="{{ url('public/komando') }}/assets/css/vendor.min.css" rel="stylesheet" />
	<link href="{{ url('public/komando') }}/assets/css/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
  <link rel="stylesheet" href="{{ url('public/fontawesome-free-6.7.2-web/css/all.css') }}">
	<link href="{{ url('public/komando') }}/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
@stack('styles')
@livewireStyles
</head>
<body >
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<div class="d-flex align-items-center">
			<div class="app-loader-circle"></div>
			<div class="app-loader-text">LOADING...</div>
		</div>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN #header -->
	<x-layouts.komando.header />
		<!-- END #header -->

		<!-- BEGIN #sidebar -->
		<x-layouts.komando.sidebar />
		<!-- END #sidebar -->

		<!-- BEGIN mobile-sidebar-backdrop -->
		<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
		<!-- END mobile-sidebar-backdrop -->

		<!-- BEGIN #content -->
		<div id="content" class="app-content p-3">

                <div class="container-fluid">
                  <div class="row">
                    <div class="card mb-5 mt-3 shadow-sm">
                        <div class="card-header">
                        <h1 class="text-theme">{{ $title}}</h1>
                     </div>
                   </div>

                   {{ $slot }}
                  </div>
                </div>

			<!-- END row -->
		</div>
		<!-- END #content -->

		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade">
			<iconify-icon icon="material-symbols-light:keyboard-arrow-up"></iconify-icon>
		</a>
		<!-- END btn-scroll-top -->

		<!-- BEGIN theme-panel -->
		<div class="app-theme-panel">
			<div class="app-theme-panel-container">
				<a style="text-decoration: none;" href="#" data-toggle="theme-panel-expand" class="app-theme-toggle-btn"><i class="fa fa-gear "></i></a>
				<div class="app-theme-panel-content">
					<div class="fs-10px fw-semibold text-white">
						THEME COLOR
					</div>
					<div class="fs-9px lh-sm mb-2 text-white text-opacity-75">
						Choose your favorite theme color
					</div>
					<!-- BEGIN theme-list -->
					<div class="app-theme-list">
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-pink" data-theme-class="theme-pink" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Pink">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-red" data-theme-class="theme-red" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Red">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-warning" data-theme-class="theme-warning" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Orange">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-yellow" data-theme-class="theme-yellow" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Yellow">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-lime" data-theme-class="theme-lime" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Lime">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-green" data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Green">&nbsp;</a></div>
						<div class="app-theme-list-item active"><a href="#" class="app-theme-list-link bg-teal" data-theme-class="" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Default">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-info" data-theme-class="theme-info"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Cyan">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-primary" data-theme-class="theme-primary"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-indigo" data-theme-class="theme-indigo" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Indigo">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-purple" data-theme-class="theme-purple" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Purple">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-white" data-theme-class="theme-white" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="White">&nbsp;</a></div>
					</div>
					<!-- END theme-list -->
				</div>
			</div>
		</div>
		<!-- END theme-panel -->
	</div>
	<!-- END #app -->
@livewireScripts
	<!-- ================== BEGIN core-js ================== -->

	<script src="{{ url('public/komando') }}/assets/js/vendor.min.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<script src="{{ url('public/komando') }}/assets/js/app.min.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<!-- ================== END core-js ================== -->

	<script src="{{ url('public/komando') }}/assets/plugins/jvectormap-next/jquery-jvectormap.min.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<script src="{{ url('public/komando') }}/assets/plugins/jvectormap-content/world-mill.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<script src="{{ url('public/komando') }}/assets/plugins/apexcharts/dist/apexcharts.min.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<script src="{{ url('public/komando') }}/assets/js/demo/dashboard.demo.js" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>


	<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y3Q0VGQKY3" type="9cb4bf91e82b9cd7fafe8f3d-text/javascript"></script>
	<script type="9cb4bf91e82b9cd7fafe8f3d-text/javascript">
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-Y3Q0VGQKY3');
	</script>
<script src="{{ url('public/komando') }}/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="9cb4bf91e82b9cd7fafe8f3d-|49" defer></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"943499bbba5ffd84","version":"2025.4.0-1-g37f21b1","r":1,"serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"4db8c6ef997743fda032d4f73cfeff63","b":1}' crossorigin="anonymous"></script>
<!-- Tom Select JS -->

   <script src="{{ url('public/fontawesome-free-6.7.2-web/js/all.js') }}"></script>
   @livewireScripts
@stack('scripts')

</body>


</html>
