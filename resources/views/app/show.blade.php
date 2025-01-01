@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    App Details
                    <span class="badge badge-outline badge-danger ms-3">Active</span>
                </div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('app.index')}}" class="btn btn-outline btn-outline-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <a href="{{route('app.edit', 1)}}" class="btn btn-outline btn-outline-primary">
                            <i class="bi bi-cloud-download fs-2 mt-1"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-10">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">App Name</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">{{$app['name']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Notification</label>
                            </div>

                            <div class="col-8">
                                <span class="badge badge-outline badge-primary">{{$app['notifications']}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-10">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">App Description</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">{{$app['description']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Channels</label>
                            </div>

                            <div class="col-8">
                                <span class="badge badge-outline badge-success">{{$app['channels']}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-10">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Created At</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">{{$app['created_at']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Updated At</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">{{$app['updated_at']}}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-10">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Token</label>
                            </div>

                            <div class="col-8 d-flex align-items-start">
                                <div class="row">
                                    <div class="col-9">
                                        <label class="form-label text-break">{{$app['token']}}</label>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-icon pb-8 copy">
                                            <i class="bi bi-copy fs-3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Refresh Token</label>
                            </div>

                            <div class="col-8 d-flex align-items-start">
                                <div class="row">
                                    <div class="col-9">
                                        <label class="form-label text-break">{{$app['refresh_token']}}</label>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-icon pb-8 copy">
                                            <i class="bi bi-copy fs-3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="/assets/js/custom/app/show.js"></script>
@endpush