@include('base.base')
@yield('header_data')

 
@if(Auth::check())

    @yield('nav')
	<div class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Carpeta</h6>
			</div>

				
			<div class="contenedor_usuarios_count">
				@include('base.flash_message')

				<div class="grid-1">

					<div class="col-12_md-12_sm-12_xs-12">
						<div class="contenedor_datos ultimas_actividades">
							<h3>Registrar nuevo documento</h3>

							<form action="{{ route('Folder.Upload') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
							     @csrf

                                 @foreach($data as $datas)
                                    <div class="form-group">
                                        <label>Nombre de la carpeta*</label>
                                        <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{$datas->name}}">
                                    </div>

                                    <div class="form-group">
                                        <label>Seleccione el tipo de carpeta*</label>
                                        <select name="tipo" class="form-control" >
                                            <option value="{{$datas->tipo}}"></option>}
                                            <option value="Publico" default>Publico</option>
                                            <option value="Privado">Privado</option>
                                        </select>   
                                    </div>


                                    <div class="form-group">
                                        <label>Seleccione los usuarios que pueden acceder, en caso de ninguno, dejarlo vacio. (ctrl para seleccionar más de uno)</label>
                                        @php $users = DB::table('users')->where('status', 0)->get()->all(); @endphp
                                        <select multiple class="form-control users_select" name="access[]" class="">
                                            @foreach($users as $value)
                                                <option value="{{$value->email}}" class="opcion_evento_create">
                                                    <h6>{{$value->name}} {{$value->lastname}} - {{$value->email}}, {{$value->department}}</h6>
                                                </option>
                                            @endforeach
                                         </select>
                                    </div>   

                                    <input type="hidden" name="id" value="{{$datas->id}}">
                                @endforeach

                                <!-- id="registrar_user" -->

                                <button type="submit" class="btn btn-primary boton_completo">
                                    Modificar Carpeta
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