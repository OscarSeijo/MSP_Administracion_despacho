

					
						<table id="dataTables" class="table table-striped dt-responsive nowrap table-bordered" style="width:100%">
							<thead class="cabezera_tables">
							    <tr>
							        <!-- <th>Imagen</th> -->
							        <th>Nombre Documento</th>
							        <th>Empresa / individuo</th>
							        <th>Entregador por</th>


							        <th>Opciones</th>
							    </tr>
							</thead>


							@php $data = DB::table('documentos')->get()->all(); @endphp

							<tbody class="Contenido_tables">
								@foreach($data as $value)
								    <tr class="data_tables_row">

								        <td><h5>{{$value->name}}</h5></td>
								        <td><h5>{{$value->empresa}}</h5></td>
								        <td><h5>{{$value->entregado}}</h5></td>

								        
								        <td>
								        	<span class="dropdown boton_more_table">
		                                        <button id="btnSearchDrop2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn btn-primary dropdown-toggle dropdown-menu-right ">
			                                        MORE 

		                                        </button>
		                                    	
		                                    	<span aria-labelledby="btnSearchDrop2" class="dropdown-menu mt-1 dropdown-menu-right option_drown">
		                                            <a  href="/documentos/edit/{{$value->id}}" class="dropdown-item">
		                                            	<i class="fas fa-edit"></i> Edit
		                                            </a>
													<a href="/documentos/delete/{{$value->id}}" class="dropdown-item">
														<i class="fas fa-trash"></i> Delete
													</a>

													<a href="/documentos/send/{{$value->id}}" class="dropdown-item">
														<i class="fas fa-envelope-open-text"></i> Enviar correo
													</a> 

		                                            <a href="/documentos/status/{{$value->id}}/{{$value->status}}" class="dropdown-item">
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

		                                            
		                                    </span>
								        </td>  




								    </tr>
							    @endforeach
							</tbody>

							        
							<tfoot class="cabezera_tables">
							    <tr>
							       <th>Nombre Documento</th>
							        <th>Empresa / individuo</th>
							        <th>Entregador por</th>
							        <th>Opciones</th>
							    </tr>
							</tfoot> 
						</table>






















<div class="col-12 cont_table">
						
						<div class="grid-1 titulo_tablas">
							<div class="col-9_sm-6_xs-12">
								<h5>Últimos 10 usuarios agregados</h5>
							</div>
							<div class="col-2_sm-6_xs-12">
								<a href="{{ route('Users.Index') }}" type="button" class="btn btn-primary">See All User</a>
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


					@php $users = DB::table('users')->orderBy( 'id', 'DESC')->take(10)->get()->all(); @endphp

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
						        @if($value->status === 0)
						        	<td><h5>Activo</h5></td>
						        @else
						        	<td><h5>Inactivo</h5></td>
						        @endif
						        
						        <td>Opciones</td>
						                
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







				'status' => '0', 






















