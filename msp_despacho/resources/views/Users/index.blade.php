@include('base.base')
@include('base.emergente')
@yield('header_data')


@if(Auth::check())


@yield('emergente')
@yield('nav')
 
	<div class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Users</h6>
			</div>

			@include('base.flash_message')
			<div class="contenedor_usuarios_count">
				<div class="grid-1">


					@if(Auth::user()->role_id == 1)

	                    @php
			                 $users_all = DB::table('users')->count();
			                 $user_inactive = DB::table('users')->where('status', 1 )->count();
			                 $users_internos = DB::table('users')->where('tipo', 'interno' )->count();
			                 $users_externos = DB::table('users')->where('tipo', 'externo')->count();
			            @endphp
						<div class="col-12_sm-12">
							<div class="grid-1">
								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Cantidad de usuarios</h5>
										<h1>{{ $users_all }}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios inactivos</h5>
										<h1>{{$user_inactive}}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios internos</h5>
										<h1>{{$users_internos}}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios externos</h5>
										<h1>{{$users_externos}}</h1>
									</div>
								</div>
							</div>

						</div>

					@else


						@php
			                 $users_all = DB::table('users')->where('department', $Auth::user()->department)->count();
			                 $user_inactive = DB::table('users')->where(['status' => 1, 'department' => $Auth::user()->department ])->count();
			                 $users_internos = DB::table('users')->where(['tipo' => 'interno', 'department' => $Auth::user()->department])->count();
			                 $users_externos = DB::table('users')->where(['tipo' => 'externo', 'department' => $Auth::user()->department])->count();
			            @endphp
						<div class="col-12_sm-12">
							<div class="grid-1">
								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Cantidad de usuarios</h5>
										<h1>{{ $users_all }}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios inactivos</h5>
										<h1>{{$user_inactive}}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios internos</h5>
										<h1>{{$users_internos}}</h1>
									</div>
								</div>


								<div class="col-3_md-3_sm-6_xs-6">
									<div class="contenedor_datos">
										<h5>Usuarios externos</h5>
										<h1>{{$users_externos}}</h1>
									</div>
								</div>
							</div>

						</div>


					@endif



					<!--
					<div class="col-6_md-6_sm-12_xs-12">
						<div class="contenedor_datos ultimas_actividades">
							<h3>Ultimas actividades</h3>

							<div class="scroll">

								<div class="info_activities">
									<div class="grid-1">


									
										@php
	                                        $notifications = DB::table('notifications')
	                                        ->where('role_id', Auth::user()->role_id )
	                                        ->where('channel', 'user')
	                                        ->orderBy('id', 'DESC')
	                                        ->take(10)
	                                        ->get()->all();
	                                    @endphp

	                                    @foreach($notifications as $value)

											<div class="col-12">
												<h5>{{$value->title}}</h5>
												<h6>{{$value->text}}</h6>
											</div>

										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div> -->

				</div>
			</div>





			<div class="contenedor_tabla">
				<div class="grid-1 titulo_tablas">
					<div class="col-9_sm-6_xs-12">
						<h3>Lista de usuarios</h3>
					</div>
					<div class="col-2_sm-6_xs-12">

						@php
							$encargado = DB::table('departamentos')->where('encargado', Auth::user()->id)->get()->all();
						@endphp

						@if($encargado || Auth::user()->role_id == 1)
							<button class="btn btn-primary open_register_form">Create New User</button>
						@endif
					</div>
				</div>



				<table id="dataTables" class="table table-striped dt-responsive nowrap table-bordered" style="width:100%">
					<thead class="cabezera_tables">
					    <tr>
					        <!-- <th>Imagen</th> -->
					        <th>Información base</th>
					        <th>Correo</th>
					        <th>Role</th>
					        <th>Departamento</th>
					        <th>Estado</th>
					        <th>Opciones</th>
					    </tr>
					</thead>


					@php $users = DB::table('users')->get()->all(); @endphp

					<tbody class="Contenido_tables">
						@foreach($users as $value)
						    <tr class="data_tables_row">

						    	<!--
						        <td class="data_table_colum">
						         	<div class="avatar">
						          		<img src="" alt="">
						         	</div>
						        </td> -->

						        <td>
						            <h5>{{$value->name}} {{$value->lastname}}</h5>
						            <p>{{$value->cedula}}</p>
						        </td>

						        <td><h5>{{$value->email}}</h5></td>
						        <td><h5>{{$value->tipo}}</h5></td>
						        <td><h5>{{$value->department}}</h5></td>
						        @if($value->status == 0)
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


		                                   		

 
												@if($value->first_time == 0)
													<a href="/user/verificationEmail/{{$value->id}}" class="dropdown-item">
														<i class="fas fa-user-circle"></i> Enviar correo
													</a>
												@else
													<a href="/users/profile/{{$value->id}}" class="dropdown-item">
														<i class="fas fa-user-circle"></i> Ver Profile
													</a>
												@endif

		                                   		@if($encargado || Auth::user()->role_id == 1)
		                                   			<a href="/users/delete/{{$value->id}}" class="dropdown-item" onclick="return confirm('¿Deseas borrar este usuario?');">
														<i class="fas fa-trash"></i> Delete
													</a>

													<a href="/users/status/{{$value->id}}/{{$value->status}}" class="dropdown-item">
		                                            	<i class="fas fa-adjust"></i>
		                                                @switch($value->status)
		                                                    @case(0)
		                                                        Inactive
		                                                    @break

		                                                    @case(1)
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
					        <th>Información base</th>
					        <th>Correo</th>
					        <th>Role</th>
					        <th>Departamento</th>
					        <th>Estado</th>
					        <th>Opciones</th>
					    </tr>
					</tfoot>
				</table>



			</div>

			</div>

		</div>
	</div>





@else
<script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif






@yield('footer_data')
