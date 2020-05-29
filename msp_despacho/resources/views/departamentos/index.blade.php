@include('base.base')
@include('base.emergente')
@yield('header_data')


@if(Auth::check())

    @yield('nav')
	<section class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Users</h6>
			</div>
 

			@if(Auth::user()->role_id == 1)
				<div class="contenedor_usuarios_count">
					@include('base.flash_message')

					<div class="grid-1">

						<div class="col-6_md-6_sm-12_xs-12">
							<div class="contenedor_datos contenedor_datos_departamentos ultimas_actividades">
								<h3>Agregar nuevo departamento</h3>

								<form action="{{ route('Departamentos.Store') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
									@csrf
									<div class="form-group">
									    <label for="exampleInputEmail1">Nombre del departamento*</label>
									   	<input type="text" name="nombre" class="form-control"  placeholder="Nombre del departamento">

									</div>

									<div class="form-group">
									    <label for="exampleInputEmail1">Encargado de departamento*</label>
									   	@php $users = DB::table('users')->where('role_id', '2')->get()->all(); @endphp
									   	<select name="encargado" class="custom-select">
											<option value="" selected>Seleccione la persona encargada</option>
											@foreach($users as $value)
											  	<option value="{{$value->name}} {{$value->lastname}}">{{$value->name}} {{$value->lastname}}</option>
											@endforeach
										</select>
									</div>
									<input type="hidden" name="register_id" value="{{Auth::user()->id}}">
									<!-- id="registrar_user" -->

									<button type="submit" class="btn btn-primary boton_completo">
										Registrar Departamento
									</button>
								</form>

							</div>
						</div>



						<div class="col-6_md-6_sm-12_xs-12">
							<div class="contenedor_datos contenedor_datos_departamentos ultimas_actividades">
								<h3>Asignar encargado a un departamento</h3>

								<form action="{{ route('Departamentos.Update') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
									@csrf


									    <div class="form-group">
									      	<label>Seleccione Departamento*</label>
									      	@php $users = DB::table('departamentos')->get()->all(); @endphp
									    	<select name="id" class="custom-select">
											  <option value="" selected>Seleccione Departamento</option>
											  @foreach($users as $value)
											  	<option value="{{$value->id}}">{{$value->display_name}}</option>
											  @endforeach
											</select>
									    </div>



									    <div class="form-group">
									      	<label for="exampleInputEmail1">Encargado de departamento*</label>
					@php $users = DB::table('users')->where(['role_id' => '2', 'status' => '0'])->get()->all(); @endphp
									    	<select name="encargado" class="custom-select">
											  <option value="" selected>Seleccione la persona encargada</option>
											  @foreach($users as $value)
											  	<option value="{{$value->name}} {{$value->lastname}}">{{$value->name}} {{$value->lastname}}</option>
											  @endforeach
											</select>
									    </div>


									<input type="hidden" name="register_id" value="{{Auth::user()->id}}">
									<!-- id="registrar_user" -->

									<button type="submit" class="btn btn-primary boton_completo">
										Asignar Encargado
									</button>


								</form>

							</div>
						</div>


					</div>
				</div>
			@endif













			<div class="contenedor_tabla departamentos_table">
				<div class="grid-1 titulo_tablas">
					<div class="col-9_sm-6_xs-12">
						<h3>Lista de departamentos registrados</h3>
					</div>
				</div>



				<table id="dataTables" class="table table-striped dt-responsive nowrap table-bordered" style="width:100%">
					<thead class="cabezera_tables">
					    <tr>
					        <!-- <th>Imagen</th> -->
					        <th>Nombre Departamento</th>
					        <th>Encargado</th>
					        <th>Estado</th>
					        <th>Opciones</th>
					    </tr>
					</thead>


					@php $departamentos = DB::table('departamentos')->get()->all(); @endphp

					<tbody class="Contenido_tables">
						@foreach($departamentos as $value)
						    <tr class="data_tables_row">

						        <td><h5>{{$value->display_name}}</h5></td>


						        @if( $value->encargado != '-')
						        	@php
							        	$encargado_list = DB::table('users')->where('id', $value->encargado)->get()->all();
							        @endphp
							        @foreach($encargado_list as $value_encargado)
							        	<td><h5>{{$value_encargado->name}} {{$value_encargado->lastname}}</h5></td>
							        @endforeach

						        @else
						        	<td><h5>No existe usuario</h5></td>
						        @endif



						        


						        @if($value->status === 0)
						        	<td><h5>Activo</h5></td>
						        @else
				 		        	<td><h5>Inactivo</h5></td>
						        @endif


						        <td>
							       	<span class="dropdown boton_more_table">
	                                    <button id="btnSearchDrop2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right ">
		                                 MORE
	                                </button>

	                                <span aria-labelledby="btnSearchDrop2" class="dropdown-menu mt-1 dropdown-menu-right option_drown">

	                                	@if(Auth::user()->role_id == 1)


	                                		@if($value->encargado != '-')

													<a href="/departamentos/desvincular/{{$value->id}}/{{$value->encargado}}" class="dropdown-item" onclick="return confirm('¿Deseas desvincular este usuario del departamento?');">
														<i class="fas fa-trash"></i> Desvincular
													</a>

											@endif



											<a href="/departamentos/delete/{{$value->id}}" class="dropdown-item" onclick="return confirm('¿Deseas borrar este departamento?');">
												<i class="fas fa-trash"></i> Delete
											</a>




									        

													

		                                    <a href="/departamentos/status/{{$value->id}}/{{$value->status}}" class="dropdown-item">
		                                       	<i class="fas fa-adjust"></i>
		                                        @switch($value->status)
		                                             @case('0')
		                                                Inactive
		                                            @break

		                                            @case('1')
		                                                Active
		                                            @break
		                                        @endswitch
		                                    </a>
	                                    @endif
	                                </span>
							    </td>

						    </tr>
					    @endforeach
					</tbody>


					<tfoot class="cabezera_tables">
					    <tr>
					        <!-- <th>Imagen</th> -->
					        <th>Nombre Departamento</th>
					        <th>Encargado</th>
					        <th>Estado</th>
					        <th>Opciones</th>
					    </tr>
					</tfoot>
				</table>



			</div>

			</div>

		</div>
	</section>




@else
    <script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif








@yield('footer_data')
