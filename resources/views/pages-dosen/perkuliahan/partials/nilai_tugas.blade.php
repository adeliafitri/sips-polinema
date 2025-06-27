
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th style="width: 10px">No</th>
            <th>Waktu Pelaksanaan</th>
            <th>Kode Sub CPMK</th>
            <th>Bobot Soal</th>
            <th>Bentuk Soal</th>
            <th style="width: 75px">Nilai</th>


        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datas)
        <tr>
            {{-- <td>{{ $startNumber++ }}</td> --}}
            <td></td>
            <td>{{ $datas->waktu_pelaksanaan }}</td>
            <td>{{ $datas->kode_subcpmk }}</td>
            <td>{{ $datas->bobot_soal }}%</td>
            <td>{{ $datas->bentuk_soal }}</td>
            <td > <div id="nilai-tugas-{{ $datas->id_nilai }}"> {{ $datas->nilai }}
                    <i class="nav-icon fas fa-edit" onclick="editNilaiTugas({{ $datas->id_nilai }})" style="cursor: pointer"></i>
                </div>
                <form action="{{ route('dosen.kelaskuliah.editnilaitugas') }}" method="POST" class="d-flex justify-content-end fit-content ">
                    @csrf
                    <input type="hidden" name="id_nilai" value="{{ $datas->id_nilai }}">
                        <input type="hidden" class="form-control" type="number" name="nilai" value="{{ $datas->nilai }}">
                        <input type="hidden" class="form-control" type="number" name="mahasiswa_id" value="{{ $datas->mahasiswa_id }}">
                        <input type="number" id="edit-nilai-tugas-form-{{ $datas->id_nilai }}" class="form-control" name="matakuliah_kelasid" value="{{ $datas->matakuliah_kelasid }}" style="width: 75px; display: none;">
                        <button style="display: none;" type="submit" id="edit-nilai-tugas-button-{{ $datas->id_nilai }}" class="ml-2 btn btn-sm btn-primary" onclick="editNilai({{ $datas->id_nilai })"><i class="fas fa-check"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- @section('script')
<script>
    function editNilai(id){
        Swal.fire({
        title: "Anda Yakin?",
        text: "Ubah Nilai Tugas",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, ubah!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('edit-nilai-tugas-button-' + id).submit();

            }
        });

        }
</script>
@endsection --}}
