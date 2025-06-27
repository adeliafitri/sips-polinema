<table>
    <thead>
        <tr>
            <th style="text-align: center; font-weight: bold;">Kode CPL</th>
            <th style="text-align: center; font-weight: bold;">Jenis CPL</th>
            <th style="text-align: center; font-weight: bold;">Deskripsi</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<tbody>
    <!-- Render your table rows here -->
    <tr>
        <td></td>
        <td>
            <select id="jenis_cpl_dropdown" name="jenis_cpl">
                <!-- Options will be populated using JavaScript -->
            </select>
        </td>
        <td></td>
    </tr>
</tbody>

@section('scripts')
<script>
    // Inisialisasi dropdown
    var jenisCPLOptions = @json($jenisCPLOptions);

    // ...

    // Contoh penggunaan di dalam JavaScript untuk menetapkan opsi dropdown
    // Gunakan cara yang sesuai dengan kebutuhan Anda
    // Misalnya, jika Anda menggunakan library JavaScript seperti jQuery, Anda bisa melakukan sesuatu seperti ini:

    $(document).ready(function () {
        // ...

        // Set opsi dropdown
        $('#jenis_cpl_dropdown').empty();
        $.each(jenisCPLOptions, function (index, value) {
            $('#jenis_cpl_dropdown').append('<option value="' + value + '">' + value + '</option>');
        });

        // ...
    });
</script>
@endsection
