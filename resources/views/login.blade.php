{{-- @extends('layouts.admin.main') --}}
<html>
<head>
    @include('partials.header')
</head>

<body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="card">
        <!-- <div class="card-header text-center">
          <a href="../index2.html" class="h1"><b>Admin</b>LTE</a>
        </div> -->
        <div class="card-body bg-success">
            <div class="row justify-content-center">
                <div class="col-3 mb-2">
                    <img src="{{ asset('dist/img/logo_polinema.png') }}" width="75px" alt="logo Prodi Arsitektur UIN Malang">
                </div>
                <div class="col-5 mb-2 py-4">
                    <img src="{{ asset('dist/img/logo-tekkim-polinema.png') }}" width="120px" alt="logo Prodi Arsitektur UIN Malang">
                </div>
            </div>
            <h3 class="text-center">OBE Jurusan Teknik Kimia</h3>
            {{-- <p class="login-box-msg">Enter your details to get sign in to your account</p> --}}

            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
          <form id="loginForm">
            @CSRF
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="Username">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group">
              <input type="password" name="password" class="form-control" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            {{-- <p class="text-end">
                <a href="forgot-password.html" class="text-dark">Forgot Password?</a>
            </p> --}}
            <button type="button" class="btn btn-primary btn-block mt-3" onclick="login()">Sign In</button>
          </form>

          <!-- <div class="social-auth-links text-center mt-2 mb-3">
            <a href="#" class="btn btn-block btn-primary">
              <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
              <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
            </a>
          </div> -->
          <!-- /.social-auth-links -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->

    {{-- @include('layouts.admin.script') --}}
    </body>
    </html>

  <script>
    function login() {
        var form = $('#loginForm');
        $.ajax({
            type: 'POST',
            url: "{{ url('login') }}",
            data: form.serialize(),
            success: function(response) {
                if (response.status == "success") {
                    // Check if the user clicked "OK"
                    // if (result.isConfirmed) {
                        // Redirect to the desired URL
                        window.location.href = response.route;
                    // };
                }
                console.log(response);
            },
            error: function(xhr, status, error) {
                if (xhr.status == 422) {
                    var errorMessage = xhr.responseJSON.message;
                    Swal.fire({
                    icon: "error",
                    title:"Validation Error",
                    text: errorMessage,
                }).then((result) => {
                    // Check if the user clicked "OK"
                    if (result.isConfirmed) {
                        // Redirect to the desired URL
                        window.location.reload();
                    };
                });
                }
                else if(xhr.status == 401){
                    var errorMessage = xhr.responseJSON.message;
                    Swal.fire({
                    icon: "error",
                    title:"Error!",
                    text: errorMessage,
                }).then((result) => {
                    // Check if the user clicked "OK"
                    if (result.isConfirmed) {
                        // Redirect to the desired URL
                        window.location.reload();
                    };
                });
                }
                else{
                    var errorMessage = xhr.responseJSON.message;
                    Swal.fire({
                    icon: "error",
                    title:"Error!",
                    text: errorMessage,
                }).then((result) => {
                    // Check if the user clicked "OK"
                    if (result.isConfirmed) {
                        // Redirect to the desired URL
                        window.location.reload();
                    };
                });
                }
                // Handle error here
                console.error(xhr.responseText);
            }
        });
    }

    $('#loginForm').on('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            login();
        }
    });
</script>
