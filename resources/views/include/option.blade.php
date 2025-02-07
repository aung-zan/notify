<div class="mb-10">
    <div class="row">
        <div class="col-4 col-md-2">
            <label class="required form-label">Provider</label>
        </div>

        @php
            $count = 0;
        @endphp

        <div class="col-8">
            @foreach ($providers as $provider => $value)
                <div class="form-check form-check-custom form-check-solid form-check-danger form-check-sm mb-3
                    @error('provider') is-invalid @enderror"
                >
                    <input type="radio"
                        value="{{$value}}"
                        name="provider"
                        class="form-check-input"
                        {{$value == old('provider', 1) ? 'checked' : ''}}
                        {{$value !== 1 ? 'disabled' : ''}}
                    >
                    <label class="form-check-label">{{$provider}}</label>
                </div>

                @php
                    $count++;
                @endphp
            @endforeach

            @error('provider')
                <div class="invalid-feedback">{{$message}}</div>
            @enderror
        </div>
    </div>
</div>

{{-- @isset($js)
    <div class="invalid-feedback" name="{{$name}}"></div>
@endisset

@error($name)
    <div class="invalid-feedback">{{$message}}</div>
@enderror --}}
