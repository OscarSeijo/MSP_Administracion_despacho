@include('base.base')
@include('base.emergente')
@yield('header_data')


@if(Auth::check())

    @yield('nav')
    @yield('emergente')
	<div class="contenedor_dashboard">
		<div class="cont_scroll">

			<div class="contenedor_linea">
				<a href="/home"><h6>Dashboard</h6></a>
				<h6><i class="fas fa-angle-right"></i></h6>
				<h6 class="last">Profile</h6>
			</div>

            @include('base.flash_message')
            <div class="contenedor_información">
                @foreach($usuario as $value)

                    <div class="datos_principal">
                        <div class="avatar">
                            <img src="{{asset('/storage')}}/{{$value->avatar}}" alt="">
                        </div>
                        <div class="info_general">
                            <h3>{{$value->name}} {{$value->lastname}}</h3>
                            <h6>{{$value->department}} / {{$value->cargo}} / {{$value->tipo}}</h6>
                            <h6>Fecha de creación: {{ Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }} ( {{ $value->created_at->diffForHumans() }} )</h6>
                        </div>
                    </div>

                    <div class="información_secundaria shadow">
                        <div class="grid-1-center">
                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Correo electronico:</h5>
                                <h6>{{$value->email}}</h6>
                            </div>

                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Codigo identidad:</h5>
                                <h6>{{$value->cedula}}</h6>
                            </div>

                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Cargo:</h5>
                                <h6>{{$value->cargo}}</h6>
                            </div>

                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Role:</h5>
                                @php $roles = DB::table('roles')
                                                    //->select('display_name')
													->where('id', $value->id)
													->take(1)
													->get()
													//->first();
                                @endphp
                                @foreach($roles as $value_roles)
                                    <h6>{{$value_roles->display_name}}</h6>
                                @endforeach

                            </div>

                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Numero telefonico primario:</h5>
                                <h6>{{$value->phone}}</h6>
                            </div>

                            <div class="col-6_lg-6_sm-6_xs-12">
                                <h5>Numero telefonico secundario:</h5>
                                <h6>{{$value->secundary_phone}}</h6>
                            </div>

                            @if($value->id === Auth::user()->id)
                                <div class="col-6_lg-6_sm-12 button_contenedor">
                                    <div class="boton" id="updatedProfile">MODIFICAR DATOS</div>
                                </div>
                                <div class="col-6_lg-6_sm-12 button_contenedor">
                                    <div class="boton" id="changePassword" >CAMBIAR CONTRASEÑA</div>
                                </div>
                            @endif

                        </div>


                    </div>





                @endforeach

            </div>
		</div>
	</div>

@else
    <script>window.location.href = "{{ route('Dashboard.Index') }}";</script>
@endif











@yield('footer_data')
