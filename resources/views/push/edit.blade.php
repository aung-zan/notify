@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit a channel</div>

                <div class="card-toolbar">
                    <div class="d-flex jstify-content-end">
                        <a href="{{route('push.index')}}" class="btn btn-outline btn-outline-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <button type="submit" form="update" class="btn btn-outline btn-outline-primary">
                            <i class="bi bi-cloud-upload fs-2 mt-1"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{route('push.update', $channel['id'])}}" method="post" id="update">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-2">
                                <lable class="form-label">Provider</lable>
                            </div>

                            <div class="col-2">
                                <lable class="form-label disabled">{{$channel['provider_name']}}</lable>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-2">
                                <lable class="form-label">Name</lable>
                            </div>

                            <div class="col-7">
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Eg. Testing Channel"
                                    value="{{old('name', $channel['name'])}}"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-2">
                                <lable class="form-label">Credentials</lable>
                            </div>

                            <div class="col-7">
                                <button type="button" class="btn btn-outline btn-outline-danger" id="edit-credentials">Make changes the credentials.</button>

                                <textarea name="credentials"
                                    cols="30"
                                    rows="10"
                                    class="form-control @error('credentials') is-invalid @enderror mb-2"
                                    placeholder="Copy credentials and paste here."
                                    id="credentials"
                                    hidden
                                    disabled
                                >{{old('credentials', $channel['credentials'])}}</textarea>
                                @error('credentials')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror

                                <button type="button" class="btn btn-outline btn-outline-secondary" id="cancel" hidden>Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="/assets/js/custom/push/edit.js"></script>
@endpush