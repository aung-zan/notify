@extends('layout.default')

@section('content')
    <div class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Test Pusher Channel</div>

                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('push.index')}}" class="btn btn-outline btn-outline-secondary me-2">
                            <i class="bi bi-x-lg fs-3"></i>
                            Cancel
                        </a>
                        <button type="button" form="store" class="btn btn-outline btn-outline-primary" id="send-button">
                            <i class="bi bi-send-plus fs-2 mt-1"></i>
                            Send
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" id="channel-id" value="{{$channel['id']}}">

                <div class="mb-10">
                    <div class="row">
                        <div class="col-2">
                            <lable class="form-label">Channel Name</lable>
                        </div>

                        <div class="col-2">
                            <lable class="form-label">{{$channel['name']}}</lable>
                        </div>
                    </div>
                </div>

                <div class="mb-10">
                    <div class="row">
                        <div class="col-2">
                            <lable class="form-label">Message</lable>
                        </div>

                        <div class="col-7">
                            <textarea class="form-control"
                                cols="30"
                                rows="10"
                                placeholder="Enter the message to send."
                                id="message"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let test_url = '{!! route('push.test', 'id') !!}';
        let config = '{!! json_encode($channel) !!}';
    </script>
    <script src="/assets/plugins/custom/pusher/pusher.min.js"></script>
    <script src="/assets/js/custom/push/providers/pusher.js"></script>
@endpush