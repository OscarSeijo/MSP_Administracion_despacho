@include('base.base')
@include('base.emergente')
@yield('header_data')
@yield('nav')

@if(Auth::check())

	@yield('emergente')



	<section class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_inicio">
				<h2>Bienvenida {{ Auth::user()->name }}</h2>
				<h5>Al sistema administrativo del Ministerio Salud Pública</h5>


				


				<div class="contenedor_opciones">
					<div class="opciones">
						<div class="grid-1-center">


                            @php
                                // Primero llamamos los componentes del menu:
                                $menu = DB::table('menu_items')->orderBy('order', 'ASC')->get()->all();
                                // Verificamos el role del usuario en la tabla de "persmissions roles"
                                $roles = DB::table('permission_roles')->where('role_id', Auth::user()->role_id)->get();
                            @endphp


                            @foreach($roles as $value_roles)
                                @foreach($menu as $value_menu)
                                    @if($value_roles->menu_id == $value_menu->id)
                                        @if($value_menu->status == 0)
                                            @if($value_menu->id !== 1)
                                            	@if($value_menu->id == 1)
                                            	@else
                                                <div class="col-3_lg-3_md-4_sm-6_xs-12">
                                                    <a href="{{route($value_menu->route)}}" title="">
                                                        <div class="opcion_dashboard">
                                                            <div class="imagen">
                                                                <img src="{{secure_asset($value_menu->icon_home)}}" alt="">
                                                            </div>
                                                            <h6>{{$value_menu->titulo_home}}</h6>
                                                            <p>{{$value_menu->text_home}}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                                @endif
                                            @endif

                                        @endif
                                    @endif
                                @endforeach
                            @endforeach


						</div>
					</div>
				</div>

			</div>















			<div class="contenedor_bajo">



				<!--
				<div class="contenedor_show_recordatorios">
					<div class="grid-1 titulo_tablas">
						<div class="col-8_sm-6_xs-12 texto">
							<h3>Ultimos recordatorios agregados:</h3>
						</div>

						<div class="col-4_sm6_xs-12 boton_more">
							<a href="{{ route('Documentos.Index') }}"> <i class="far fa-file-word"></i> VER RECORDATORIOS</a>
						</div>
					</div>



					<div class="slick_carrousel_recordatorio"></div>
				</div> -->

				<div class="grid-1-noGutter">

					<div class="col-12 cont_table">

						<div class="grid-1 titulo_tablas">
							<div class="col-8_sm-6_xs-12 texto">
								<h3>Ultimos documentos registrados</h3>
							</div>

							<div class="col-4_sm6_xs-12 boton_more">
								<a href="{{ route('Documentos.Index') }}"> <i class="far fa-file-word"></i> VER TODOS LOS DOCUMENTOS</a>
							</div>
						</div>


						@include('base.flash_message')

					</div>


					@php

						$email = Auth::user()->email;
						$documentos = DB::table('documentos')
										->where('send', 'LIKE', '%'.$email.'%')
										->where('status', '0', )
										->orderBy('id', 'DESC')
										->take(10)
										->get()
										->all();
					@endphp



						@foreach($documentos as $key=>$value)
							@if($key == 1 || $key == 3 || $key == 5 || $key == 7 || $key == 9 )

								<div class="tableStyle number_impar">


									<div class="cuadros name_document">
										<h6>{{ \Illuminate\Support\Str::limit($value->name, 30, $end='...') }}</h6>
										<p>{{$value->tipo}} |

											@if( $value->situacion === 0)
												Externo
											@else
												Interno
											@endif
										</p>
									</div>

									<div class="linea"></div>

									<div class="cuadros time">
										<h6>
											<i class="far fa-calendar"></i>
											{{ Carbon\Carbon::parse($value->created_at)->format('d-M-Y') }}

											( {{ Carbon\Carbon::parse($value->created_at)->format('H:s') }} )
										</h6>


										<p>{{ Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</p>
									</div>

									<div class="linea"></div>

									@php $usuario = DB::table('users')->where([ "id" => $value->register_id,"status" => "0"])->take(1)->get()->all(); @endphp

									@foreach($usuario as $usuario)
										<div class="cuadros recibido">
											<div class="imagen">
												<img src="{{ secure_asset('/storage/')}}/{{$usuario->avatar}}" alt="">
											</div>

											<div class="info">
												<h6>{{$usuario->name}}</h6>
												<p>{{$usuario->department}}</p>
											</div>
										</div>
									@endforeach

									<div class="linea"></div>

									<div class="cuadros">
										<h6>Entregado por:</h6>
										<p>{{$value->entregado}}</p>
									</div>


									<div class="linea"></div>

									<div class="opciones">
										<a href="/documentos/download/{{$value->id}}" title="Descargar Documento">
											<i class="fas fa-file-download"></i>
										</a>
									</div>

									<div class="linea"></div>

									<div class="opciones">
										<button class="Open_event_created" value="{{$value->name}}" title="">
											<i class="far fa-calendar-times"></i>
										</button>
									</div>

									
								</div>







							@else







								<div class="tableStyle number_par">

									<!--
									<div class="checkbox">
										<input type="checkbox" name="opcion[]">
									</div>
								-->

									<div class="cuadros name_document">
										<h6>{{ \Illuminate\Support\Str::limit($value->name, 30, $end='...') }}</h6>
										<p>{{$value->tipo}} |

											@if( $value->situacion === 0)
												Externo
											@else
												Interno
											@endif
										</p>
									</div>

									<div class="linea"></div>

									<div class="cuadros time">
										<h6>
											<i class="far fa-calendar"></i>
											{{ Carbon\Carbon::parse($value->created_at)->format('d-M-Y') }}

											( {{ Carbon\Carbon::parse($value->created_at)->format('H:s') }} )
										</h6>


										<p>{{ Carbon\Carbon::parse($value->created_at)->diffForHumans() }}</p>
									</div>

									<div class="linea"></div>

									@php $usuario = DB::table('users')->where([ "id" => $value->register_id,"status" => "0"])->take(1)->get()->all(); @endphp

									@foreach($usuario as $usuario)
									<div class="cuadros recibido">
										<div class="imagen">
											<img src="{{ secure_asset('/storage/')}}/{{$usuario->avatar}}" alt="">
										</div>

										<div class="info">
											<h6>{{$usuario->name}}</h6>
											<p>{{$usuario->department}}</p>
										</div>
									</div>
									@endforeach

									<div class="linea"></div>

									<div class="cuadros">
										<h6>Entregado por:</h6>
										<p>{{$value->entregado}}</p>
									</div>


									<div class="linea"></div>

									<div class="opciones">
										<a href="/documentos/download/{{$value->id}}" title="">
											<i class="fas fa-file-download"></i>
										</a>
									</div>

									<div class="linea"></div>

									<div class="opciones">
										<button class="Open_event_created" value="{{$value->name}}" title="">
											<i class="far fa-calendar-times"></i>
										</button>
									</div>

									
								</div>



							@endif
						@endforeach

				<!-- CONTENEDOR GRID -->
				</div>
			<!-- contenedor_bajo -->
			</div>






		<!--  CIERRE CONTENEDOR SCROLL -->
		</div>
	</section>

@else
	<script>window.location.href = "http://google.com";</script>
@endif




@yield('footer_data')
