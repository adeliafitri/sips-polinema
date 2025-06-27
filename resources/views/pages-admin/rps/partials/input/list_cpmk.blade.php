@foreach ($kode_cpmk as $cpmk_id => $kode)
<div class="col-md-12 mb-3 d-flex">
    <div class="col-md-2 align-self-center">
        <h6>{{ $kode }}</h6>
    </div>
    <div class="col-md-8">
        <button class="btn btn-primary" type="button" onclick="inputCpmk({{ $cpmk_id }}, '{{ $kode }}')"><i class="nav-icon fas fa-plus mr-2"></i> Tambah Sub-CPMK</button>
    </div>
</div>
@endforeach
