<div class="table-responsive" id="tabel-data-tugas">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Kode CPL</th>
                <th>Kode Indikator Kinerja CPL</th>
                <th>Kode CPMK</th>
                <th>Bentuk Soal</th>
                <th>Bobot</th>
                <th>Waktu Pelaksanaan</th>
                <th style="width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_soalsubcpmk as $unique => $contents)
            <tr>
                <td>{{ $start_nosoalsubcpmk++ }}</td>
                <td>{{ $contents->kode_cpl }}</td>
                <td>{{ $contents->kode_cpmk}}</td>
                <td>{{ $contents->kode_subcpmk }}</td>
                <td>{{ $contents->bentuk_soal }}</td>
                <td>{{ $contents->bobot_soal }}%</td>
                <td>{{ $contents->waktu_pelaksanaan }}</td>
                <td class="d-flex justify-content-center">
                    <!-- <a href="index.php?include=detail-kelas" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> -->
                    <button class="btn btn-secondary mt-1 mr-1 btn-edit-soalsubcpmk" onclick="editSoalSubCpmk({{ $contents->id }})"><i class="nav-icon fas fa-edit"></i></button>
                    <a class="btn btn-danger mt-1" onclick="deleteSoal({{$contents->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                    {{-- <form action="{{ route('admin.kelas.destroy', $datas->id) }}" method="post" class="mt-1">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger ml-1" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                    </form> --}}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5">Total Bobot</td>
                <td colspan="3">{{ $total_bobot_rps }}%</td>
            </tr>
        </tbody>
    </table>
    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data_soalsubcpmk->links('pagination::bootstrap-4') !!}
    </div>
</div>
