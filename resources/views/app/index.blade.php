@extends('layout.default')

@push('css')
    <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/custom/app/index.css" rel="stylesheet" type="text/css"/>
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

                <div class="card-title">App List</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('app.create')}}" class="btn btn-flex btn-primary">
                            <i class="bi bi-app fs-1"></i>
                            Create New App
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-hover table-row-gray-300 gy-7" id="app-list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th>Name</th>
                                <th>Notification</th>
                                <th>Channel</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="action-div" hidden>
                    <button class="btn btn-danger dropdown-toggle"
                        data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end"
                        data-kt-menu-overflow="true"
                        id="action-btn"
                    >Action</button>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded border border-gray-300 menu-state-bg-light-primary fw-semibold w-100px mt-1"
                        data-kt-menu="true"
                    >
                        <div class="menu-item px-3">
                            <a href="{{route('app.show', ':id')}}" class="btn btn-active-light-danger w-100" id="detail-btn">
                                Details
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <button class="btn btn-active-light-danger w-100" id="delete-btn">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let app_data_url = '{!! route('app.getData') !!}';
        let app_detail_url = '{!! route('app.show', 'id') !!}';
    </script>
    <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="/assets/js/custom/app/index.js"></script>
@endpush