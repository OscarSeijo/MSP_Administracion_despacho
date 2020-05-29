@section('header_data')
<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

    	<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- <link rel="icon" href="{{ asset('img/infradom_logo.png') }}"> -->

        <!--
		<link rel="stylesheet" type="text/css" href="{{secure_asset('/externo/clockpicker/clockpicker.css')}}">
		<link rel="stylesheet" type="text/css" href="{{secure_asset('/externo/timerpicker/jquery.datetimepicker.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{secure_asset('/css/app.css')}}"> 

        <link rel="stylesheet" type="text/css" href="{{ asset( '/externo/clockpicker/clockpicker.css', Request::secure() ) }}">
        <link rel="stylesheet" type="text/css" href="{{ asset( '/externo/timerpicker/jquery.datetimepicker.min.css', Request::secure() ) }}">
         <link rel="stylesheet" type="text/css" href="{{ asset( '/css/app.css', Request::secure() ) }}">  -->

        <link rel="stylesheet" type="text/css" href="{{ asset( '/css/app.css') }}"> 

        
	</head>
	<body>
		<div class="contenedor_general">
@endsection



@section('footer_data')
		</div>
	</body>
        <!-- JavaScript -->

            <script src="{{ asset( '/externo/jquery.js' ) }}"></script>
            <script src="{{ asset( '/externo/ajax.js' ) }}"></script>
            <script src="{{ asset( '/externo/bootstrap.js' ) }}"></script>


            <script src="{{ asset( '/externo/datatables/datatables.min.js' ) }}" type="text/javascript" charset="utf-8" async defer></script>
            <script src="{{ asset( '/externo/slick/slick.js' ) }}" type="text/javascript" charset="utf-8" async defer></script>
            <link rel="stylesheet" type="text/css" href="{{ asset( '/externo/slick/slick.css' ) }}">
            <script src="{{ asset( '/externo/inputmask/inputmask.js' ) }}"></script>
            <script src="{{ asset( '/externo/clockpicker/clockpicker.js' ) }}"></script>
            <script src="{{ asset( '/js/app.js') }}" type="text/javascript" charset="utf-8" defer></script>

        <!--
        	<script src="{{ asset( '/externo/jquery.js', Request::secure() ) }}"></script>
        	<script src="{{ asset( '/externo/ajax.js', Request::secure() ) }}"></script>
        	<script src="{{ asset( '/externo/bootstrap.js', Request::secure() ) }}"></script>


        	<script src="{{ asset( '/externo/datatables/datatables.min.js', Request::secure() ) }}" type="text/javascript" charset="utf-8" async defer></script>
            <script src="{{ asset( '/externo/slick/slick.js', Request::secure() ) }}" type="text/javascript" charset="utf-8" async defer></script>
            <link rel="stylesheet" type="text/css" href="{{ asset( '/externo/slick/slick.css', Request::secure() ) }}">
            <script src="{{ asset( '/externo/inputmask/inputmask.js', Request::secure() ) }}"></script>
        	<script src="{{ asset( '/externo/clockpicker/clockpicker.js', Request::secure() ) }}"></script>
            <script src="{{ asset( '/js/app.js', Request::secure() ) }}" type="text/javascript" charset="utf-8" defer></script>
-->


            

               <!--
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js" type="text/javascript" charset="utf-8" async defer></script>
            <script src="{{asset('/externo/ajax.js')}}"></script>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.0/cjs/popper.min.js" charset="utf-8" async defer></script>
            <script src="//stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>  -->

            <!--
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
            <script src="{{asset('/externo/timerpicker/jquery.datetimepicker.full.js')}}"></script> 
        -->


    </html>

@endsection






















@section('loading')

	<section class="loading">
		<div class="contenedor_centro"><img src="{{ asset('img/difradom_logo.png') }}" alt=""></div>
	</section>


@endsection








