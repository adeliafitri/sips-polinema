<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.admin.header')
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <!-- <div class="register-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div> -->

  <div class="card">
    <div class="card-body register-card-body">
        <div class="text-center mb-2">
            <img src="{{ asset('dist/img/logo-arsitektur-UIN-Malang.png') }}" width="100px" alt="logo arsitektur UIN Malang">
        </div>
      <p class="login-box-msg">Register a new membership</p>
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        {{-- @if ($errors->has('registration'))
            <div class="alert alert-danger">
                {{ $errors->first('registration') }}
            </div>
        @endif --}}
      <form action="{{ route('register') }}" method="post">
        @CSRF
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="nama" placeholder="Full Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="telp" placeholder="08xxx">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-alt"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="form-group">
            {{-- <label for="role">Role</label> --}}
            <select class="form-control" id="role" name="role">
                <option value="">- Pilih Role -</option>
                <option value="admin">Admin</option>
                <option value="dosen">Dosen</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>
        </div>
        <div id="mahasiswaFields" class="form-group" style="display: none">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="nim" placeholder="NIM">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <input type="date" class="form-control text-muted" name="tanggal_lahir" placeholder="">
            </div>
            <div class="form-group">
                {{-- <label for="role">Role</label> --}}
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                    <option value="">- Pilih Jenis Kelamin -</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>
        <div id="dosenFields" class="form-group" style="display: none">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="nidn" placeholder="NIDN">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-id-card"></span>
                  </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-12 mb-2">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

@include('layouts.admin.script')
<script>
    document.getElementById('role').addEventListener('change', function () {
        var roleValue = this.value;
        var mahasiswaFields = document.getElementById('mahasiswaFields');
        var dosenFields = document.getElementById('dosenFields');

        if (roleValue === 'mahasiswa') {
            mahasiswaFields.style.display = 'block';
            dosenFields.style.display = 'none';
        }else if(roleValue === 'dosen'){
            dosenFields.style.display = 'block';
            mahasiswaFields.style.display = 'none';
        } else {
            mahasiswaFields.style.display = 'none';
            dosenFields.style.display = 'none';
        }
    });
</script>
</body>
</html>
