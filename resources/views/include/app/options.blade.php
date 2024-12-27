@push('css')
    <link href="/assets/css/custom/include/app/options.css" rel="stylesheet" type="text/css"/>
@endpush

@php
    $defaultActive = [2];
    $serviceErrorMessage = '';

    if ($errors->has('scope')) {
        $channelError = $serviceError = true;
        $channelErrorMessage = $serviceErrorMessage = $errors->first('scope');
    }
@endphp

{{-- Service row --}}
<div class="mb-10">
    <div class="row">
        <div class="col-2">
            <label class="form-label required">Services</label>
        </div>

        {{-- TODO: service chips and channels select should be in loop and
        the resrouce data should be in the array structure with the chosen
        service and channels. [email, push] and [mailtrap, smtp, amazon ses, pusher, rabbitmq]. --}}

        <div class="col-10">
            @foreach ($services as $serviceName => $serviceValue)
                @php
                    $serviceType = strtolower($serviceName);
                    $isActive = in_array($serviceValue, $defaultActive);

                    if (! $errors->has('scope')) {
                        $serviceError = false;

                        if ($errors->has('scope.' . $serviceValue . '.service')) {
                            $serviceError = true;
                            $serviceErrorMessage = $errors->first('scope.' . $serviceValue . '.service');
                        }
                    }
                @endphp

                <input type="hidden"
                    name="scope[{{$serviceValue}}][service]"
                    id="{{$serviceType}}"
                    value={{$serviceValue}}
                    {{$isActive ? '' : 'disabled'}}
                >

                <button type="button"
                    class="chip {{$isActive ? 'active' : ''}} {{$serviceError ? 'is-invalid' : ''}}"
                    data-type="{{$serviceType}}"
                >{{$serviceName}} Service</button>
            @endforeach

            @if (! empty($serviceErrorMessage))
                <div class="invalid-feedback">{{$serviceErrorMessage}}</div>
            @endif
        </div>
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
                @foreach ($services as $serviceName => $serviceValue)
                    @php
                        $service = strtolower($serviceName);
                        $isSelected = in_array($serviceValue, $defaultActive);

                        if (! $errors->has('scope')) {
                            $channelError = false;

                            if ($errors->has('scope.' . $serviceValue . '.channel')) {
                                $channelError = true;
                                $channelErrorMessage = $errors->first('scope.' . $serviceValue . '.channel');
                            }
                        }
                    @endphp

                    <div class="col-3 mb-3" id="{{$service}}-channels" {{$isSelected ? '' : 'hidden'}}>
                        <select name="scope[{{$serviceValue}}][channel]"
                            class="form-select {{$channelError ? 'is-invalid' : ''}}"
                            data-control="select2"
                            {{$isSelected ? '' : 'disabled'}}
                        >
                            @foreach ($providers[$service] as $channelGroups => $channels)
                                <optgroup label={{$channelGroups}}>
                                    @foreach ($channels as $channel)
                                        <option value={{$channel['id']}}>{{$channel['name']}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        @if (! empty($channelErrorMessage))
                            <div class="invalid-feedback">{{$channelErrorMessage}}</div>
                        @endif
                    </div>
                @endforeach
                {{-- channels select --}}
            </div>
        </div>
    </div>
</div>
{{-- Channels row --}}

@push('js')
    <script src="/assets/js/custom/include/app/options.js"></script>
@endpush