<div class="contenedor_emergente">
		<div class="contenedor_info created_user">
			<div class="info_general">
				<h3>AÑADIR NUEVO USUARIO</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">


				<div class="contenedor_form">
					<form action="{{ route('Users.Store') }}" method="post" id="form_registrar_user" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf

						<div class="opciones">
							<h5>Seleccione el tipo de usuario que desea crear</h5>
							<div class="grid-1-center-noGutter">
								<div class="col-6">
									<h6 id="opcion_interno" class="activate interno opcion_change_register">INTERNO</h6>
								</div>

								<div id="opcion_externo" class="col-6 externo opcion_change_register">
									<h6>EXTERNO</h6>
								</div>
							</div>
						</div>


						<div class="row">
						    <div class="col">
						      <label for="exampleInputEmail1">Nombre*</label>
						    	<input type="text" class="form-control" name="name"  placeholder="Nombre">
						    </div>
						    <div class="col">
						      <label for="exampleInputEmail1">Apellidos*</label>
						    	<input type="text" class="form-control" name="lastname" placeholder="Apellidos">
						    </div>
						</div>


						<div class="row">
						    <div class="col">
						      <label for="exampleInputEmail1">Telefono*</label>
						    	<input type="phone" name="phone" class="form-control"  placeholder="Nombre">
						    </div>

						    <div class="col">
						      <label for="exampleInputEmail1">Departamento*</label>
						      	@php $departamentos = DB::table('departamentos')->get()->all(); @endphp
						    	<select name="department" class="custom-select">
								  <option selected>Seleccione Departamento</option>
								  @foreach($departamentos as $value)
								  	<option value="{{$value->display_name}}">{{$value->display_name}}, {{$value->encargado}}</option>
								  @endforeach
								</select>
						    </div>

						</div>


						<div class="form-group">
							<label>Cargo*</label>
							<input type="text" class="form-control" name="cargo" placeholder="Cargo que ejerce">
						</div>



						<div class="form-group">
						    <label for="exampleInputEmail1">Correo electrónico*</label>
						    <input type="email" class="form-control" name="email"  placeholder="user@...">
						</div>



				

						<div class="campos_externos">
							<div class="linea"></div>

							<div class="form-group">
							    <label>Empresa o Ministerio*</label>
							    <input type="text" class="form-control" name="company" placeholder="Escriba su area laboral">
							</div>



						</div>

						

						<div class="form-groud">
							<p>* indica un campo obligatorio</p>
						</div>



						<!--

						<div class="linea"></div>
						<div class="custom-control custom-radio custom-control-inline contenedor_check">
						  	<input id="s1" type="checkbox" class="switch" onclick="ShowPasswordInput()">
						  	<label>Crear contraseña Asignada</label>
						</div>


						<script>
							function ShowPasswordInput(){
								if (document.getElementById("s1").checked == true){
									alert('activo')
		    						document.getElementById('password_input_create').show();
								} else {
									alert('inactivo')
		    						document.getElementById('password_input_create').hide();
								}
							}
						</script>

						<div id="password_input_create" class="form-group password_new">
						    <label for="exampleInputEmail1">Password</label>
						    <input type="password" class="form-control" id="exampleInputEmail1" placeholder="Contraseña">
						    <small>Minimo 8 caracteres, un símbolo numerico y un caracter especial</small>
						</div> -->

						<div class="linea"></div>

						<div class="custom-control custom-radio custom-control-inline contenedor_check">
						  	<input id="s2" type="checkbox" class="switch">
						  	<label>Solicitar cambio de contraseña al inicial session</label>
						</div>

						<input type="hidden" name="register_id" value="{{Auth::user()->id}}">
						<!-- id="registrar_user" -->

						<button type="submit" id="registrar_user" class="btn btn-primary boton_completo">
							<div class="loading">
								<img src="{{asset('img/loading.gif')}}" alt="">
							</div>
							Submit
						</button>


					</form>
				</div>


				<div class="contenedor_respuesta"></div>
				
			</div>
			
		</div>
	</div>




































	<select name="hour" class="form-control" >

						    			<option value="12:00">12:00 am</option>
						    			<option value="12:30">12:30 am</option>

						    			<option value="1:00">1:00 am</option>
						    			<option value="1:00">1:30 am</option>

						    			<option value="2:00">2:00 am</option>
						    			<option value="2:00">2:30 am</option>

						    			<option value="3:00">3:00 am</option>
						    			<option value="3:00">3:30 am</option>

						    			<option value="4:00">4:00 am</option>
						    			<option value="4:00">4:30 am</option>

						    			<option value="5:00">5:00 am</option>
						    			<option value="5:00">5:30 am</option>

						    			<option value="6:00">6:00 am</option>
						    			<option value="6:00">6:30 am</option>

						    			<option value="7:00">7:00 am</option>
						    			<option value="7:30">7:30 am</option>

						    			<option value="8:00">8:00 am</option>
						    			<option value="8:30">8:30 am</option>

						    			<option value="9:00">9:00 am</option>
						    			<option value="9:30">9:30 am</option>

						    			<option value="10:00">10:00 am</option>
						    			<option value="10:30">10:30 am</option>

						    			<option value="11:00">11:00 am</option>
						    			<option value="11:30">11:30 am</option>

						    			<option value="12:00">12:00 pm</option>
						    			<option value="12:30">12:30 pm</option>

						    			<option value="13:00">13:00 pm</option>
						    			<option value="13:30">13:30 pm</option>

						    			<option value="14:00">14:00 pm</option>
						    			<option value="14:30">14:30 pm</option>

						    			<option value="15:00">15:00 pm</option>
						    			<option value="15:30">15:30 pm</option>

						    			<option value="16:00">16:00 pm</option>
						    			<option value="16:30">16:30 pm</option>

						    			<option value="17:00">17:00 pm</option>
						    			<option value="17:30">17:30 pm</option>

						    			<option value="18:00">18:00 pm</option>
						    			<option value="18:30">18:30 pm</option>

						    			<option value="19:00">19:00 pm</option>
						    			<option value="19:30">19:30 pm</option>

						    			<option value="20:00">20:00 pm</option>
						    			<option value="20:30">20:30 pm</option>

						    			<option value="21:00">21:00 pm</option>
						    			<option value="21:30">21:30 pm</option>

						    			<option value="22:00">22:00 pm</option>
						    			<option value="22:30">22:30 pm</option>

						    			<option value="23:00">23:00 pm</option>
						    			<option value="23:30">23:30 pm</option>


						    		</select>
						    		



















						    		<div class="contenedor_form">
					<form action="{{ route('Folder.Create') }}" method="post" id="form_create_folder" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf


					<div class="grid-1 contenedor_form_event_register">

						<div class="col-12_sm-12_xs-12">
							<div class="form-group">
						      <label for="exampleInputEmail1">Nombre de la carpeta*</label>
						    	<input type="text" class="form-control" name="title"  placeholder="Nombre">
						    </div>

						    <div class="form-group">
						      <label for="exampleInputEmail1">Seleccione los permisos*</label>
						    	<input type="text" class="form-control" name="location"  placeholder="Lugar del evento">
						    </div>

						    <div class="form-group">
						    	<label>Seleccione los usuarios que pueden acceder* (ctrl para seleccionar más de uno)</label>
						    	@php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
						    	<select multiple class="form-control users_select" name="usuarios_registrados[]" class="">
						    		@foreach($users as $value)
								      	<option value="{{$value->email}}" class="opcion_evento_create">
								      		<h6>{{$value->name}} {{$value->lastname}} - {{$value->email}}, {{$value->department}}</h6>
								      	</option>
							      	@endforeach
							    </select>
						    </div>



						    <div class="form-group">
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
						</div>







						<div class="col-6_sm-12_xs-12">
							<div class="form-group">
						     	<label for="exampleInputEmail1">Agrega un Comentario*</label>
						    	<textarea class="form-control" name="comentario" rows="3" placeholder="Agregar comentario..."></textarea>
						    </div>


						    <div class="confirmation_date grid-1-noGutter">
						    	<div class="col-12">
						    		<h6>Confirmar día y hora:</h6>
						    	</div>
						    	<div class="col-6_sm-12_xs-12">
						    		<input type="date" class="picker_custom" name="date" id="DateInitial">
						    	</div>

						    	<div class="col-6_sm-12_xs-12">

						    		<input type="time" name="hour" min="00:00" max="23:59" value="07:00" class="form-control"/>
						    		
						    	</div>
						    </div>
						</div>


						<div class="col-12">
							<div class="linea"></div>
							<div class="form-groud">
								<p>* indica un campo obligatorio</p>
							</div>

							<input type="hidden" name="register_calendario" value="{{Auth::user()->id}}">
							<!-- id="registrar_date" -->

							<button type="submit"  class="btn btn-primary boton_completo">
								<div class="loading">
									<img src="{{asset('img/loading.gif')}}" alt="">
								</div>
								REGISTRAR EVENTO
							</button>

						</div>


					</div>

						

					</form>
				</div>





































				@php
							$email = Auth::user()->email;
							$department = Auth::user()->deparment;
							$carpetas = DB::table('carpetas')
										->where('access', 'LIKE', '%'.$email.'%')
										->where('status', '0', )
										->orderBy('id', 'DESC')
										->get()
										->all();
						@endphp
						<div class="carpetas_name">

							@foreach($carpetas as $value)

								@php 
									$documentos_folder = DB::table('documentos')->where('carpeta_id', $value->id )->count();
								@endphp

								@if($value->department === Auth::user()->department)
									<button type="submit" class="carpeta">
										<i class="fas fa-folder"></i>
										<h6>{{$value->name}} ( {{ $documentos_folder }} )  </h6>
									</button>
								@elseif($value->department === 'All')
									<button type="submit" class="carpeta">
										<i class="fas fa-folder"></i>
										<h6>{{$value->name}} ( {{ $documentos_folder }} )  </h6>
									</button>
								@endif



							@endforeach




						</div>





































						@foreach($likes as $likes)
															@if($value->id === $likes->document_id)
																@if($likes->status === 0)
																	<a href="" title="">
																		<!-- <i class="fas fa-heart"></i> -->
																		<i class="far fa-heart"></i>
																	</a>
																@else
																	<a href="" title="">
																		<i class="fas fa-heart"></i> 
																	</a>
																@endif
															@else
																<a href="" title="">
																	<!-- <i class="fas fa-heart"></i> -->
																	<i class="far fa-heart"></i>
																</a>
															@endif
														@endforeach

















											




















											@if(Auth::user()->role_id === 1)
				<div class="contenedor_usuarios_count">
					@include('base.flash_message')

					<div class="grid-1">

						<div class="col-12_md-12_sm-12_xs-12">
							<div class="contenedor_datos ultimas_actividades">
								<h3>Registrar nuevo documento</h3>

								<form action="{{ route('Documentos.Store') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
									@csrf

									<div class="row">
										<div class="col-md-6 mb-12">
									    	<label for="exampleInputEmail1">Nombre del documento (asunto)*</label>
									   		<input type="text" name="nombre" class="form-control"  placeholder="documento">
										</div>

										<div class="col-md-6 mb-12">
									    	<label for="exampleInputEmail1">Tipo de documento*</label>
									   		<select name="tipo" class="custom-select">
											  	<option value="Invitación">Invitación</option>
											  	<option value="Formularios">Formularios</option>
											  	<option value="Documento">Documento</option>
											</select>
										</div>

										<div class="col-md-12 mb-12">
									    	<label for="exampleInputEmail1">Compartir documuento a los usuarios: (ctrl para elegir más de uno)*</label>
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
									    	<label for="exampleInputEmail1">Nombre de la institución*</label>
									   		<input type="text" name="empresa" class="form-control"  placeholder="Nombre de la institución">

										</div>

										<div class="col-md-6 mb-12">
									    	<label for="exampleInputEmail1">Entregado por*</label>
									   		<input type="text" name="entregado" class="form-control"  placeholder="Nombre del departamento">
										</div>
									</div>


									<div class="row">
										<div class="col-md-6 mb-12">
									    	<label>Subir documento (JPG, PNG y PDF)</label>
											<div class="custom-file">
										  		<input type="file" name="imagen" class="custom-file-input" lang="es">
										  		<label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
											</div>

										</div>



										<div class="col-md-6 mb-12">


											<label for="exampleInputEmail1">Guardar en la carpeta*</label>
									    	@php $carpetas = DB::table('carpetas')->where('status', 0)->get()->all(); @endphp
									    

										    <input list="brow_forder" class="form-control" name="carpeta">
											<datalist id="brow_forder">
												@foreach($carpetas as $value)
											      	<option value="{{$value->id}}" class="opcion_evento_create">
											      		<h6>{{$value->name}}</h6>
											      	</option>
										      	@endforeach
											</datalist>  

										</div>
									</div>


								
									
									
									<input type="hidden" name="register_id" value="{{Auth::user()->id}}">
									<!-- id="registrar_user" -->

									<button type="submit" class="btn btn-primary boton_completo">
										Registrar Documento
									</button>
								</form>
								
							</div>
						</div>



					</div>
				</div>
			@endif































