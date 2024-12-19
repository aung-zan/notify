<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="index.html">
            <img alt="Logo" src="/assets/media/logos/default-dark.svg" class="h-30px app-sidebar-logo-default" />
        </a>
        <!--end::Logo image-->
    </div>
    <!--end::Logo-->

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="hover-scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    @php
                        $menu = config('constant.sidebar');
                    @endphp

                    <!--begin:Menu items-->
                    @foreach ($menu as $item)
                        @php
                            $isActive = '';

                            if ($item['group'] === $groupRouteName) {
                                $isActive = 'active';
                            }
                        @endphp

                        <div class="menu-item pt-5">
                            <a href="{{ route($item['route']) }}" class="menu-link {{$isActive}}">
                                <span class="menu-icon">
                                    <i class="ki-outline {{$item['icon']}} fs-1"></i>
                                </span>
                                <span class="menu-title">{{$item['display_name']}}</span>
                            </a>
                        </div>
                    @endforeach
                    <!--end:Menu items-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
</div>