<body class="login-page" style="min-height: 496.781px;">
    <div class="login-box">
      <div class="login-logo">
       Blog del viajero
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Iniciar Sesión</p>
    
          <form method="POST" action="{{ route('login') }}">
            @csrf
            {{-- email --}}
            <div class="input-group mb-3">
                <div class="input-group-append input-group-text">
                  <i class="fas fa-envelope"></i>
                </div>
                <input id="email" type="email" class="form-control email_login @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
    
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
            </div>
            {{-- Password --}}

            <div class="input-group mb-3">
                <div class="input-group-append input-group-text">
                <i class="fas fa-key"></i>
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Contraseña">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
           

          
            <div class="text-center">
              
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                     Ingresar
                    </button>

           
            </div>
        </form>
    
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-js -->    
    <script src="{{url('/')}}/js/login.js"></script>
</body>