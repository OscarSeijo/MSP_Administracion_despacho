@include('base.base')

@yield('header_data')


<section class="contenedor_primera_vez">
	<div class="contenedor_centro">





 

		<div id="bienvenida_site" class="contenedor_inicio shadowNormal bienvenida_site">

			<div class="contenedor_scroll">

				<div class="contenedor_imagen">
					<div class="imagen">
						<img src="{{asset('img/icon/rocket.svg')}}" alt="">
					</div>
				</div>

				<div class="contenedor_texto">
					<h4>¡Bienvenido a la plataforma!</h4>
					<h1>{{ Auth::user()->name }}</h1>
					<h6>Gracias por formar parte de nuestro sistema administrativo. Para poder acceder y utilizar las herramientas asignadas, es necesario terminar el proceso de registro, ¿Empezamos?</h6>
					<button class="Button_submit" id="next_slide_registration">Continuar</button>
				</div>

			</div>
		</div>







		<div id="contenedor_formulario" class="contenedor_inicio shadowNormal contenedor_formulario">
			<div class="contenedor_scroll">

				<div class="contenedor_texto">
					<h4>¡Por favor llenar los siguientes campos para cerrar el proceso de registro!</h4>

					<form action="{{ route('Login.RegistroFinal') }}" id="formulario_cierre_registro_final" method="post" enctype="multipart/form-data" accept-charset="utf-8">
						@csrf

						<div class="grid-1-center">

							<div class="col-12">
								<h6>Información basica:</h6>
							</div>

							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Nombre</label>
								    <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{Auth::user()->name}}">
								  </div>
							</div>

							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Apellido</label>
								    <input type="text" class="form-control" name="lastname" placeholder="Apellidos" value="{{Auth::user()->lastname}}">
								  </div>
							</div>


							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Cedula</label>
								    <input type="text" class="form-control cedula_mask" name="cedula" placeholder="Cedula">
								  </div>
							</div>


							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Telefono</label>
								    <input type="text" class="form-control phone_mask" name="phone" placeholder="Telefono" value="{{Auth::user()->phone}}" >
								  </div>
							</div>

							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Telefono secundario</label>
								    <input type="text" class="form-control phone_mask" name="secundary_phone" placeholder="Telefono">
								  </div>
							</div>


							<div class="col-4_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Correo electrónico</label>
								    <input type="text" class="form-control" placeholder="correo electrónico" value="{{Auth::user()->email}}" readonly>
								  </div>
							</div>





							<div class="linea"></div>


							<div class="col-12">
								<h6>Nueva contraseña:</h6>
							</div>

							<div class="col-6_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Contraseña</label>
								    <input id="password_input" type="password" class="form-control" name="password" placeholder="Contraseña">
								  </div>
							</div>

							<div class="col-6_sm-12_xs-12">
								 <div class="form-group">
								 	<label>Confirmar Contraseña</label>
								    <input type="password" class="form-control"  id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña">
								  </div>
							</div>

							<div class="col-12">
                                    <small>
                                    	<b class="min_text">Minimo 8 caracteres</b>, 
                                    	<b class="mayu_text">1 letra en mayuscula</b>, 
                                    	<b class="number_text">1 numero</b> y 
                                    	<b class="caracter_text">1 caracter especial</b>
                                    </small>
                            </div>



							<div class="linea"></div>


							<div class="col-12">
								<h6>Datos laboral:</h6>
							</div>

							@if(Auth::user()->tipo === 'externo')

								<div class="col-6_sm-12_xs-12">
									 <div class="form-group">
									 	<label>Role Asignado</label>
									    <input type="text" class="form-control" placeholder="Role Asignado" value="{{Auth::user()->tipo}}" readonly>
									  </div>
								</div>

								<div class="col-6_sm-12_xs-12">
									 <div class="form-group">
									 	<label>Institución</label>
									    <input type="text" class="form-control" placeholder="Institución" value="{{Auth::user()->institute}}" readonly>
									  </div>
								</div>


							@else

								<div class="col-4_sm-12_xs-12">
									 <div class="form-group">
									 	<label>Role Asignado</label>
									    <input type="text" class="form-control" placeholder="Role Asignado" value="{{Auth::user()->tipo}}" readonly>
									  </div>
								</div>

								<div class="col-4_sm-12_xs-12">
									 <div class="form-group">
									 	<label>Departamento</label>
									    <input type="text" class="form-control" placeholder="Departamento" value="{{Auth::user()->department}}" readonly>
									  </div>
								</div>


								<div class="col-4_sm-12_xs-12">
									 <div class="form-group">
									 	<label>Puesto laboral</label>
									    <input type="text" class="form-control" placeholder="Puesto Laboral" value="{{Auth::user()->cargo}}" readonly>
									  </div>
								</div>

							@endif



							<div class="linea"></div>

							<div class="col-12">
								<label>Foto para perfil</label>
								<div class="custom-file">
								  <input type="file" class="custom-file-input" name="imagen" id="customFile">
								  <label class="custom-file-label" for="customFile">Seleccione foto de perfil</label>
								</div>
							</div>


						</div>

						<div class="alert alert-danger alert-block" style="display:none">
							<button type="button" class="close">×</button>
							<div class="error_respuesta"></div>
						</div>

						<button type="submit" class="Button_submit" id="boton_formulario_cierre_registro_final">
							<div class="loading">
								<img src="{{asset('img/loading.gif')}}" alt="">
							</div>
							Terminar proceso de registro
						</button>

					</form>

				</div>

			</div>
		</div>





		<div id="conclusion_site" class="contenedor_inicio shadowNormal conclusion_site">

			<div class="contenedor_scroll">

				<div class="contenedor_imagen">
					<div class="imagen">
						<img src="{{asset('img/icon/rocket.svg')}}" alt="">
					</div>
				</div>

				<div class="contenedor_texto">
					<h4>¡Datos registrados exitosamente!</h4>
					<h6>Gracias por registrar los datos faltantes, ahora disfruta de la aplicación administrativa del Ministerio de Salud Pública.</h6>

					<a href="{{route('Dashboard.Index')}}" title="">
						<button class="Button_submit">Ir al Dashboard</button>
					</a>
				</div>

			</div>
		</div>





	</div>
</section>






@yield('footer_data')
