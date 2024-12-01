<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Default</h1>
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

        @session('statusInfo')
            <!--begin::Toast-->
            <div class="position-fixed end-0 me-6 p-3 z-index-3">
                <div id="kt_docs_toast_toggle" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-show="true">
                    <div class="toast-header">
                        <label class="me-auto">Email Status</label>
                    </div>

                    <div class="toast-body d-flex align-items-center">
                        <i class="ki-outline {{session('statusInfo')['icon']}} text-{{session('statusInfo')['color']}} fs-2 me-3"></i>
                        <label class="me-auto fs-5 text-{{session('statusInfo')['color']}}">
                            {{session('statusInfo')['message']}}
                        </label>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <!--end::Toast-->
        @endsession

        <!--begin::Notification Toast-->
        <div class="position-fixed end-0 me-6 p-3 z-index-3">
            <div id="noti-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-show="true">
                <div class="toast-header">
                    <label class="me-auto">Push Notification</label>
                </div>

                <div class="toast-body d-flex align-items-center">
                    <i class="ki-outline ki-notification-status text-primary fs-2 me-3"></i>
                    <label class="me-auto fs-5 text-primary" id="noti-message"></label>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!--end::Notification Toast-->
    </div>
    <!--end::Toolbar container-->
</div>

@push('js')
    <script src="/assets/js/custom/include/toolbar.js"></script>
@endpush