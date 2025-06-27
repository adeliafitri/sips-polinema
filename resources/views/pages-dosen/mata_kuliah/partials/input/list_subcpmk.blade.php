@foreach ($kode_subcpmk as $subcpmkid => $value)
<div class="col-2">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="subcpmk_{{ $subcpmkid }}" name="pilih_subcpmk[]" value="{{ $subcpmkid }}">
        <label class="form-check-label" for="subcpmk_{{ $subcpmkid }}">{{ $value }}</label>
    </div>
</div>
@endforeach
