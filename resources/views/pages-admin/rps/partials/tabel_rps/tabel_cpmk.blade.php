<div class="table-responsive" id="tabel-data-cpmk">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Kode CPL</th>
                <th>Kode Indikator Kinerja CPL</th>
                <th>Deskripsi Indikator Kinerja CPL</th>
                <th style="width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_cpmk as $key => $datas)
            <tr>
                <td>{{ $start_nocpmk++ }}</td>
                <td>{{ $datas->kode_cpl }}</td>
                <td>{{ $datas->kode_cpmk }}</td>
                <td>{{ $datas->deskripsi }}</td>
                <td class="d-flex justify-content-center">
                    <!-- <a href="index.php?include=detail-kelas" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> -->
                    <button class="btn btn-secondary mt-1 mr-1 btn-edit-cpmk" onclick="editCpmk({{ $datas->id }})"><i class="nav-icon fas fa-edit"></i></button>
                    <a class="btn btn-danger mt-1" onclick="deleteCpmk({{$datas->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data_cpmk->links('pagination::bootstrap-4') !!}
    </div>
</div>
