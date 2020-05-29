@include('base.base')
@include('auth.login')

@yield('header_data')

    <div class="contenedor_Auth">

        <div class="contenedor_imagen">
           <img src="{{ asset('img/foto_inicio.jpg') }}" >

        </div>

        <div class="contenedor_formulario">  
            <div class="data_cont login_form">

                <div class="info_header">
                    <h5>Forgot Your Password?</h5>
                    <h6>It’s ok… follow the instruction below</h6>
                    <p>To reset your password, please put your email account below. After that, you will recieved a email verification code.</p>
                </div>


                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="formulario">
                    @csrf
                        

                      <label for="email" class="col-md-12 col-form-label text-md-center">{{ __('E-Mail Address') }}</label>
                    <div class="form-group">

                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                       
                    </div>


                    <button type="submit" class="btn btn-primary boton_form">
                        <i class="far fa-envelope"></i>
                        RESET PASSWORD
                    </button>

                    <div class="form_option text_bottom grid-1-center">
                        <div class="form-check col-12">
                            <a href="/"><p>You remember the password?</p></a>
                        </div> 
                    </div>

                </form>

            </div>

                   



        </div>
    </div>


































<!--


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@yield('footer_data')
