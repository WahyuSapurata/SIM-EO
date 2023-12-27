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
{{-- @dd($isActive) --}}
{{-- @dd($path) --}}
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

            <div class="menu-item">
                <a class="menu-link {{ $isActive ? 'active' : '' }}" href="{{ route($dashboardRoutes[$role]) }}">
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <img src="{{ $isActive ? url('admin/assets/media/icons/aside/dashboardact.svg') : url('admin/assets/media/icons/aside/dashboarddef.svg') }}"
                                alt="">
                        </span>
                    </span>
                    <span class="menu-title" style="{{ $activeColor }}">Dashboard</span>
                </a>
            </div>

            @if ($role === 'procurement')
                <!--begin::Menu item-->
                {{-- <div class="menu-item">
                    <a class="menu-link {{ $path[1] === 'dataclient' ? 'active' : '' }}"
                        href="{{ route('procurement.dataclient') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'dataclient' ? url('admin/assets/media/icons/aside/dataclientact.svg') : url('/admin/assets/media/icons/aside/dataclientdef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'dataclient' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Daftar
                            Client</span>
                    </a>
                </div> --}}
                <!--end::Menu item-->

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
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/pembeliandef.svg') }}" alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">PO</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                {{-- <div class="menu-item">
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/pengajuandef.svg') }}" alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">Pengajuan</span>
                    </a>
                </div> --}}
                <!--end::Menu item-->
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
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/pembeliandef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">PO</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/invoicedef.svg') }}" alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">Invoice</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/pajakdef.svg') }}" alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">Pajak</span>
                    </a>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item">
                    <a class="menu-link" href="">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ url('/admin/assets/media/icons/aside/admindef.svg') }}" alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title" style="color: #FFFFFF;">Admin</span>
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
                    </div>
                    <!--end::Menu sub-->

                </div>
            @endif


            {{-- @if ($role === 'admin')
                <div class="menu-item">
                    <a class="menu-link  {{ $path[1] === 'absensi' ? 'active' : '' }}"
                        href="{{ route('admin.absensi') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'absensi' ? url('admin/assets/media/icons/aside/absenact.svg') : url('/admin/assets/media/icons/aside/absendef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'absensi' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Absensi</span>
                    </a>
                </div>
            @endif

            @if ($role === 'admin')
                <div class="menu-item">
                    <a class="menu-link  {{ $path[1] === 'gaji' ? 'active' : '' }}" href="{{ route('admin.gaji') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'gaji' ? url('admin/assets/media/icons/aside/gajiact.svg') : url('/admin/assets/media/icons/aside/gajidef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'gaji' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Gaji</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link  {{ $path[1] === 'rekap' ? 'active' : '' }}"
                        href="{{ route('admin.rekap') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'rekap' ? url('admin/assets/media/icons/aside/rekapact.svg') : url('/admin/assets/media/icons/aside/rekapdef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'rekap' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Rekap</span>
                    </a>
                </div>
            @endif

            @if ($role === 'guru')
                <div class="menu-item">
                    <a class="menu-link  {{ $path[1] === 'absen' ? 'active' : '' }}" href="{{ route('guru.absen') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-2">
                                <img src="{{ $path[1] === 'absen' ? url('admin/assets/media/icons/aside/absenact.svg') : url('/admin/assets/media/icons/aside/absendef.svg') }}"
                                    alt="">
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title"
                            style="{{ $path[1] === 'absen' ? 'color: #F4BE2A' : 'color: #FFFFFF' }}">Absensi</span>
                    </a>
                </div>
            @endif --}}

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
