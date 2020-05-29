@include('base.base')
@yield('header_data')

 
@if(Auth::check())

    @yield('nav')
	<div class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Calendario</h6>
			</div>

				
					<div class="contenedor_usuarios_count">
						@include('base.flash_message')

						<div class="grid-1">

                            @foreach($evento as $data)

							<div class="col-12_md-12_sm-12_xs-12">
								<div class="contenedor_datos ultimas_actividades">
									<h3>Datos del evento</h3>

										@csrf

                                        <div class="grid-1">
                                            
                                            @if($data->type === 'evento')

                                                <div class="col-6_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Titulo Recordatorio</label>
                                                    <input type="text" class="form-control"  value="{{$data->title}}" disabled>
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Lugar del Evento</label>
                                                    <input type="text" class="form-control"  value="{{$data->locacion}}" disabled>
                                                </div>

                                                <div class="col-12_sm-12_xs-12">
                                                    <label>Usuarios registrados al evento</label>

                                                    <div class="grid-1-center">

                                                        @php $usuarios = explode(',', $data->usuarios_registrados); @endphp
                                                        @php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp

                                                        @foreach($usuarios as $key => $value)

                                                            @php $users = DB::table('users')->where('email', $value)->get()->all(); @endphp
                                                            @foreach($users as $key => $value_user)
                                                                <div class="col-6">
                                                                    <div class="info_user">
                                                                        <h5>{{$value_user->name}} {{$value_user->lastname}}</h5> 
                                                                        <h6><i class="fas fa-id-card"></i>{{$value_user->cedula}} / 
                                                                            <i class="far fa-envelope"></i>{{$value_user->email}}</h6>
                                                                        <h6><i class="far fa-building"></i> 
                                                                            {{$value_user->tipo}} /
                                                                            {{$value_user->department}} / 
                                                                            {{$value_user->cargo}}</h6>
                                                                    </div>
                                                                </div>

                                                            @endforeach

                                                        @endforeach
                                                    </div>
                                                    

                                                </div>


                                                


                                                @if($data->document === NULL)

                                                @else
                                                    <div class="col-12_sm-12_xs-12">
                                                        <label>Documento asignado al evento:</label>
                                                         <input list="brow" class="form-control" value="{{$data->document}}" disabled>
                                                    </div>
                                                @endif

                                            
                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Comentario</label>
                                                    <textarea class="form-control" name="comentario" rows="3" disabled placeholder="Agregar comentario..." value="">{{$data->comentario}}</textarea>
                                                 </div>
                                               



                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Día establecido:</label>
                                                    <input type="text" class="form-control" disabled value="{{$data->date}}" >
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Hora establecida:</label>
                                                   <input type="text"  disabled value="{{$data->hour}}" class="form-control"/>
                                                </div>




                                            @else

                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Titulo del Recordatorio</label>
                                                    <input type="text" class="form-control" name="title" disabled  placeholder="Titulo" value="{{$data->title}}">
                                                </div>


                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Comentario</label>
                                                    <textarea class="form-control" name="comentario" rows="3" disabled placeholder="Agregar comentario..." value="">{{$data->comentario}}</textarea>
                                                </div>


                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Día establecido:</label>
                                                    <input type="text" class="form-control" disabled value="{{$data->date}}" >
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Hora establecida:</label>
                                                   <input type="text"  disabled value="{{$data->hour}}" class="form-control"/>
                                                </div>


                                            @endif

                                           
                                        </div>
                                        

									
								</div>
							</div>

                            @endforeach

						</div>
					</div>
				

			
		</div>
	</div>

@else
    <script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif











@yield('footer_data')