@if( count($likes) > 0)
														<!-- EN CASO DE QUE HAYA DATA -->
															@foreach($likes as $like_values)

																<!-- EN CASO DE QUE EXISTA EL REGISTRO -->
																@if($like_values->document_id == $value->id)
																	@if($like_values->status === 0)
																		<a href="{{ route('Documentos.Likes', [ 
																						'id' => $like_values->id, 
																						'status' => $like_values->status,
																						'document_id' => $value->id
																					] )}} ">
																			<i class="fas fa-heart"></i>
																		</a>
																	@else

																		<a href="{{ route('Documentos.Likes', [ 
																					'id' => $like_values->id, 
																					'status' => $like_values->status,
																					'document_id' => $value->id
																				] )}} ">
																			<i class="far fa-heart"></i>
																		</a>

																	@endif

																<!-- EN CASO DE QUE NO EXISTA EL REGISTRO -->
																@else
																	<a href="{{ route('Documentos.Likes', [ 
																					'id' => $like_values->id, 
																					'status' => $like_values->status,
																					'document_id' => $value->id
																				] )}} ">
																		<i class="far fa-heart"></i>
																	</a>
																@endif
															@endforeach
														<!-- EN CASO DE QUE NO EXISTA DATA -->
														@else
															<a href="{{ route('Documentos.Likes', [ 
																				'id' => 0, 
																				'status' => 1,
																				'document_id' => $value->id
																			] )}} ">
																<i class="far fa-heart"></i>
															</a>
														@endif


