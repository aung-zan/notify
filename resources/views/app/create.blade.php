@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Create New App</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('app.index')}}" class="btn btn-flex btn-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <button type="submit" form="store" class="btn btn-flex btn-primary">
                            <i class="bi bi-cloud-upload fs-2 mt-1"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{route('app.store')}}" method="post" id="store">
                    @csrf

                    <div class="mb-10">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label required">App Name</label>
                            </div>

                            <div class="col-7">
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Eg. Testing App"
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
                            <div class="col-2">
                                <label class="form-label">App Description</label>
                            </div>

                            <div class="col-7">
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    cols="30"
                                    rows="5"
                                    placeholder="Eg. This is a testing app"
                                ></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @include('include.app.options')
                </form>
            </div>
        </div>
    </div>
@endsection

