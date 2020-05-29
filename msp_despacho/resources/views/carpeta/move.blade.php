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

							<form action="{{ route('Folder.MoveDocument') }}" method="post"  enctype="multipart/form-data" accept-charset="utf-8">
							     @csrf

                                <div class="form-group">
                                    <label>Mover documento a la carpeta*</label>
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

                                <input type="hidden" name="id" value="{{$id}}">
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