@include('base.base')
@yield('header_data')

 
@if(Auth::check())

    @yield('nav')
	<div class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Documentos</h6>
			</div>


					<div class="contenedor_usuarios_count">
						@include('base.flash_message')

						<div class="grid-1">

							<div class="col-12_md-12_sm-12_xs-12">
								<div class="contenedor_datos ultimas_actividades">
									<h3>Registrar nuevo documento</h3>

									<form action="{{ route('Documentos.Update') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
										@csrf


                                            <div class="row">
                                                <div class="col-md-6 mb-12">
                                                    <label for="exampleInputEmail1">Nombre del documento*</label>
                                                    <input type="text" name="nombre" class="form-control"  placeholder="documento" value="{{$data->name}}">
                                                </div>

                                                <div class="col-md-6 mb-12">
                                                    <label for="exampleInputEmail1">Tipo de documento*</label>
                                                    <select name="tipo" class="custom-select">
                                                        <option selected value="{{$data->tipo}}">{{$data->tipo}}</option>}
                                                        <option value="Invitación">Invitación</option>
                                                        <option value="Formularios">Formularios</option>
                                                        <option value="Documento">Documento</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-12 mb-12">
                                                    <label for="exampleInputEmail1">Enviar al usuario (ctrl para elegir más de uno)*</label>
                                                        @php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
                                                <select multiple name="send[]" class="custom-select">
                                                    @foreach($users as $value)
                                                        <option value="{{$value->email}}">
                                                            {{$value->name}} {{$value->lastname}}, 
                                                            {{$value->department}}, {{$value->cargo}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-12">
                                                    <label for="exampleInputEmail1">Nombre de la empresa</label>
                                                    <input type="text" name="empresa" class="form-control"  placeholder="Nombre del departamento" value="{{$data->empresa}}">

                                                </div>

                                                <div class="col-md-6 mb-12">
                                                    <label for="exampleInputEmail1">Entregado por</label>
                                                    <input type="text" name="entregado" class="form-control"  placeholder="Nombre del departamento" value="{{$data->entregado}}">
                                                </div>
                                            </div>

                                            
                                            <input type="hidden" name="id" value="{{$data->id}}">
                                            <input type="hidden" name="register_id" value="{{Auth::user()->id}}">
                                            <!-- id="registrar_user" -->

                                            <button type="submit" class="btn btn-primary boton_completo">
                                                Registrar Departamento
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