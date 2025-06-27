<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/logo-tekkim-polinema.png') }}" alt="Logo Teknik Kimia Polinema" class="brand-image" style="max-width: 80px; height: auto; opacity: .8">
        <span class="brand-text font-weight-dark text-xs text-uppercase">Jurusan Teknik Kimia</span>
    </a>

    @php
    $user = session('admin') ?? session('dosen') ?? session('mahasiswa');
    // dd(session()->all());
    @endphp
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            @if (session()->has('admin'))
                @php
                $images = $admin->image;
                @endphp
            <img src="{{ asset('storage/image/' . $images) }}" class="img-circle elevation-2" alt="User Image">
            @elseif (session()->has('dosen'))
                @php
                    $images = $dosen->image;
                @endphp
            <img src="{{ asset('storage/image/' . $images) }}" class="img-circle elevation-2" alt="User Image">
            @elseif (session()->has('mahasiswa'))
                @php
                    $images = $mahasiswa->image;
                @endphp
            <img src="{{ asset('storage/image/' . $images) }}" class="img-circle elevation-2" alt="User Image">
            @endif
            </div>
            <div class="info">
            @if (session()->has('admin'))
            <a href="#" class="d-block">{{ $admin->nama }}</a>
            @elseif (session()->has('dosen'))
            <a href="#" class="d-block">{{ $dosen->nama }}</a>
            @elseif (session()->has('mahasiswa'))
            <a href="#" class="d-block">{{ $mahasiswa->nama }}</a>
            @else
                <p>Welcome, Guest!</p>
                <!-- Atau tampilkan pesan lain jika data admin tidak ditemukan -->
            @endif
            </div>
        </div> --}}
        {{-- @if (session()->has('admin')) --}}
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @php
                    $user = session('admin');
                    $image = $user->image;
                    $defaultImage = 'default-150x150.png';
                @endphp
                @if($image && file_exists(public_path('storage/image/' . $image)))
                    <img src="{{ asset('storage/image/' . $image) }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="{{ asset('dist/img/' . $defaultImage) }}" class="img-circle elevation-2" alt="Default Image">
                @endif
            </div>
            <div class="info">
                    <a href="{{ route('admin.user', $user->id_auth) }}" class="d-block">{{ $user->nama }}</a>
            </div>
        </div>
        {{-- @endif --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                @if (session()->has('admin'))
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                @endif
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <!-- <span class="right badge badge-danger">New</span> -->
                </p>
                </a>
            </li>
            @if (session()->has('admin'))
            <li class="nav-item">
                <a href="{{ route('admin.mahasiswa') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa') ? 'active' : '' }}">
                <i class="fas fa-user-graduate nav-icon"></i>
                <p>Data Mahasiswa</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dosen') }}" class="nav-link {{ request()->routeIs('admin.dosen') ? 'active' : '' }}">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Data Dosen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.rps') }}" class="nav-link {{ request()->routeIs('admin.rps') ? 'active' : '' }}">
                <i class="fas fa-book-reader nav-icon"></i>
                <p>Data RPS</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kelaskuliah') }}" class="nav-link {{ request()->routeIs('admin.kelaskuliah') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                    Data Kelas Perkuliahan
                </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->routeIs(['admin.semester', 'admin.kelas', 'admin.cpl']) ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    Data Master
                    <i class="fas fa-angle-left right"></i>
                    <!-- <span class="badge badge-info right">6</span> -->
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.semester') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Semester</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kelas') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.matakuliah') }}" class="nav-link {{ request()->routeIs('admin.matakuliah') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Mata Kuliah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.cpl') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data CPL</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.admins') }}" class="nav-link {{ request()->routeIs('admin.admins') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>
                    Data Admin
                </p>
                </a>
            </li>
            @endif
            <li class="nav-item">
                {{-- <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-start"></i>
                        <p class="text-start">
                            Logout
                        </p>
                    </button>
                </form> --}}
                <a href="#" onclick="logout()" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt text-start"></i>
                    <p class="text-start">Logout</p>
                </a>
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
