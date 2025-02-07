@push('css')
    <link href="/assets/css/custom/include/app/options.css" rel="stylesheet" type="text/css"/>
@endpush

@php
    $activeService = old('services', isset($app['services']) ? $app['services'] : ['push']);
    $activeChannel = old('channels', isset($app['channels']) ? $app['channels'] : []);
    $serviceErrorMessage = '';
    $channelErrorMessage = '';

    if ($errors->has('services')) {
        $serviceError = true;
        $serviceErrorMessage = $errors->first('services');
    }

    if ($errors->has('channels')) {
        $channelError = true;
        $channelErrorMessage = $errors->first('channels');
    }
@endphp

<div class="mb-10">
    {{-- Service --}}
    <div class="row">
        <div class="col-4 col-md-2">
            <label for="services" class="form-label required">Service</label>
        </div>
        <div class="col-8">
            @foreach ($services as $key => $service)
                @php
                    $serviceName = ucfirst($service);
                    $isActive = in_array($service, $activeService);

                    if (! $errors->has('services')) {
                        $serviceError = false;

                        if ($errors->has('services.' . $key)) {
                            $serviceError = true;
                            $serviceErrorMessage = $errors->first('services.' . $key);
                        }
                    }
                @endphp

                <input type="hidden"
                    name="services[{{$key}}]"
                    id="{{$service}}"
                    value="{{$service}}"
                    {{$isActive ? '' : 'disabled'}}
                >

                <button type="button"
                    data-type="{{$service}}"
                    class="chip {{$isActive ? 'active' : ''}} {{$serviceError ? 'is-invalid' : ''}}">
                    {{$serviceName}} Service
                </button>
            @endforeach

            @if (! empty($serviceErrorMessage))
                <div class="invalid-feedback">{{$serviceErrorMessage}}</div>
            @endif
        </div>
    </div>
</div>

<div class="mb-10">
    {{-- Channel --}}
    <div class="row">
        <div class="col-4 col-md-2">
            <label class="form-label required">Channel</label>
        </div>
        <div class="col-8">
            <div class="row">
                @foreach ($services as $key => $service)
                    @php
                        $isActive = in_array($service, $activeService);

                        if (! $errors->has('channels')) {
                            $channelError = false;

                            if ($errors->has('channels.' . $key)) {
                                $channelError = true;
                                $channelErrorMessage = $errors->first('channels.' . $key);
                            }
                        }
                    @endphp

                    <div class="col-12 col-md-4 mb-3" id="{{$service}}-channels" {{$isActive ? '' : 'hidden'}}>
                        <select data-control="select2"
                            class="form-select {{$channelError ? 'is-invalid' : ''}}"
                            name="channels[{{$service}}]"
                            {{$isActive ? '' : 'disabled'}}
                        >
                            @foreach ($channels[$service] as $channelGroupName => $channelGroup)
                                <optgroup label="{{$channelGroupName}}">
                                    @foreach ($channelGroup as $channel)
                                        @php
                                            $isChannelActive = in_array($channel['id'], $activeChannel);
                                        @endphp

                                        <option
                                            value="{{$channel['id']}}"
                                            {{$isChannelActive ? 'selected' : ''}}
                                        >{{$channel['name']}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        @if (! empty($channelErrorMessage))
                            <div class="invalid-feedback">{{$channelErrorMessage}}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="/assets/js/custom/include/app/options.js"></script>
@endpush