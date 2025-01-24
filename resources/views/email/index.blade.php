@extends('layout.default')

@push('css')
    <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/custom/email/index.css" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="col-sm-6 align-items-center justify-content-start" id="dTSearchW" hidden>
                    <div class="input-group w-250px">
                        <input type="text" id="dTSearch" class="form-control border border-gray-300" placeholder="Search"/>
                        <button type="button" class="btn btn-icon btn-active-secondary border border-gray-300" id="dTSearchBtn">
                            <i class="ki-outline ki-magnifier fs-3 text-gray-900"></i>
                        </button>
                    </div>
                </div>

                <div class="card-title">Channel List</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('email.create')}}" class="btn btn-outline btn-outline-primary">
                            <i class="bi bi-broadcast-pin fs-1"></i>
                            Create New Channel
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-hover table-row-gray-300 gy-7" id="email-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>Name</th>
                                <th>Provider</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let email_data_url = '{!! route('email.getData') !!}';
        let email_detail_url = '{!! route('email.show', 'id') !!}';
        let email_test_url = '{!! route('push.testPage', 'id') !!}';
    </script>
    <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="/assets/js/custom/email/index.js"></script>
@endpush