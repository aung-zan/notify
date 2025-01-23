@push('css')
    <link href="/assets/css/custom/include/app/options.css" rel="stylesheet" type="text/css"/>
@endpush

{{-- TODO: refactor with [label, label] and [value, value].
Current is [label, value] and [label, value]. --}}

@php
    $defaultActive = ['push'];
    $serviceErrorMessage = '';

    if ($errors->has('scopes')) {
        $channelError = $serviceError = true;
        $channelErrorMessage = $serviceErrorMessage = $errors->first('scopes');
    }
@endphp

{{-- Service row --}}
<div class="mb-10">
    <div class="row">
        <div class="col-2">
            <label class="form-label required">Services</label>
        </div>

        <div class="col-10">
            @foreach ($services as $key => $service)
                @php
                    $serviceName = ucfirst($service);
                    $isActive = in_array($service, $defaultActive);

                    if (! $errors->has('scopes')) {
                        $serviceError = false;

                        if ($errors->has('scopes.' . $key . '.service')) {
                            $serviceError = true;
                            $serviceErrorMessage = $errors->first('scopes.' . $key . '.service');
                        }
                    }
                @endphp

                <input type="hidden"
                    name="scopes[{{$key}}][service]"
                    id="{{$service}}"
                    value={{$service}}
                    {{$isActive ? '' : 'disabled'}}
                >

                <button type="button"
                    class="chip {{$isActive ? 'active' : ''}} {{$serviceError ? 'is-invalid' : ''}}"
                    data-type="{{$service}}"
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
                @foreach ($services as $serviceName => $service)
                    @php
                        $isSelected = in_array($service, $defaultActive);

                        if (! $errors->has('scopes')) {
                            $channelError = false;

                            if ($errors->has('scopes.' . $service . '.channel')) {
                                $channelError = true;
                                $channelErrorMessage = $errors->first('scopes.' . $service . '.channel');
                            }
                        }
                    @endphp

                    <div class="col-3 mb-3" id="{{$service}}-channels" {{$isSelected ? '' : 'hidden'}}>
                        <select name="scopes[{{$service}}][channel]"
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