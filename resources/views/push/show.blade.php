@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Channel Details</div>

                <div class="card-toolbar">
                    <div class="d-flex jstify-content-end">
                        <a href="{{route('push.index')}}" class="btn btn-flex btn-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <a href="{{route('push.edit', $channel['id'])}}" class="btn btn-flex btn-primary">
                            <i class="bi bi-cloud-download fs-2 mt-1"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-10">
                    <div class="row">
                        <div class="col-2">
                            <lable class="form-label">Provider</lable>
                        </div>

                        <div class="col-2">
                            <lable class="form-label">{{$channel['provider']}}</lable>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <div class="row">
                        <div class="col-2">
                            <lable class="form-label">Name</lable>
                        </div>

                        <div class="col-2">
                            <lable class="form-label">{{$channel['name']}}</lable>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <div class="row">
                        <div class="col-2">
                            <lable class="form-label">Credentials</lable>
                        </div>

                        <div class="col-2">
                            @foreach ($channel['credentials'] as $key => $value)
                                <lable class="form-label">{{$key}}</lable> =
                                <lable class="form-label">{{$value}}</lable>
                                <br />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection