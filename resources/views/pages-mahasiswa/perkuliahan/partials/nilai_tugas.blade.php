<div class="table-responsive" id="tabel-datatugas">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Waktu Pelaksanaan</th>
                <th>Kode CPL</th>
                <th>Kode CPMK</th>
                <th>Kode Sub CPMK</th>
                <th>Bentuk Soal</th>
                <th>Bobot Soal</th>
                <th style="width: 75px">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $no = $startNumber; @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['waktu_pelaksanaan'] }}</td>
                    <td>{{ $item['kode_cpl'] }}</td>
                    <td>{{ $item['kode_cpmk'] }}</td>
                    <td>{{ $item['kode_subcpmk'] }}</td>
                    <td>{{ $item['bentuk_soal'] }}</td>
                    <td>{{ $item['bobot_soal'] }}%</td>
                    <td>{{ $item['nilai'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-3 pt-3 d-flex justify-content-end">
        {!! $data->links('pagination::bootstrap-4') !!}
    </div>
</div>
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
