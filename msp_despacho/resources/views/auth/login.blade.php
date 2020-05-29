


@section('content')
    <div class="contenedor_Auth">
                <div class="contenedor_imagen">
                    <img src="img/foto_inicio.jpg" alt="">
 
                </div>
                <div class="contenedor_formulario">
                    
                    <div class="data_cont login_form">
                        <div class="info_header">
                            <h1>APP SAD</h1>
                            <h5>Ministerio Salud Pública</h5>
                            <h6>Sistema Administración Despacho</h6>

                            <p>Sign in by entering the information below</p>
                        </div>


                        
                        <form method="POST" action="{{ route('login') }}" class="formulario">
                            @csrf
                            @include('base.flash_message')

                            <div class="form-group">

                             <input id="email" type="email" class="form-control form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                             @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            <small id="emailHelp" class="form-text text-muted">No debes compartir tu cuenta a nadie...</small>
                          </div>
                          <div class="form-group">

                             <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                          </div>

                          <div class="form_option grid-1-center">

                            <div class="remember_buton col-6">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                 <label>Remember Me</label>
                            </div>
                            
                             <div class="form-check col-6">


                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                       <p> {{ __('Forgot Your Password?') }}</p>
                                    </a>
                                @endif



                                <!-- <inertia-link href="/forgot"><p>Forgot Password?</p></inertia-link> -->
                             </div> 
                          </div>

                          
                          <button type="submit" class="btn btn-primary boton_form">
                                <i class="fas fa-lock icon"></i>
                                SIGN IN
                          </button>
                        </form>



                    </div>

                    <!-- 
                    <div class="info_footer">
                        <h6>Need information?</h6>
                        <h6 class="link_buttom">Contact</h6>
                        <h6 class="link_buttom">Policy</h6>
                    </div> -->



                </div>
            </div>
@endsection






















@section('loginForm')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
