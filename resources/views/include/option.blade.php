<div class="row row-cols-1 row-cols-md-4 row-cols-lg-1 row-cols-xl-4 g-9 @error($name) is-invalid @enderror"
    data-kt-buttons="true"
    data-kt-buttons-target="[data-kt-button='true']"
    name="{{$name}}"
>

    @foreach ($options as $option => $value)
        <div class="col">
            <!--begin::Option-->
            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6
                {{$value == old($name, 1) ? 'active' : ''}}
                @error($name) btn-outline-danger @enderror"
                data-kt-button="true"
                name="{{$name}}"
            >
                <!--begin::Radio-->
                <span class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                    <input class="form-check-input" type="radio" name="{{$name}}" value="{{$value}}"
                        {{$value == old($name, 1) ? 'checked' : ''}}
                    >
                </span>
                <!--end::Radio-->
                <!--begin::Info-->
                <span class="ms-5">
                    <span class="fs-4 fw-bold text-gray-800 d-block">{{$option}}</span>
                </span>
                <!--end::Info-->
            </label>
            <!--end::Option-->
        </div>
    @endforeach
</div>

@isset($js)
    <div class="invalid-feedback" name="{{$name}}"></div>
@endisset

@error($name)
    <div class="invalid-feedback">{{$message}}</div>
@enderror