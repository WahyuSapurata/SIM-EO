@php
    $path = explode('/', Request::path());
    $role = auth()->user()->role;

    $dashboardRoutes = [
        'admin' => 'admin.dashboard-admin',
        'procurement' => 'procurement.dashboard-procurement',
        'finance' => 'finance.dashboard-finance',
        'direktur' => 'direktur.dashboard-direktur',
        'pajak' => 'pajak.dashboard-pajak',
    ];

    $isActive = in_array($role, array_keys($dashboardRoutes)) && $path[1] === 'dashboard-' . $role;
    $activeColor = $isActive ? 'color: #F4BE2A' : 'color: #FFFFFF';
@endphp

<div class="aside-menu bg-primary flex-column-fluid">
    <!--begin::Aside Menu-->
    <div class="hover-scroll-overlay-y mb-5 mb-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
        data-kt-scroll-offset="0">
        <script>
            // Ambil elemen menu menggunakan JavaScript
            var menu = document.getElementById('kt_aside_menu_wrapper');

            // Set tinggi maksimum dan penanganan overflow menggunakan JavaScript
            if (menu) {
                menu.style.maxHeight = '88vh'; // Set tinggi maksimum
            }
        </script>
        <!--begin::Menu-->
        <div class="menu menu-column mt-2 menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="kt_aside_menu" data-kt-menu="true" style="gap: 3px;">

            @if ($role === 'procurement' || $role === 'admin' || $role === 'finance' || $role === 'direktur')
                <div class="menu-item">
                    <a class="menu-link {{ $isActive ? 'active' : ($module = 'Persetujun PO') }}"
                        href="{{ route($dashboardRoutes[$role]) }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $isActive ? url('admin/assets/media/icons/aside/dashboardact.svg') : url('admin/assets/media/icons/aside/dashboarddef.svg') }}"
                                    alt="">
                            </span>
                        </span>
                        <span class="menu-title" style="{{ $activeColor }}">Dashboard</span>
                    </a>
                </div>
            @endif

            @if ($role === 'procurement')
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'penjualan' ? 'active' : '' }}"
                        href="{{ route('procurement.penjualan') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'penjualan' ? url('admin/assets/media/icons/aside/penjualanact.svg') : url('/admin/assets/media/icons/aside/penjualandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'penjualan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Budget
                            Client</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'po' ? 'active' : '' }}" href="{{ route('procurement.po') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'po' ? url('admin/assets/media/icons/aside/pembelianact.svg') : url('/admin/assets/media/icons/aside/pembeliandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Real Cost</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'persetujuanpo-prc' ? 'active' : '' }}"
                        href="{{ route('procurement.persetujuanpo-prc') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'persetujuanpo-prc' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'persetujuanpo-prc' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                            PO</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'pesetujuannonvendor-prc' ? 'active' : '' }}"
                        href="{{ route('procurement.pesetujuannonvendor-prc') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'pesetujuannonvendor-prc' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'pesetujuannonvendor-prc' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                            PO Non Vendor</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'master-data' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'master-data' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'master-data' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'master-data' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Master
                            Data</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'datavendor' ? 'active' : '' }}"
                                href="{{ route('admin.datavendor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'datavendor' ? url('admin/assets/media/icons/aside/datavendoract.svg') : url('/admin/assets/media/icons/aside/datavendordef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'datavendor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Vendor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu sub-->

                </div>
            @endif

            @if ($role === 'admin')
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'penjualan' ? 'active' : '' }}"
                        href="{{ route('procurement.penjualan') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'penjualan' ? url('admin/assets/media/icons/aside/penjualanact.svg') : url('/admin/assets/media/icons/aside/penjualandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'penjualan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Budget
                            Client</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'po' ? 'active' : '' }}" href="{{ route('procurement.po') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'po' ? url('admin/assets/media/icons/aside/pembelianact.svg') : url('/admin/assets/media/icons/aside/pembeliandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Real Cost</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-invoice' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-invoice' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-invoice' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Data
                            Invoice</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'invoice' ? 'active' : '' }}"
                                href="{{ route('admin.invoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'invoice' ? url('admin/assets/media/icons/aside/invoiceact.svg') : url('/admin/assets/media/icons/aside/invoicedef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuaninvoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'master-data' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'master-data' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'master-data' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'master-data' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Master
                            Data</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'databank' ? 'active' : '' }}"
                                href="{{ route('admin.databank') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'databank' ? url('admin/assets/media/icons/aside/databankact.svg') : url('/admin/assets/media/icons/aside/databankdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'databank' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Bank</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                    </div>
                    <!--end::Menu sub-->

                </div>
            @endif

            @if ($role === 'finance')
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'penjualan' ? 'active' : '' }}"
                        href="{{ route('procurement.penjualan') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'penjualan' ? url('admin/assets/media/icons/aside/penjualanact.svg') : url('/admin/assets/media/icons/aside/penjualandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'penjualan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Budget
                            Client</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'po' ? 'active' : '' }}"
                        href="{{ route('procurement.po') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'po' ? url('admin/assets/media/icons/aside/pembelianact.svg') : url('/admin/assets/media/icons/aside/pembeliandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Real Cost</span>
                    </a>
                </div>
                <!--end::Menu item-->


                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'persetujuan-po' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'persetujuan-po' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'persetujuan-po' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'persetujuan-po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                            PO</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuanpo' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuanpo') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuanpo' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuanpo' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    PO</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? 'active' : '' }}"
                                href="{{ route('admin.pesetujuannonvendor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    PO Non Vendor</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Menu item-->
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-invoice' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-invoice' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-invoice' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Data
                            Invoice</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'invoice' ? 'active' : '' }}"
                                href="{{ route('admin.invoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'invoice' ? url('admin/assets/media/icons/aside/invoiceact.svg') : url('/admin/assets/media/icons/aside/invoicedef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuaninvoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'Utang-piutang' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'Utang-piutang' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'Utang-piutang' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'Utang-piutang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Utang
                            Piutang</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'utang' ? 'active' : '' }}"
                                href="{{ route('admin.utang') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'utang' ? url('admin/assets/media/icons/aside/utangact.svg') : url('/admin/assets/media/icons/aside/utangdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'utang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Utang</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'piutang' ? 'active' : '' }}"
                                href="{{ route('admin.piutang') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'piutang' ? url('admin/assets/media/icons/aside/piutangact.svg') : url('/admin/assets/media/icons/aside/piutangdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'piutang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Piutang</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-operasional' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-operasional' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-operasional' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-operasional' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Data
                            Operasional Kantor</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'operasionalkantor' ? 'active' : '' }}"
                                href="{{ route('admin.operasionalkantor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'operasionalkantor' ? url('admin/assets/media/icons/aside/pajakact.svg') : url('/admin/assets/media/icons/aside/pajakdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'operasionalkantor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Operasional
                                    Kantor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuanoperasionalkantor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    Operasional
                                    Kantor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-laporan' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-laporan' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-laporan' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-laporan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'laporan' ? 'active' : '' }}"
                                href="{{ route('admin.laporan') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'laporan' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'laporan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Arus
                                    Kas</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'laporan-fee' ? 'active' : '' }}"
                                href="{{ route('admin.laporan-fee') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'laporan-fee' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'laporan-fee' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan
                                    Event</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>
            @endif

            @if ($role === 'direktur')
                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'penjualan' ? 'active' : '' }}"
                        href="{{ route('procurement.penjualan') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'penjualan' ? url('admin/assets/media/icons/aside/penjualanact.svg') : url('/admin/assets/media/icons/aside/penjualandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'penjualan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Budget
                            Client</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'po' ? 'active' : '' }}"
                        href="{{ route('procurement.po') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'po' ? url('admin/assets/media/icons/aside/pembelianact.svg') : url('/admin/assets/media/icons/aside/pembeliandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Real Cost</span>
                    </a>
                </div>
                <!--end::Menu item-->


                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'persetujuan-po' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'persetujuan-po' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'persetujuan-po' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'persetujuan-po' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                            PO</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuanpo' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuanpo') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuanpo' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuanpo' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    PO</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? 'active' : '' }}"
                                href="{{ route('admin.pesetujuannonvendor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'pesetujuannonvendor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    PO Non Vendor</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Menu item-->
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-invoice' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-invoice' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-invoice' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Data
                            Invoice</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'invoice' ? 'active' : '' }}"
                                href="{{ route('admin.invoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'invoice' ? url('admin/assets/media/icons/aside/invoiceact.svg') : url('/admin/assets/media/icons/aside/invoicedef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'invoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuaninvoice') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuaninvoice' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    Invoice</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'Utang-piutang' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'Utang-piutang' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'Utang-piutang' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'Utang-piutang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Utang
                            Piutang</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'utang' ? 'active' : '' }}"
                                href="{{ route('admin.utang') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'utang' ? url('admin/assets/media/icons/aside/utangact.svg') : url('/admin/assets/media/icons/aside/utangdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'utang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Utang</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'piutang' ? 'active' : '' }}"
                                href="{{ route('admin.piutang') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'piutang' ? url('admin/assets/media/icons/aside/piutangact.svg') : url('/admin/assets/media/icons/aside/piutangdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'piutang' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Piutang</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-operasional' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-operasional' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-operasional' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-operasional' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Data
                            Operasional Kantor</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'operasionalkantor' ? 'active' : '' }}"
                                href="{{ route('admin.operasionalkantor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'operasionalkantor' ? url('admin/assets/media/icons/aside/pajakact.svg') : url('/admin/assets/media/icons/aside/pajakdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'operasionalkantor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Operasional
                                    Kantor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? 'active' : '' }}"
                                href="{{ route('admin.persetujuanoperasionalkantor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? url('admin/assets/media/icons/aside/persetujuanpoact.svg') : url('/admin/assets/media/icons/aside/persetujuanpodef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'persetujuanoperasionalkantor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Persetujuan
                                    Operasional
                                    Kantor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'data-laporan' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'data-laporan' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'data-laporan' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'data-laporan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'laporan' ? 'active' : '' }}"
                                href="{{ route('admin.laporan') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'laporan' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'laporan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Arus
                                    Kas</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'laporan-fee' ? 'active' : '' }}"
                                href="{{ route('admin.laporan-fee') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'laporan-fee' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'laporan-fee' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan
                                    Event</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>

                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'master-data' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'master-data' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'master-data' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'master-data' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Master
                            Data</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'datauser' ? 'active' : '' }}"
                                href="{{ route('admin.datauser') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'datauser' ? url('admin/assets/media/icons/aside/dataguruact.svg') : url('/admin/assets/media/icons/aside/datagurudef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'datauser' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    User</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'dataclient' ? 'active' : '' }}"
                                href="{{ route('admin.dataclient') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'dataclient' ? url('admin/assets/media/icons/aside/dataclientact.svg') : url('/admin/assets/media/icons/aside/dataclientdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'dataclient' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Client</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'datavendor' ? 'active' : '' }}"
                                href="{{ route('admin.datavendor') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'datavendor' ? url('admin/assets/media/icons/aside/datavendoract.svg') : url('/admin/assets/media/icons/aside/datavendordef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'datavendor' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Vendor</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'datapajak' ? 'active' : '' }}"
                                href="{{ route('admin.datapajak') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'datapajak' ? url('admin/assets/media/icons/aside/pajakact.svg') : url('/admin/assets/media/icons/aside/pajakdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'datapajak' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Pajak</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'databank' ? 'active' : '' }}"
                                href="{{ route('admin.databank') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'databank' ? url('admin/assets/media/icons/aside/databankact.svg') : url('/admin/assets/media/icons/aside/databankdef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'databank' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Bank</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'kategori' ? 'active' : '' }}"
                                href="{{ route('admin.kategori') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'kategori' ? url('admin/assets/media/icons/aside/kategoriact.svg') : url('/admin/assets/media/icons/aside/kategoridef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'kategori' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                                    Kategori</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu sub-->

                </div>
            @endif

            @if ($role === 'pajak')
                <div class="menu-item menu-link-indention menu-accordion {{ $path[1] == 'laporan' ? 'show' : '' }}"
                    data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3 {{ $path[1] == 'laporan' ? 'active' : '' }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] == 'laporan' ? url('admin/assets/media/icons/aside/masterdataact.svg') : url('/admin/assets/media/icons/aside/masterdatadef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] == 'laporan' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan
                            Pajak</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub gap-2 menu-sub-accordion my-2">
                        <!--begin::Menu item-->
                        {{-- <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'laporan-pajak' ? 'active' : '' }}"
                                href="{{ route('pajak.laporan-pajak') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'laporan-pajak' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'laporan-pajak' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Laporan</span>
                            </a>
                        </div> --}}
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'faktur-keluar' ? 'active' : '' }}"
                                href="{{ route('pajak.faktur-keluar') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'faktur-keluar' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'faktur-keluar' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Faktur
                                    Keluar</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'faktur-masuk' ? 'active' : '' }}"
                                href="{{ route('pajak.faktur-masuk') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'faktur-masuk' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'faktur-masuk' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Faktur
                                    Masuk</span>
                            </a>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu item-->
                        <div class="menu-item pe-0">
                            <a class="menu-link {{ isset($path[2]) && $path[2] === 'pemotongan-pajak' ? 'active' : '' }}"
                                href="{{ route('pajak.pemotongan-pajak') }}">
                                <span class="menu-icon">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <img src="{{ isset($path[2]) && $path[2] === 'pemotongan-pajak' ? url('admin/assets/media/icons/aside/laporanact.svg') : url('/admin/assets/media/icons/aside/laporandef.svg') }}"
                                            alt="">
                                    </span>
                                    <!--end::Svg Icon-->
                                </span>
                                <span class="menu-title"
                                    style="{{ isset($path[2]) && $path[2] === 'pemotongan-pajak' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Pemotongan
                                    Pajak</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                </div>
            @endif

            <div class="menu-item">
                <a class="menu-link  {{ $path[1] === 'ubahpassword' ? 'active' : '' }}"
                    href="{{ route('admin.ubahpassword') }}">
                    <span class="menu-icon">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                        <span class="svg-icon svg-icon-2">
                            <img src="{{ $path[1] === 'ubahpassword' ? url('admin/assets/media/icons/aside/ubahpasswordact.svg') : url('/admin/assets/media/icons/aside/ubahpassworddef.svg') }}"
                                alt="">
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title"
                        style="{{ $path[1] === 'ubahpassword' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Ubah
                        Password</span>
                </a>
            </div>

        </div>
        <!--end::Menu-->
    </div>
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            // $(".menu-link").hover(function(){
            //     $(this).css("background", "#282EAD");
            // }, function(){
            //     $(this).css("background", "none");
            // });
        });
    </script>
@endsection
