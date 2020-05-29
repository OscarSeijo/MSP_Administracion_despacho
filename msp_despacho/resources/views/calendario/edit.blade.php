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

							<div class="col-12_md-12_sm-12_xs-12">
								<div class="contenedor_datos ultimas_actividades">
									<h3>Editar</h3>

									<form action="{{ route('Calendar.Update') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
										@csrf

                                        <div class="grid-1">
                                            
                                            @if($data->type === 'evento')

                                                <div class="col-6_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Titulo Recordatorio*</label>
                                                    <input type="text" class="form-control" name="title"  placeholder="Titulo" value="{{$data->title}}">
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Lugar del Evento*</label>
                                                    <input type="text" class="form-control" name="location"  placeholder="Locación" value="{{$data->location}}">
                                                </div>

                                                <div class="col-12_sm-12_xs-12">
                                                    <label>Seleccione los usuarios*</label>
                                                    <small>(ctrl para seleccionar más de uno)</small>
                                                    @php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
                                                    <select multiple class="form-control users_select" name="usuarios_registrados[]" class="">
                                                        @foreach($users as $value)

                                                            @if($value->id === Auth::user()->id)

                                                            @else
                                                                <option value="{{$value->email}}" class="opcion_evento_create">
                                                                    <h6>{{$value->name}} {{$value->lastname}} - {{$value->email}}, {{$value->department}}</h6>
                                                                </option>
                                                            @endif
                                                            
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-12_sm-12_xs-12">
                                                    <label>Seleccione el documento</label>
                                                    @php $documentos = DB::table('documentos')->where('status', 0)->get()->all(); @endphp


                                                    <input list="brow" class="form-control" name="documento">
                                                    <datalist id="brow">
                                                        <div id="documento_activo" class="documento_activo"></div>
                                                        @foreach($documentos as $value)
                                                            <option value="{{$value->name}}" class="opcion_evento_create">
                                                                <h6>{{$value->name}}</h6>
                                                            </option>
                                                        @endforeach
                                                    </datalist>
                                                </div>

                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Agrega un Comentario*</label>
                                                    <textarea class="form-control" name="comentario" rows="3" placeholder="Agregar comentario..." value="">{{$data->comentario}}</textarea>
                                                </div>



                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Confirmar día*:</label>
                                                    <input type="date" class="form-control picker_custom date" name="date" value="{{$data->date}}" >
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Confirmar hora*:</label>
                                                   <input type="time" name="hour" min="00:00" max="23:59" value="{{$data->hour}}" class="form-control"/>
                                                </div>

                                                <input type="hidden" name="type" value="evento">
                                                <input type="hidden" name="id" value="{{$data->id}}">

                                            @else

                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Titulo Recordatorio*</label>
                                                    <input type="text" class="form-control" name="title"  placeholder="Titulo" value="{{$data->title}}">
                                                </div>


                                                <div class="col-12_sm-12_xs-12">
                                                    <label for="exampleInputEmail1">Agrega un Comentario*</label>
                                                    <textarea class="form-control" name="comentario" rows="3" placeholder="Agregar comentario..." value="">{{$data->comentario}}</textarea>
                                                </div>


                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Confirmar día*:</label>
                                                    <input type="date" class="form-control picker_custom date" name="date" value="{{$data->date}}" >
                                                </div>

                                                <div class="col-6_sm-12_xs-12">
                                                    <label>Confirmar hora*:</label>
                                                   <input type="time" name="hour" min="00:00" max="23:59" value="{{$data->hour}}" class="form-control"/>
                                                </div>

                                                <input type="hidden" name="type" value="recordatorio">
                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                <input type="hidden" name="id_confirmation" value="{{$data->id_confirmation}}">

                                            @endif

                                           



                                        </div>
                                        

                                            
                                            <button type="submit" class="btn btn-primary boton_completo">
                                                Actualizar Datos
                                            </button>

									</form>
									
								</div>
							</div>



						</div>
					</div>
				


			
		</div>
	</div>

@else
    <script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif











@yield('footer_data')