@section('nav')
	@if(Auth::check())

        <div class="background_nav_responsive"></div>


		<section class="contenedor_nav">



			<div id="left_nav" class="left_nav">
				<div class="logo_cont">

					<a href="/" title="">
						<img src="{{asset('img/logo-msp_completo.png')}}" alt="">
					</a>

				</div>
				<div class="opciones_menu">
					<div class="scroll">
						@php

                            // Primero llamamos los componentes del menu:
                            $menu = DB::table('menu_items')->orderBy('order', 'ASC')->get()->all();
                            // Verificamos el role del usuario en la tabla de "persmissions roles"
                            $roles = DB::table('permission_roles')->where('role_id', Auth::user()->role_id)->get();
                            // Del permission_roles, llamamos los menus que pertenezcan a ese grupo
                        @endphp

                        @foreach($roles as $value_roles)
                            @foreach($menu as $value_menu)
                                @if($value_roles->menu_id == $value_menu->id)

                                    @if($value_menu->status == 0)
                                        <a href="{{route($value_menu->route)}}" title="{{ $value_menu->title }}" target="{{ $value_menu->target }}">
                                            <div class="opcion">
                                                <i class="{{$value_menu->icon_class}}"></i>
                                                <h5>{{$value_menu->title}}</h5>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach


					</div>
				</div>
				<div class="proyectos">
					<h5>Eventos</h5>


					<div class="button_proyect">

						<a class="btn btn-secondary boton">
							<i class="fas fa-project-diagram"></i>
							CREAR PROYECTO
						</a>
					</div>


				</div>
			</div>







			<div class="top_nav shadow">

				<div class="contenedor">

                    <div id="icon_responsive_boton" class="nav_icon_responsive">
                        <i class="fas fa-bars"></i>
                    </div>

                    <!--
                    <div id="icon_search_boton" class="nav_icon_responsive">
                        <i class="fas fa-search"></i>
                    </div>

                    -->




					<div class="contenedor_search">
						<form action="{{ route('Search.All') }}" method="post" id="busqueda_general" accept-charset="utf-8">
							@csrf
							<div class="input_search shadow">
								<i class="fas fa-search"></i>
								<!-- id="search_all" -->
								<input type="text" name="search" max="40" id="search_all" placeholder="Busqueda general....">
							</div>

							<!-- <input type="submit" name="enviar"> -->
						</form>
						<div class="contenedor_resultado_busqueda_rapida shadow">
							<div id="busqueda_rapida" class="cont_scroll"></div>
						</div>
					</div>



					<div class="info_user_menu">

						<div class="icon notification">
							<i id="Show_Notification" class="far fa-bell"></i>


							<div id="cont_emer_notificaciones" class="cont_emer_notificaciones shadow">
                                <div class="intro">
                                    <h6>Notificaciones (1)</h6>
                                    <h6 id="markAllRead" class="markAllRead">Marcar todos leidos</h6>
                                </div>

                                <div class="notificaciones_contenedor">

                                    @php
                                        $notification = DB::table('notifications')
                                        ->where('role_id', Auth::user()->role_id )
                                        ->orderBy('id', 'DESC')
                                        ->get()->all();
                                    @endphp

                                    @foreach($notification as $value)

                                        <div class="noti">
                                            <div class="info">
                                                <h6>{{$value->title}}</h6>
                                                <p>{{$value->text}}</p>
                                                <p><i class="far fa-clock"></i>{{$value->created_at}}</p>
                                            </div>
                                        </div>


                                    @endforeach
                                    
                                  

                                </div>

                                <div class="seeMore">
                                    <button>Ver todas las notificaciones</button>
                                </div>
							</div>

						</div>


						<div class="user">
                            <a href="{{ route('Users.Profile', Auth::user()->id) }}">
                                <div class="avatar">
                                    <img src="{{asset('/storage')}}/{{Auth::user()->avatar}} " alt="">
                                </div>
                            </a>
                                <div class="name">
                                    <h6>{{ Auth::user()->name }}</h6>
                                    <p>Administrador</p>
                                </div>

						</div>
 
						<div class="icon logout">
                            <!-- style="display: none;"
							<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<i class="fas fa-sign-out-alt"></i>
		                    </a>-->

		                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
		                        @csrf
                                <button type="submit"><i class="fas fa-sign-out-alt"></i></button>
		                    </form>
						</div>


					</div>




				</div>







			</div>


		</section>


	@endif
@endsection






@section('footer')


@endsection
