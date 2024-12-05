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

        @session('flashMessage')
            <!--begin::Alert-->
            <div class="alert alert-dismissible bg-{{session('flashMessage')['color']}} d-flex flex-column flex-sm-row p-5 mb-0">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <!--begin::Title-->
                    <h4 class="fw-semibold alert-text-color">
                        <i class="bi bi-bell fs-3 me-2 alert-text-color"></i>
                        Notification
                    </h4>
                    <!--end::Title-->

                    <!--begin::Content-->
                    <span class="alert-text-color">{{session('flashMessage')['message']}}</span>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Close-->
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="bi bi-x fs-1 alert-text-color"></i>
                </button>
                <!--end::Close-->
            </div>
            <!--end::Alert-->
        @endsession
    </div>
    <!--end::Toolbar container-->
</div>