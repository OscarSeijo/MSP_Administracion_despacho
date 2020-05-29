@section('emergente')

@if(Auth::check())

	<section class="contenedor_emergente">




		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ----------------------------- CREATE USER | CREATE USER | CREATE USER -------------------------------- -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->

 
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
						    	<input type="phone" name="phone" class="form-control phone_mask"  placeholder="Telefono">
						    </div>

						    <div class="col">
						      <label for="exampleInputEmail1">Departamento*</label>
						      	@php $departamentos = DB::table('departamentos')->get()->all(); @endphp
						    	<select name="department" class="custom-select">
								  	<option selected>Seleccione Departamento</option>

								  	@if( Auth::user()->role_id == 1 )
								  			@foreach($departamentos as $value)
								  				<option value="{{$value->display_name}}">{{$value->display_name}}</option>
											@endforeach

									@else
										<option value="{{Auth::user()->department}}">{{Auth::user()->department}}</option>
	                               	@endif

								 
								</select>
						    </div>

						</div>

 
                        <div class="row">
                            <div class="col">
                                <label>Cargo*</label>
                                <input type="text" class="form-control" name="cargo" placeholder="Cargo que ejerce">
                            </div>

                            <div class="col">
                                <label for="exampleInputEmail1">Role del usuario*</label>
                                @php $roles = DB::table('roles')->get()->all(); @endphp
	                            <select name="role_id" class="custom-select">
	                                <option selected>Seleccione el role</option>

	                                @if(Auth::user()->role_id === 1)
	                                	<option value="1">Administrador</option>
	                                @endif

	                                @foreach($roles as $value)
	                                	@if($value->id !== 1)
	                                		<option value="{{$value->id}}">{{$value->display_name}}</option>
	                                	@endif
	                                		

	                                @endforeach
	                            </select>


                               
                            </div>

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


						<div class="linea"></div>

                        <!--
						<div class="custom-control custom-radio custom-control-inline contenedor_check">
						  	<input id="s2" type="checkbox" class="switch">
						  	<label>Solicitar cambio de contraseña al inicial session</label>
						</div> -->

						<input type="hidden" name="register_id" value="{{Auth::user()->id}}">
						<!--  -->

						<button type="submit" id="registrar_user" class="btn btn-primary boton_completo" 
								onclick="return confirm('¿Confirma que todos los datos están bien?');">
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

















		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ----------------------------- CREATE EVENT | CREATE EVENT | CREATE EVENT ----------------------------- -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->

		<div class="contenedor_info add_event">
			<div class="info_general">
				<h3>AÑADIR NUEVO EVENTO</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">

				<div class="contenedor_form">
					<form action="{{ route('Calendar.Store') }}" method="post" id="form_crear_evento" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf


					<div class="grid-1 contenedor_form_event_register">

						<div class="col-6_sm-12_xs-12">
							<div class="form-group">
						      <label for="exampleInputEmail1">Titulo Evento*</label>
						    	<input type="text" class="form-control" name="title"  placeholder="Nombre">
						    </div>

						    <div class="form-group">
						      <label for="exampleInputEmail1">Lugar del Evento*</label>
						    	<input type="text" class="form-control" name="location"  placeholder="Lugar del evento">
						    </div>

						    <div class="form-group">
						    	<label>Seleccione los usuarios*</label>
						    	<small>(ctrl para seleccionar más de uno)</small>
						    	@php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
						    	<select multiple class="form-control users_select" name="usuarios_registrados[]" class="">
						    		@foreach($users as $value)

						    			@if($value->id == Auth::user()->id)

						    			@else
						    				@if($value->first_time == 0)

						    				@else
						    					<option value="{{$value->email}}" class="opcion_evento_create">
									      			<h6>{{$value->name}} {{$value->lastname}} - {{$value->department}} ({{$value->cargo}})</h6>
									      		</option>
						    				
								      		@endif
						    			@endif
								      	
							      	@endforeach
							    </select>
						    </div>



						    <div class="form-group">
							    <label>Seleccione el documento</label>
							    @php $documentos_list = DB::table('documentos')->where('status', 0)->get()->all(); @endphp
							    

								<input list="brow_documentos" class="form-control" id="documento_id">
								<datalist id="brow_documentos">
									@foreach($documentos_list as $value)
										<option value-documentos_event="{{$value->id}}" class="opcion_evento_create">
                                           <h6>{{$value->name}}</h6>
                                        </option>
									@endforeach
								</datalist>
								<input type="hidden" name="documento" id="documento_result">
							</div>


						   
						</div>



						<div class="col-6_sm-12_xs-12">
							<div class="form-group">
						     	<label for="exampleInputEmail1">Agrega un Comentario*</label>
						    	<textarea class="form-control" name="comentario" rows="3" placeholder="Agregar comentario..."></textarea>
						    </div>


						    <div class="confirmation_date grid-1">
						    	<div class="col-12">
						    		<label>Confirmar día y hora:</label>
						    	</div>
						    	<div class="col-6_sm-12_xs-12">
						    		<input type="date" class="form-control picker_custom date" name="date">
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


							<input type="hidden" name="type" value="evento">
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


				<div class="contenedor_respuesta"></div>

			</div>

		</div>









		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------- CREATE RECORDATORIO | CREATE RECORDATORIO | CREATE RECORDATORIO ------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->

		<div id="add_recordatorio" class="contenedor_info add_recordatorio">
			<div class="info_general">
				<h3>AÑADIR NUEVO RECORDATORIO</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">

				<div class="contenedor_form">
					<form action="{{ route('Calendar.Store') }}" method="post" id="form_crear_recordatorio" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf

						<div class="grid-1 contenedor_form_event_register">

							<div class="col-12_sm-12_xs-12">
								<div class="form-group">
							      	<label for="exampleInputEmail1">Titulo Recordatorio*</label>
							    	<input type="text" class="form-control" name="title"  placeholder="Nombre">
							    </div>
							</div>



							<div class="col-12_sm-12_xs-12">
								<div class="form-group">
							     	<label for="exampleInputEmail1">Agrega un Comentario*</label>
							    	<textarea class="form-control" name="comentario" rows="3" placeholder="Agregar comentario..."></textarea>
							    </div>
							</div>


							<div class="col-12_sm-12_xs-12">
								<label>Confirmar día y hora*:</label>

								<div class="grid-1">
									<div class="col-6_xs-12">
										<input type="date" class="form-control picker_custom date" name="date" >
									</div>

									<div class="col-6_xs-12">
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
								<input type="hidden" name="type" value="recordatorio">
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


				<div class="contenedor_respuesta"></div>

			</div>

		</div>












        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------ MODIFICAR PERFIL | MODIFICAR PERFIL | MODIFICAR PERFIL ---------------------- -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->


        <div id="changeProfile" class="contenedor_info changeProfile single_line">
            <div class="info_general">
                <h3>MODIFICAR DATOS DE PERFIL</h3>
                <div class="exit_button">
                    <i class="fas fa-times"></i>
                </div>
            </div>

            <div class="contenedor_scroll">


                <div class="contenedor_form">
                    <form action="{{ route('Users.Edit.Admin') }}" method="post" id="form_profile_edit" enctype="multipart/form-data" accept-charset="utf-8">
                        @csrf

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="name"  placeholder="Nombre" value="{{Auth::user()->name}}">
                        </div>

                        <div class="form-group">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Apellidos" value="{{Auth::user()->lastname}}">
                        </div>

                        <div class="form-group">
                            <label>Codigo de identidad (Cedula)</label>
                            <input type="text" class="form-control cedula_mask" name="cedula" placeholder="Codigo de identidad" value="{{Auth::user()->cedula}}">
                        </div>


                        <div class="row">
                            <div class="col">
                                <label for="exampleInputEmail1">Telefono</label>
                                <input type="phone" name="phone" class="form-control phone_mask"  placeholder="Telefono" value="{{Auth::user()->phone}}">
                            </div>

                            <div class="col">
                                <label for="exampleInputEmail1">Telefono Secundario</label>
                                <input type="phone" name="secundary_phone" class="form-control phone_mask"  placeholder="Telefono Secundario" value="{{Auth::user()->secundary_phone}}">
                            </div>
                        </div>




                        <div class="linea"></div>


                        <input type="hidden" name="register_id" value="{{Auth::user()->id}}">
                        <!-- id="registrar_user" -->

                        <button type="submit" id="Profile_edit_submit" class="btn btn-primary boton_completo">
                            <div class="loading">
                                <img src="{{asset('img/loading.gif')}}" alt="">
                            </div>
                            MODIFICAR DATOS
                        </button>


                    </form>
                </div>


            </div>

        </div>








        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- --------------------- MODIFICAR PASSWORD | MODIFICAR PASSWORD | MODIFICAR PASSWORD ------------------- -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------------------------------------------------ -->


        <div id="changePasswor_cont" class="contenedor_info changePasswor single_line">
            <div class="info_general">
                <h3>MODIFICAR CONTRASEÑA</h3>
                <div class="exit_button">
                    <i class="fas fa-times"></i>
                </div>
            </div>

            <div class="contenedor_scroll">


                <div class="contenedor_form">
                    <form action="{{ route('Users.Edit.Password') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
                        @csrf

                        <div class="form-group">
                            <label>Contraseña Actual:</label>
                            <input type="password" class="form-control" name="current_password"  placeholder="Contraseña actual" >
                        </div>


                        <div class="form-group">
                            <label>Nueva Contraseña:</label>
                            <input id="password_input" type="password" class="form-control" name="password" placeholder="Contraseña">
                        </div>


                        <div class="form-group">
                            <label>Confirmar Nueva Contraseña:</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirmar Contraseña">
                        </div>



                        <div class="linea"></div>

                        <input type="hidden" name="register_id" value="{{Auth::user()->id}}">
                        <!-- id="registrar_user" -->

                        <button type="submit" id="Password_edit_submit" class="btn btn-primary boton_completo">
                            <div class="loading">
                                <img src="{{asset('img/loading.gif')}}" alt="">
                            </div>
                            MODIFICAR DATOS
                        </button>


                    </form>
                </div>


            </div>

        </div>






































        <!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------- CREATE DOCUMENT | CREATE DOCUMENT | CREATE DOCUMENT ------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->


		<div id="add_file"  class="contenedor_info single_line">
			<div class="info_general">
				<h3>SUBIR DOCUMUENTO</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">

				<form action="{{ route('Documentos.Store') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
					@csrf
					<div class="grid-1 contenedor_add_folder">

						<div class="opciones">
							<h5>Seleccione el tipo de usuario que desea crear</h5>
							<div class="grid-1-center-noGutter">
								<div class="col-6">
									<h6 id="opcion_interno" class="activate upload opcion_change_document">Almacenar</h6>
								</div>

								<div id="opcion_externo" class="col-6 share opcion_change_document">
									<h6>Almacenar y compartir</h6>
								</div>
							</div>
						</div>




						<div class="col-12_sm-12_xs-12">



							<div class="row">
								<div class="col-md-12 mb-12">
									<label for="exampleInputEmail1">Nombre del documento (asunto)*</label>
									<input type="text" name="nombre" class="form-control"  placeholder="documento">
								</div>

								<div class="col-md-12 mb-12">
									<label>Tipo de documento*</label>
									<select name="tipo" class="custom-select">
										<option value="Invitación">Invitación</option>
										<option value="Formularios">Formularios</option>
										<option value="Documento">Documento</option>
									</select>
								</div>


							</div>




							<div class="row campos_externos">
								<div class="col-md-12 mb-12">
									<label>Compartir documuento a los usuarios: <br>(ctrl para elegir más de uno)*</label>
									@php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp

									<select multiple name="send[]" class="custom-select">
										@foreach($users as $value)
											@if($value->email !== Auth::user()->email)
												<option value="{{$value->email}}">
													{{$value->name}} {{$value->lastname}},
													{{$value->department}}, {{$value->cargo}}
												</option>
											@endif
										@endforeach
									</select>
								</div>


								<div class="row">
									<div class="col-md-12 mb-12">
										<label for="exampleInputEmail1">Nombre de la institución</label>
										<input type="text" name="empresa" class="form-control" placeholder="Nombre de la institución">
									</div>

									<div class="col-md-12 mb-12">
										<label for="exampleInputEmail1">Entregado por</label>
										<input type="text" name="entregado" class="form-control"  placeholder="Nombre del departamento">
									</div>
								</div>

							</div>





							<div class="row">
								<div class="col-md-12 mb-12">
									<label>Subir documento (jpg, png, pdf, docx y xlsx)</label>
									<div class="custom-file">
										<input type="file" name="imagen" class="custom-file-input" lang="es">
										<label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
									</div>
								</div>



								<div class="col-md-12 mb-12">

									<label for="exampleInputEmail1">Guardar en la carpeta*</label>
									@php $carpetas = DB::table('carpetas')->where('status', 0)->get()->all(); @endphp

									<input list="brow_forder" class="form-control" id="carpeta">
									<datalist id="brow_forder">
										@foreach($carpetas as $value)
											@if($value->tipo == 'Privado')
												@if($value->register_id == Auth::user()->id)
													<option data-folder="{{$value->id}}" class="opcion_evento_create">
														<h6>{{$value->name}} / Folder {{$value->tipo}}</h6>
													</option>
												@endif
											@else
												<option data-folder="{{$value->id}}" class="opcion_evento_create">
													<h6>{{$value->name}} / Folder {{$value->tipo}}</h6>
												</option>
											@endif
										@endforeach
									</datalist>
									<input type="hidden" name="carpeta" id="carpeta_data">

								</div>
							</div>




							<div class="col-12">
								<div class="linea"></div>
								<div class="form-groud">
									<p>* indica un campo obligatorio</p>
								</div>

								<input type="hidden" name="register_id" value="{{Auth::user()->id}}">

								<button type="submit"  class="btn btn-primary boton_completo">
									<div class="loading">
										<img src="{{asset('img/loading.gif')}}" alt="">
									</div>
									SUBIR DOCUMENTO
								</button>

							</div>

						</div>

					</div>
				</form>
			</div>
		<!-- CIERRE CONTENEDOR ADD FILE -->
		</div>





		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ---------------------------- CREATE FOLDER | CREATE FOLDER | CREATE FOLDER --------------------------- -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<div id="add_folder" class="contenedor_info single_line">
			<div class="info_general">
				<h3>CREAR NUEVA CARPETA</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">

				<form action="{{ route('Folder.Store') }}" method="post" id="form_folder_create" enctype="multipart/form-data" accept-charset="utf-8">

					@csrf

				<div class="grid-1 contenedor_add_folder">

					<div class="col-12_sm-12_xs-12">

						<div class="form-group">
						    <label for="exampleInputEmail1">Nombre de la carpeta*</label>
						    <input type="text" class="form-control" name="folder_name"  placeholder="Nombre">
						</div>

						<div class="form-group" >
						  	<label for="exampleInputEmail1">Seleccione el tipo de carpeta que deseas crear*</label>
						  	<select name="tipo_carpeta" class="form-control" id="select_tipo">
						  		<option value="" selected >Selecciona el tipo de carpeta</option>
						  		<option value="Publico" default>Publico</option>
						  		<option value="Privado">Privado</option>
						  	</select>
						</div>


						<div class="form-group" id="select_user_folder">
						   	<label>Seleccione los usuarios que pueden acceder, en caso de hacerlo público para todos, dejarlo vacio.</label>
						   	<small>(ctrl para seleccionar más de uno)</small>
						    @php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
						    <select multiple class="form-control users_select" name="access[]" class="">
						    	@foreach($users as $value)
						    		@if($value->email !== Auth::user()->email)
								    <option value="{{$value->email}}" class="opcion_evento_create">
								      	<h6>{{$value->name}} {{$value->lastname}} - {{$value->email}}, {{$value->department}}</h6>
								    </option>
								    @endif
							    @endforeach
							 </select>
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
								CREAR FOLDER
							</button>

						</div>

					</div>
				</form>

			</div>

		</div>




		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ---------------------------- MOVE DOCUMENT | MOVE DOCUMENT | MOVE DOCUMENT --------------------------- -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<!-- ------------------------------------------------------------------------------------------------------ -->
		<div id="move_document" class="contenedor_info single_line">
			<div class="info_general">
				<h3>MOVER DOCUMENTO</h3>
				<div class="exit_button">
					<i class="fas fa-times"></i>
				</div>
			</div>

			<div class="contenedor_scroll">

				<form action="{{ route('Folder.Move') }}" method="post" enctype="multipart/form-data" accept-charset="utf-8">

					@csrf

				<div class="grid-1 contenedor_add_folder">

					<div class="col-12_sm-12_xs-12">

						<div class="form-group">
						    <label for="exampleInputEmail1">Mover documento a la carpeta*</label>
						    @php $carpetas = DB::table('carpetas')->where('status', 0)->get()->all(); @endphp

							<input list="brow_forder" class="form-control" name="carpeta">
							<datalist id="brow_forder">
								@foreach($carpetas as $value)
									<option value="{{$value->name}}" class="opcion_evento_create">
										<h6>{{$value->name}}</h6>
									</option>
								@endforeach
							</datalist>
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
								MOVER DOCUMENTO
							</button>

						</div>

					</div>
				</form>

			</div>

		</div>



















    </section>

@else
    <script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif
@endsection
