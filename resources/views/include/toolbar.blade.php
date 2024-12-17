<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6 h-100px">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                {{$title}}
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="index.html" class="text-muted text-hover-primary">Home</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-500 w-5px h-2px"></span>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">Dashboards</li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->

        @php
            $notificationColor = session('flashMessage')['color'] ?? 'bg-success';
        @endphp

        @session('flashMessage')
            <input type="hidden" id="flash-message" value="{{session('flashMessage')['message']}}">
        @endsession

        <!--begin::Toast-->
        <div class="position-fixed end-0 me-9 z-index-3">
            <div id="notification_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header {{$notificationColor}}">
                    <i class="bi bi-bell fs-3 me-2 alert-text-color"></i>
                    <strong class="me-auto alert-text-color">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body h-40px d-flex align-items-center {{$notificationColor}}">
                    <span id="toast-message" class="fs-5 alert-text-color"></span>
                </div>
            </div>
        </div>
        <!--end::Toast-->
    </div>
    <!--end::Toolbar container-->
</div>

@push('js')
    <script src="/assets/js/custom/include/toolbar.js"></script>
@endpush