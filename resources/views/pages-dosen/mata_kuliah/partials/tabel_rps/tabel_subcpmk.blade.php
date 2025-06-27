<div class="table-responsive" id="tabel-data">
    <table class="table table-bordered" id="dataTable2">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Kode CPL</th>
                <th>Kode Indikator Kinerja CPL</th>
                <th>Kode CPMK</th>
                <th>Deskripsi CPMK</th>
                <th style="width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_subcpmk as $values)
            <tr>
                <td>{{ $start_nosubcpmk++ }}</td>
                <td>{{ $values->kode_cpl }}</td>
                <td>{{ $values->kode_cpmk }}</td>
                <td>{{ $values->kode_subcpmk }}</td>
                <td>{{ $values->deskripsi }}</td>
                <td class="d-flex justify-content-center">
                    <!-- <a href="index.php?include=detail-kelas" class="btn btn-info"><i class="nav-icon far fa-eye mr-2"></i>Detail</a> -->
                    <button class="btn btn-secondary mt-1 mr-1 btn-edit-subcpmk" onclick="editSubCpmk({{ $values->id }})"><i class="nav-icon fas fa-edit"></i></button>
                    <a class="btn btn-danger mt-1" onclick="deleteSubCpmk({{$values->id}})"><i class="nav-icon fas fa-trash-alt"></i></a>
                    {{-- <form action="{{ route('admin.kelas.destroy', $datas->id) }}" method="post" class="mt-1">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger ml-1" type="submit"><i class="nav-icon fas fa-trash-alt"></i></button>
                    </form> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data_subcpmk->links('pagination::bootstrap-4') !!}
    </div>
</div>

