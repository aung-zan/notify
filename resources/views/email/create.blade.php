@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create New Channel</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('email.index')}}" class="btn btn-outline btn-outline-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <button type="submit" form="store" class="btn btn-outline btn-outline-primary">
                            <i class="bi bi-cloud-upload fs-2 mt-1"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{route('email.store')}}" method="POST" id="store">
                    @csrf

                    @include('include.option')

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-4 col-md-2">
                                <label class="required form-label">Channel Name</label>
                            </div>

                            <div class="col-8">
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Eg. Testing Channel"
                                    value="{{old('name', '')}}"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-4 col-md-2">
                                <label class="required form-label">Enter credentials</label>
                            </div>

                            <div class="col-8">
                                <textarea name="credentials"
                                    cols="30"
                                    rows="10"
                                    class="form-control @error('credentials') is-invalid @enderror"
                                    placeholder="Copy credentials and paste here."
                                >{{old('credentials', '')}}</textarea>
                                @error('credentials')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection