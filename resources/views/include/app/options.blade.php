@push('css')
    <link href="/assets/css/custom/include/app/options.css" rel="stylesheet" type="text/css"/>
@endpush

{{-- Service row --}}
<div class="mb-10">
    <div class="row">
        <div class="col-2">
            <label class="form-label required">Services</label>
        </div>

        {{-- TODO: service chips and channels select should be in loop and
        the resrouce data should be in the array structure with the chosen
        service and channels. [email, push] and [mailtrap, smtp, amazon ses, pusher, rabbitmq]. --}}

        {{-- service chips row --}}
        <div class="col-10">
            {{-- service chips --}}
            <button type="button"
                class="chip active @error('scope[0][service]"') is-invalid @enderror"
                data-type="email"
            >Email Service</button>
            @error('scope[0][service]')
                <div class="invalid-feedback">{{$message}}</div>
            @enderror

            <button type="button"
                class="chip @error('scope[1][service]') is-invalid @enderror"
                data-type="push"
            >Push Service</button>
            @error('scope[1][service]')
                <div class="invalid-feedback">{{$message}}</div>
            @enderror
            {{-- service chips --}}

            {{-- service hidden inputs --}}
            <input type="hidden" name="scope[0][service]" id="email" value="email">
            <input type="hidden" name="scope[1][service]" id="push" value="push" disabled>
            {{-- service hidden inputs --}}
        </div>
        {{-- service chips row --}}
    </div>
</div>
{{-- Service row --}}

{{-- Channels row --}}
<div class="mb-10">
    <div class="row">
        <div class="col-2">
            <label class="form-label required">Channels</label>
        </div>

        <div class="col-10">
            <div class="row" id="channels">
                {{-- channels select --}}
                <div class="col-3 mb-3" id="email-channels">
                    <select name="scope[0][channel]"
                        class="form-select @error('scope[0][channel]"') is-invalid @enderror"
                    >
                        <option value="1">Mailtrap</option>
                        <option value="2">SMTP</option>
                        <option value="3">Amazon SES</option>
                    </select>
                    @error('scope[0][channel]')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-3 mb-3" id="push-channels" hidden>
                    <select name="scope[1][channel]"
                        class="form-select @error('scope[1][channel]') is-invalid @enderror"
                        disabled
                    >
                        <option value="1">Pusher</option>
                        <option value="2">RabbitMQ</option>
                    </select>
                    @error('scope[1][channel]')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                {{-- channels select --}}
            </div>
        </div>
    </div>
</div>
{{-- Channels row --}}

@push('js')
    <script src="/assets/js/custom/include/app/options.js"></script>
@endpush