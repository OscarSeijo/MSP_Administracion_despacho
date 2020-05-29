@include('base.base')
@include('base.emergente')
@yield('header_data')

 
@if(Auth::check())
@yield('emergente')
@yield('nav')



	<section class="contenedor_dashboard">


			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Calendario</h6>
			</div>

			<div id="open_menu_calendar_responsive" class="buton_menu_calendario">
				<i class="far fa-calendar-times"></i>
			</div>


			<div class="contenedor_calendar_dahsboard">
				<div class="contenedor_hours">
					<div class="contenedor_scroll">

						@include('base.flash_message')

						@if(Auth::user()->role_id == 1)
							<div class="contenedor_calendario_count">

								<div class="grid-1">
									<div class="col-3_md-3_sm-3_xs-6">
										<div class="contenedor_datos ultimas_actividades">
											<h4>{{$dataCount}}</h4>
											<h6>Total de eventos</h6>
										</div> 
									</div>

									<div class="col-3_md-3_sm-3_xs-6">
										<div class="contenedor_datos ultimas_actividades">
											<h4>{{$cancelEventsCount}}</h4>
											<h6>Eventos cancelados</h6>
											
										</div>
									</div>


									<div class="col-3_md-3_sm-3_xs-6">
										<div class="contenedor_datos ultimas_actividades">
											<h4>{{$successCount}}</h4>
											<h6>Eventos realizados</h6>
										</div>
									</div>

									<div class="col-3_md-3_sm-3_xs-6">
										<div class="contenedor_datos ultimas_actividades">
											<h4>{{$InactivosCount}}</h4>
											<h6>Eventos Inactivos</h6>
										</div>
									</div>

								</div>
							</div>
						@endif


						<div id="contenedor_resultados_data" class="contenedor_resultados_data">
							<div class="grid-1">
								<div class="col-8_sm-12_xs-12 contenedor_resultado_eventos">
                					<h5>Eventos registrados</h5>
                				</div>
							</div>
						</div>

					</div>
				</div>





				<div class="contenedor_opciones_calendar">
					<div class="calendar_pick">

						<div id="boton_close" class="boton_close">
							<button>Cerrar pestaña</button>
						</div>
						<div class="contenedor_fecha_picker">
							<!-- <h5> <b>Martes</b> , 12 Mayo 2020</h5> -->

						</div> 
						<form action="{{route('Calendar.Find.Date')}}" method="POST" id="busqueda_date" class="ws-validate">
						    <input type="date" class="picker_custom hide-replaced find_date" 
						    	name="date" id="" 
						    	data-date-inline-picker="true" 
						    	data-date-side-by-side = "true"
						    />
						    <small>Seleccione la fecha que desea consultar</small>
						</form>



					</div>

					<div class="calendar_upcoming">
						<h6>Eventos de este mes</h6>

						<div class="contendor_eventos_mes">
							

							@php
								$mes = date('M'); 
								$eventos_mes = DB::table('calendarios')->get()->all();
							@endphp

							@foreach($eventos_mes as $value)

								@if( \Carbon\Carbon::parse($value->date)->format('M') === $mes )
									<div class="evento">
                                        <div class="contenedor_dato">
                                            <div class="events_item">
                                                <span class="events_name">{{$value->title}}</span>
                                                <span class="events_date">
                                                	{{ \Carbon\Carbon::parse($value->date)->format('d M') }}
                                                	<b>({{$value->type}})</b>
                                                </span>
                                            </div>
                                            <span class="events_tag">{{$value->hour}}</span>
                                        </div>


                                        <div class="contenedor_option">
                                             <div class="opciones">
                                                <h6>Opciones:</h6>

                                                <a href="/calendar/show/{{$value->id}}">
                                                	<i class="far fa-eye"></i> 
                                            	</a>

                                                @if($value->status == 0)
                                                	<a href="/calendar/status/{{$value->id}}/{{$value->status}}"><i class="fas fa-toggle-off"></i></a>
                                                @else
                                                	<a href="/calendar/status/{{$value->id}}/{{$value->status}}"><i class="fas fa-toggle-on"></i></a>
                                                @endif

                                                <a href="/calendar/edit/{{$value->id}}"><i class="fas fa-edit"></i></a>


                                                <a href="/calendar/delete/{{$value->id}}" onclick="return confirm('¿Desea borrarlo?');"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        </div>

                                       
                                    </div>
								@endif
								
								
							@endforeach



						</div>
					</div>


					<div class="calendar_options">
						<button id="Open_event_recordatorio" class="Open_event_recordatorio">
							<i class="fas fa-plus-circle"></i>
							RECORDATORIO
						</button>
						<button class="Open_event_created">
							<i class="fas fa-plus-circle"></i>
							 EVENTO
						</button>
					</div>
				</div>
			</div>





	</section>





@else
	<script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif


@yield('footer_data')