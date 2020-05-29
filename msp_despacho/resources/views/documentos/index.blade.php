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
				<h6 class="last">Documentos</h6>
			</div>




				<div class="contenedor_carpetas">

					<div class="grid-1-center titulos_opciones">
						<div class="col-11-noGutter">
							<div class="grid-1-noGutter">
								<div class="col-5_sm-12">
									<h4> <i class="fas fa-folder-open"></i>CARPETAS CREADAS</h4>
								</div>
								<div class="col-7_sm-12 grid-1">
									<div class="col-6_sm-6_xs-12">
										<div id="open_create_folder" class="boton_2">
											<i class="fas fa-folder-plus"></i>
											CREAR CARPETA
										</div>
									</div>

									<div class="col-6_sm-6_xs-12">
										<div id="open_create_document" class="boton_2">
											<i class="fas fa-folder-plus"></i>
											SUBIR DOCUMENTO
										</div>
									</div>


								</div>
							</div>

						</div>
					</div>





					@include('base.flash_message')




					<div class="contenedor_carpetas">

						@php
							$email = Auth::user()->email;
							$id = Auth::user()->id;
							$carpetas = DB::table('carpetas')
										->where('access', 'LIKE', '%'.$email.'%')
										->where('status', '0', )
										->orderBy('id', 'DESC')
										->get()
										->all();


							$documentos_folder = DB::table('documentos')
													->where('send', 'LIKE', '%'.$email.'%')
													->orderBy('id', 'DESC')
													->take(5)
													->get()
													->all();

							$old = DB::table('documentos')
													->where('send', 'LIKE', '%'.$email.'%')
													->get()
													->all();

							$documentos = DB::table('documentos')->get()->all();


							$likes =  DB::table('likes')->where('register_id', $id)->get()->all();
						@endphp






						<div class="contenedor_carpetas_seccion documentos_recientes">
							<h5>Ultimos archivos agregados:</h5>

							<div class="documento_name">

								<div class="slider_documento" data-slick='{"slidesToShow": 4, "slidesToScroll": 4}'>

									<?php $imagenes = ['jpg', 'gif', 'png']; ?>
									@foreach($documentos_folder as $value)

										<div class="documento_imagen">

											<div class="imagen">
												@if( in_array( strtolower(substr($value->imagen, -3)), $imagenes) )
													<img src="{{asset('/storage')}}/{{$value->imagen}}" alt="">

												@elseif(in_array( strtolower(substr($value->imagen, -3)), ['pdf']))
													<img src="{{asset('/img/pdf_image.jpg')}}" alt="">
												@elseif(in_array( strtolower(substr($value->imagen, -4)), ['docx']))
													<img src="{{asset('/img/word_image.png')}}" alt="">
												@elseif(in_array( strtolower(substr($value->imagen, -4)), ['xlsx']))
													<img src="{{asset('/img/excel_image.png')}}" alt="">
												@endif
											</div>

											

											<div class="texto">
												<h6>{{$value->name}} </h6>

												<div class="opciones_documentos">
													<div class="opciones">
														<a href="/documentos/download/{{$value->id}}" title="">
															<i class="fas fa-file-download"></i>
														</a>
													</div>

													<div class="opciones">
														<a href="{{ route('Folder.Move', ['id' => $value->id])}}">
															<i class="fas fa-file-import"></i>
														</a>
													</div>

													<div class="opciones">
														<a href="/documentos/edit/{{$value->id}}">
															<i class="far fa-edit"></i>
														</a>
													</div>

													<div class="opciones">
														<a class="Open_event_created" value="{{$value->name}}" title="">
															<i class="far fa-calendar-times"></i>
														</a>
													</div>


													<div class="opciones">
														<a href="{{ route('Documentos.Delete', ['id' => $value->id])}}" onclick="return confirm('¿Deseas borrar el archivo?');">
															<i class="far fa-trash-alt"></i>
														</a>
													</div>
												</div>
											</div>
										</div>


									@endforeach
								</div>

							</div>


						</div>






















						<div class="contenedor_carpetas_seccion carpetas_privadas">
							<h5>Carpetas Privadas:</h5>

							<div class="carpetas_name">

								<div class="carpeta shadow">
									<i class="fas fa-folder"></i>
									<h6 id="all_Privado" class="Filter_document">
										Todos los documentos
									</h6>

								</div>

								@php 
									$carpetas_privadas = DB::table('carpetas')->where(['tipo' => 'Privado', 'register_id' => Auth::user()->id ])->get()->all() 
								@endphp

								@foreach($carpetas_privadas as $value)
									@php
										$documentos_folder = DB::table('documentos')->where('carpeta_id', $value->id)->count();
									@endphp

									<div class="carpeta shadow">
										<i class="fas fa-folder"></i>
										<h6 id="{{$value->id}}" class="Filter_document">
											{{$value->name}} ( {{ $documentos_folder }} )
										</h6>

										<div class="opciones">
											<a href="/folder/edit/{{$value->id}}">
												<i class="far fa-edit"></i>
											</a>

											<a href="{{ route('Folder.Delete', ['id' => $value->id]) }}" onclick="return confirm('¿Deseas borrar este usuario?');">
												<i class="far fa-trash-alt"></i>
											</a>
										</div>
									</div>

								@endforeach
							</div>

						</div>





						<div class="contenedor_carpetas_seccion carpetas_publicas">
							<h5>Carpetas Publicas:</h5>

							<div class="carpetas_name">

								<div class="carpeta shadow">
									<i class="fas fa-folder"></i>
									<h6 id="all_Publico" class="Filter_document">
										Todos los documentos
									</h6>
								</div>

								@php $public_folders = DB::table('carpetas')->where('tipo', 'Publico')->get()->all(); @endphp

								@foreach($public_folders as $value)
									@php $documentos_folder = DB::table('documentos')->where('carpeta_id', $value->id )->count(); @endphp
									@if($value->access == 'all')

										@if($value->tipo == 'Publico')
											<button type="submit" class="carpeta shadow">
												<i class="fas fa-folder"></i>
												<h6 id="{{$value->id}}" class="Filter_document">
													{{$value->name}} ( {{ $documentos_folder }}  )
												</h6>
												<div class="opciones">
													<a href="{{ route('Folder.Edit',['id' => $value->id] )}}">
														<i class="far fa-edit"></i>
													</a>

													<a href="{{ route('Folder.Delete', ['id' => $value->id]) }}" onclick="return confirm('¿Deseas borrar este usuario?');">
														<i class="far fa-trash-alt"></i>
													</a>
												</div>
											</button>
										@endif

									@else

										@if (strpos($value->access, Auth::user()->email) !== false)
											
											@if($value->tipo ==
											 'Publico')
												<button type="submit" class="carpeta shadow">
													<i class="fas fa-folder"></i>
													<h6 id="{{$value->id}}" class="Filter_document">
														{{$value->name}} ( {{ $documentos_folder }} )
													</h6>

													<div class="opciones">
														<a href="{{ route('Folder.Edit',['id' => $value->id] )}}">
															<i class="far fa-edit"></i>
														</a>

														<a href="{{ route('Folder.Delete', ['id' => $value->id]) }}" onclick="return confirm('¿Deseas borrar este usuario?');">
															<i class="far fa-trash-alt"></i>
														</a>
													</div>
												</button>
											@endif
										@endif

									@endif
								@endforeach
							</div>

						</div>





						<div class="grid-1 documento_name">

							@php 
								$documentos = DB::table('documentos')->get()->all();
								$carpetas = DB::table('carpetas')->get()->all();
							@endphp



							@foreach($documentos as $value)


								@if($value->send == 'all')

									@foreach($carpetas as $value_carpeta)

										<!-- ASIGNAMOS LA CARPETA A CADA DOCUMENTO -->
										@if($value->carpeta_id == $value_carpeta->id)

											<!-- VERIFICAMOS SI ES PUBLICO O PRIVADO -->
											@if($value_carpeta->tipo == 'Publico')


												@if($value_carpeta->register_id == Auth::user()->id)

													<div class="col-3_lg-3_sm-6_xs-12  documentos  {{$value_carpeta->tipo}} filter_{{$value->carpeta_id}}">
		                                                <div class="documento_imagen">
		                                                    <div class="imagen">
		                                                        @if( in_array( strtolower(substr($value->imagen, -3)), $imagenes) )
		                                                            <img src="{{asset('/storage')}}/{{$value->imagen}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -3)), ['pdf']))
																	<img src="{{asset('/img/pdf_image.jpg')}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['docx']))
		                                                            <img src="{{asset('/img/word_image.png')}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['xlsx']))
		                                                            <img src="{{asset('/img/excel_image.png')}}" alt="">
		                                                        @endif
		                                                    </div>
		                                                    <div class="texto">
		                                                        <h6>{{$value->name}} </h6>

		                                                        <div class="opciones_documentos">
		                                                            <div class="opciones">
		                                                                <a href="/documentos/download/{{$value->id}}" title="">
		                                                                    <i class="fas fa-file-download"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a href="{{ route('Folder.Move', ['id' => $value->id])}}">
		                                                                    <i class="fas fa-file-import"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a href="/documentos/edit/{{$value->id}}">
		                                                                    <i class="far fa-edit"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a class="Open_event_created" value="{{$value->name}}" title="">
		                                                                    <i class="far fa-calendar-times"></i>
		                                                                </a>
		                                                            </div>


		                                                            <div class="opciones">
		                                                                <a href="{{ route('Documentos.Delete', ['id' => $value->id])}}" onclick="return confirm('¿Deseas borrar el archivo?');">
		                                                                    <i class="far fa-trash-alt"></i>
		                                                                </a>
		                                                            </div>


		                                                        </div>
		                                                    </div>
		                                                </div>
		                                            </div>


												@else

													<div class="col-3_lg-3_sm-6_xs-12  documentos  {{$value_carpeta->tipo}} filter_{{$value->carpeta_id}}">
			                                                <div class="documento_imagen">
			                                                    <div class="imagen">
			                                                        @if( in_array( strtolower(substr($value->imagen, -3)), $imagenes) )
			                                                            <img src="{{asset('/storage')}}/{{$value->imagen}}" alt="">
			                                                        @elseif(in_array( strtolower(substr($value->imagen, -3)), ['pdf']))
																		<img src="{{asset('/img/pdf_image.jpg')}}" alt="">
			                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['docx']))
			                                                            <img src="{{asset('/img/word_image.png')}}" alt="">
			                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['xlsx']))
			                                                            <img src="{{asset('/img/excel_image.png')}}" alt="">
			                                                        @endif
			                                                    </div>
			                                                    <div class="texto">
			                                                        <h6>{{$value->name}} </h6>

			                                                        <div class="opciones_documentos">
			                                                            <div class="opciones">
			                                                                <a href="/documentos/download/{{$value->id}}" title="">
			                                                                    <i class="fas fa-file-download"></i>
			                                                                </a>
			                                                            </div>

			                                                            <div class="opciones">
			                                                                <a class="Open_event_created" value="{{$value->name}}" title="">
			                                                                    <i class="far fa-calendar-times"></i>
			                                                                </a>
			                                                            </div>

			                                                        </div>
			                                                    </div>
			                                                </div>
			                                            </div>
		                                        @endif

											@else

												<!-- EN CASO DE QUE LA CARPETA ES DEL USUARIO -->
												@if($value_carpeta->register_id == Auth::user()->id)

													<div class="col-3_lg-3_sm-6_xs-12  documentos  {{$value_carpeta->tipo}} filter_{{$value->carpeta_id}}">
		                                                <div class="documento_imagen">
		                                                    <div class="imagen">
		                                                        @if( in_array( strtolower(substr($value->imagen, -3)), $imagenes) )
		                                                            <img src="{{asset('/storage')}}/{{$value->imagen}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -3)), ['pdf']))
																	<img src="{{asset('/img/pdf_image.jpg')}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['docx']))
		                                                            <img src="{{asset('/img/word_image.png')}}" alt="">
		                                                        @elseif(in_array( strtolower(substr($value->imagen, -4)), ['xlsx']))
		                                                            <img src="{{asset('/img/excel_image.png')}}" alt="">
		                                                        @endif
		                                                    </div>
		                                                    <div class="texto">
		                                                        <h6>{{$value->name}} </h6>

		                                                        <div class="opciones_documentos">
		                                                            <div class="opciones">
		                                                                <a href="/documentos/download/{{$value->id}}" title="">
		                                                                    <i class="fas fa-file-download"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a href="{{ route('Folder.Move', ['id' => $value->id])}}">
		                                                                    <i class="fas fa-file-import"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a href="/documentos/edit/{{$value->id}}">
		                                                                    <i class="far fa-edit"></i>
		                                                                </a>
		                                                            </div>

		                                                            <div class="opciones">
		                                                                <a class="Open_event_created" value="{{$value->name}}" title="">
		                                                                    <i class="far fa-calendar-times"></i>
		                                                                </a>
		                                                            </div>


		                                                            <div class="opciones">
		                                                                <a href="{{ route('Documentos.Delete', ['id' => $value->id])}}" onclick="return confirm('¿Deseas borrar el archivo?');">
		                                                                    <i class="far fa-trash-alt"></i>
		                                                                </a>
		                                                            </div>


		                                                        </div>
		                                                    </div>
		                                                </div>
		                                            </div>


												@endif
											@endif


										@endif
									@endforeach


								@endif


							@endforeach

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
