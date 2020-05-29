@include('base.base')
@yield('header_data')


@yield('nav')
	<div class="contenedor_dashboard">
		<div class="cont_scroll">


			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Roles</h6>
			</div>

			<div class="contenedor_centro">
				<h3>Storage Services disconect</h3>
				<p>Please, verify your connection or try later...</p>
			</div>

		</div>
	</div>

@yield('footer_data')