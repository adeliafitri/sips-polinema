@extends('layouts.admin.main')

@section('content')
    <section class="content-header">
        <!-- Header content goes here -->
    </section>

    @foreach ($data as $item)
        <section class="content">
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">{{ $item['matakuliah']->nama_matkul }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card card-primary card-outline card-tabs">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                @foreach ($item['classes'] as $class)
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-three-{{ str_replace(' ', '_', $class->nama_kelas) }}-tab" data-toggle="pill" href="#custom-tabs-three-{{ str_replace(' ', '_', $class->nama_kelas) }}" role="tab" aria-controls="custom-tabs-three-{{ str_replace(' ', '_', $class->nama_kelas) }}" aria-selected="true">Kelas {{ $class->nama_kelas }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>


                        <div class="card-body">
                            @foreach ($item['classes'] as $class)
                                <div class="tab-pane fade" id="custom-tabs-three-{{ str_replace(' ', '_', $class->nama_kelas) }}" role="tabpanel">
                                    <p><span class="text-bold">Dosen :</span> {{ $class->nama_dosen }}</p>
                                    <p><span class="text-bold">Jumlah Mahasiswa(Aktif) :</span> 15</p>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Nilai Akhir</th>
                                                <th>Keterangan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Loop through students for this class --}}
                                            @php
                                                $batas = 5;
                                                $posisi = 0; // Reset position for each class
                                                $halaman = 1; // Reset page for each class
                                                $no = 0; // Reset numbering for each class
                                            @endphp
                                            @foreach ($item['studentsData'][$class->id] as $student)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    <td>{{ $student->nim }}</td>
                                                    <td>{{ $student->nama }}</td>
                                                    <td>{{ $student->nilai_akhir }}</td>
                                                    <td>Lulus</td>
                                                    <td>
                                                        {{-- <a href="{{ route('detail-nilai-mahasiswa', ['data' => $class->id, 'id_mahasiswa' => $student->id_mahasiswa]) }}" class="btn btn-info">
                                                            <i class="nav-icon far fa-eye mr-2"></i>Detail
                                                        </a> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
@endsection
