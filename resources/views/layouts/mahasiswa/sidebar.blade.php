<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('mahasiswa.dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/logo-tekkim-polinema.png') }}" alt="Logo Teknik Kimia Polinema" class="brand-image" style="max-width: 80px; height: auto; opacity: .8">
        <span class="brand-text font-weight-dark text-xs text-uppercase">Jurusan Teknik Kimia</span>
    </a>

    @php
    $user = session('admin') ?? session('mahasiswa') ?? session('mahasiswa');
    // dd(session()->all());
    @endphp
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @php
                    $user = session('mahasiswa');
                    $image = $user->image;
                    $defaultImage = 'default-150x150.png';
                @endphp
                @if($image && file_exists(public_path('storage/image/' . $image)))
                    <img src="{{ asset('storage/image/' . $image) }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('dist/img/' . $defaultImage) }}" class="img-circle elevation-2" alt="Default Image">
                @endif
                    {{-- <img src="{{ asset('storage/image/' . $image) }}" class="img-circle elevation-2" alt="User Image"> --}}
            </div>
            <div class="info">
                    <a href="{{ route('mahasiswa.user', $user->id_auth) }}" class="d-block">{{ $user->nama }}</a>
            </div>
        </div>
        {{-- @endif --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                @if (session()->has('mahasiswa'))
                <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                @endif
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <!-- <span class="right badge badge-danger">New</span> -->
                </p>
                </a>
            </li>
            @if (session()->has('mahasiswa'))
            {{-- <li class="nav-item">
                <a href="" class="nav-link {{ request()->routeIs('mahasiswa.rps') ? 'active' : '' }}">
                <i class="fas fa-book-reader nav-icon"></i>
                <p>Data RPS</p>
                </a>
            </li> --}}
            <li class="nav-item">
                <a href="{{ route('mahasiswa.kelaskuliah') }}" class="nav-link {{ request()->routeIs('mahasiswa.kelaskuliah') ? 'active' : '' }}">
                <i class="fas fa-book-reader nav-icon"></i>
                <p>Data Kelas Perkuliahan</p>
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="#" onclick="logout()" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt text-start"></i>
                    <p class="text-start">Logout</p>
                </a>
                {{-- <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-start"></i>
                        <p class="text-start">
                            Logout
                        </p>
                    </button>
                </form> --}}
            </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    function logout() {
        Swal.fire({
            title: "Logout",
            text: "Apakah anda yakin ingin keluar dari aplikasi?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, keluar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/logout",
                    type: "POST",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(response) {
                        // Tindakan sukses, misalnya refresh halaman atau redirect ke halaman login
                        window.location.href = "{{ route('login') }}";
                    },
                    error: function(xhr) {
                        // Tindakan jika terjadi kesalahan
                        console.log('Kesalahan: ' + xhr.responseText);
                    }
                });
            }
        });
    }
</script>
