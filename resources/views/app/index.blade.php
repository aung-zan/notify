@extends('layout.default')

@push('css')
    <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/custom/app/index.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="col-sm-6 align-items-center justify-content-start" id="dTSearchW" hidden>
                    <div class="input-group w-250px">
                        <input type="text" id="dTSearch" class="form-control border border-gray-300"
                            placeholder="Search" />
                        <button type="button" class="btn btn-icon btn-active-secondary border border-gray-300"
                            id="dTSearchBtn">
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

                <div class="dropdown" hidden>
                    <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="dropdown-item details">Details</a>
                        </li>
                        <li>
                            <button class="dropdown-item delete">Delete</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let app_data_url = '{!! route('app.getData') !!}';
        let app_detail_url = '{!! route('app.show', ['id' => 'id']) !!}';
    </script>
    <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="/assets/js/custom/app/index.js"></script>
@endpush
