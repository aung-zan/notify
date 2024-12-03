@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Channel List</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('push.create')}}" class="btn btn-flex btn-primary">
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
                                <th>Channel</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection