@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">App Details</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('app.index')}}" class="btn btn-flex btn-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <a href="{{route('app.edit', 1)}}" class="btn btn-flex btn-primary">
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
                                <label class="form-label">Testing App</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Notification</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">Email, Push</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Token</label>
                            </div>

                            <div class="col-8 d-flex align-items-start">
                                <label class="form-label">jk076590ygghgh324vd33</label>
                                <button class="btn btn-icon pb-8 copy">
                                    <i class="bi bi-copy fs-3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Channels</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">SMTP, Pusher</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-10">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Refresh Token</label>
                            </div>

                            <div class="col-8 d-flex align-items-start">
                                <label class="form-label">jk076590ygghgh324re33</label>
                                <button class="btn btn-icon pb-8 copy">
                                    <i class="bi bi-copy fs-3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label text-muted">Created At</label>
                            </div>

                            <div class="col-8">
                                <label class="form-label">23 Dec, 2024</label>
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