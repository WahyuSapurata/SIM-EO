@php
    $role = auth()->user()->role;
    $path = explode('/', Request::path());
@endphp
@extends('layouts.layout')
@section('button')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack gap-2">
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            {{-- <a href="#" data-type="excel" class="btn btn-sm btn-success export">Export Excel</a>
            <a href="#" data-type="pdf" class="btn btn-sm btn-danger export">Cetak Laporan</a> --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_1"
                class="btn btn-sm btn-info d-flex align-items-center gap-1 disabled-link" id="cetakButton">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.00001 1.3335H9.33334L13.3333 5.3335V13.3335C13.3333 13.6871 13.1929 14.0263 12.9428 14.2763C12.6928 14.5264 12.3536 14.6668 12 14.6668H4.00001C3.64638 14.6668 3.30724 14.5264 3.0572 14.2763C2.80715 14.0263 2.66667 13.6871 2.66667 13.3335V2.66683C2.66667 2.31321 2.80715 1.97407 3.0572 1.72402C3.30724 1.47397 3.64638 1.3335 4.00001 1.3335ZM5.468 11.0735C5.822 11.0735 6.126 10.9868 6.33201 10.7935C6.48934 10.6435 6.576 10.4228 6.57667 10.1615C6.57667 9.9015 6.462 9.68083 6.29267 9.54616C6.11467 9.40416 5.85067 9.3335 5.47934 9.3335C5.20742 9.32976 4.93564 9.34804 4.66667 9.38816V12.0122H5.26267V11.0615C5.33076 11.0702 5.39937 11.0742 5.468 11.0735ZM7.64334 12.0402C8.164 12.0402 8.59 11.9295 8.87001 11.6968C9.126 11.4802 9.31201 11.1288 9.31201 10.6202C9.31201 10.1502 9.13867 9.82283 8.862 9.6175C8.606 9.42416 8.278 9.3335 7.77334 9.3335C7.50143 9.33106 7.22974 9.34956 6.96067 9.38883V12.0002C7.11067 12.0202 7.33134 12.0402 7.64334 12.0402ZM10.312 9.84683H11.3333V9.3535H9.708V12.0128H10.312V10.9435H11.2667V10.4542H10.312V9.84683ZM8.66667 6.00016H9.33334H12L8.66667 2.66683V6.00016ZM5.26303 9.81086C5.30569 9.79886 5.38836 9.78687 5.51103 9.78687C5.81103 9.78687 5.98036 9.93353 5.98036 10.1775C5.98036 10.4502 5.78369 10.6115 5.46436 10.6115C5.37703 10.6115 5.31369 10.6082 5.26303 10.5962V9.81086ZM7.56436 9.81886C7.61569 9.80686 7.70236 9.79487 7.83569 9.79487C8.35303 9.79487 8.67636 10.0869 8.67236 10.6402C8.67236 11.2749 8.31769 11.5749 7.77303 11.5709C7.69836 11.5709 7.61569 11.5709 7.56436 11.5589V9.81886Z"
                        fill="white" />
                    <mask id="mask0_198_8791" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="2" y="1"
                        width="12" height="14">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.00001 1.3335H9.33334L13.3333 5.3335V13.3335C13.3333 13.6871 13.1929 14.0263 12.9428 14.2763C12.6928 14.5264 12.3536 14.6668 12 14.6668H4.00001C3.64638 14.6668 3.30724 14.5264 3.0572 14.2763C2.80715 14.0263 2.66667 13.6871 2.66667 13.3335V2.66683C2.66667 2.31321 2.80715 1.97407 3.0572 1.72402C3.30724 1.47397 3.64638 1.3335 4.00001 1.3335ZM5.468 11.0735C5.822 11.0735 6.126 10.9868 6.33201 10.7935C6.48934 10.6435 6.576 10.4228 6.57667 10.1615C6.57667 9.9015 6.462 9.68083 6.29267 9.54616C6.11467 9.40416 5.85067 9.3335 5.47934 9.3335C5.20742 9.32976 4.93564 9.34804 4.66667 9.38816V12.0122H5.26267V11.0615C5.33076 11.0702 5.39937 11.0742 5.468 11.0735ZM7.64334 12.0402C8.164 12.0402 8.59 11.9295 8.87001 11.6968C9.126 11.4802 9.31201 11.1288 9.31201 10.6202C9.31201 10.1502 9.13867 9.82283 8.862 9.6175C8.606 9.42416 8.278 9.3335 7.77334 9.3335C7.50143 9.33106 7.22974 9.34956 6.96067 9.38883V12.0002C7.11067 12.0202 7.33134 12.0402 7.64334 12.0402ZM10.312 9.84683H11.3333V9.3535H9.708V12.0128H10.312V10.9435H11.2667V10.4542H10.312V9.84683ZM8.66667 6.00016H9.33334H12L8.66667 2.66683V6.00016ZM5.26303 9.81086C5.30569 9.79886 5.38836 9.78687 5.51103 9.78687C5.81103 9.78687 5.98036 9.93353 5.98036 10.1775C5.98036 10.4502 5.78369 10.6115 5.46436 10.6115C5.37703 10.6115 5.31369 10.6082 5.26303 10.5962V9.81086ZM7.56436 9.81886C7.61569 9.80686 7.70236 9.79487 7.83569 9.79487C8.35303 9.79487 8.67636 10.0869 8.67236 10.6402C8.67236 11.2749 8.31769 11.5749 7.77303 11.5709C7.69836 11.5709 7.61569 11.5709 7.56436 11.5589V9.81886Z"
                            fill="white" />
                    </mask>
                    <g mask="url(#mask0_198_8791)">
                        <rect width="16" height="16" fill="white" />
                    </g>
                </svg>
                Cetak Invoice Po
            </button>
        </div>
        <!--end::Actions-->
    </div>
@endsection
@section('content')
    <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Buat Invoice Po</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form>

                        <input type="hidden" name="id">
                        <input type="hidden" name="uuid">

                        <div class="mb-10">
                            <label class="form-label">Vendor</label>
                            <select name="vendor" class="form-select" data-control="select2" id="from_select"
                                data-placeholder="Pilih jenis inputan">
                            </select>
                            <small class="text-danger vendor_error"></small>
                        </div>

                        <div class="mb-10">
                            <label class="form-label">Discount</label>
                            <input class="form-control" type="text" name="disc" id="disc">
                            <small class="text-danger disc_error"></small>
                        </div>

                        <div class="mb-10">
                            <label class="form-label">Jatuh Tempo</label>
                            <input class="form-control kt_datepicker_7" type="text" name="tempo" id="tempo">
                            <small class="text-danger tempo_error"></small>
                        </div>

                        <div class="mb-10">
                            <label class="form-label">No Invoice Po</label>
                            <input class="form-control" type="number" name="no_invoice" id="no_invoice">
                            <small class="text-danger no_invoice_error"></small>
                        </div>

                        <div class="separator separator-dashed mt-8 mb-5"></div>
                        <div class="d-flex gap-5">
                            <button type="submit" id="export-excel"
                                class="btn btn-primary btn-sm btn-submit-import d-flex align-items-center"><i
                                    class="bi bi-file-earmark-diff"></i> Simpan</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn mr-2 btn-light btn-sm d-flex align-items-center"
                                style="background-color: #ea443e65; color: #EA443E"><i class="bi bi-trash-fill"
                                    style="color: #EA443E"></i>Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">

                <div class="card">
                    <div class="card-body p-0">
                        <div class="py-5 table-responsive">
                            <table id="kt_table_data"
                                class="table table-striped table-rounded border border-gray-300 table-row-bordered table-row-gray-300">
                                <thead class="text-center">
                                    <tr class="fw-bolder fs-6 text-gray-800">
                                        <th>No</th>
                                        <th>Kegiatan</th>
                                        <th>QTY</th>
                                        <th>Satuan</th>
                                        <th>Freq</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Sub Total</th>
                                        <th>Satuan Real Cost</th>
                                        <th>Total Real Cost</th>
                                        <th>Pajak</th>
                                        <th>Disc</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot class="bg-primary">
                                    <tr class="fw-bolder fs-6 text-gray-800">
                                        <td style="text-align: left !important;" colspan="7">Total</td>
                                        <td style="text-align: left !important;" colspan="2" id="total-subtotal">
                                            Rp 0
                                        </td>
                                        <td style="text-align: left !important;" colspan="4" id="subtotal-realCost">
                                            Rp 0
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('side-form')
    <div id="side_form" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#side_form_button" data-kt-drawer-close="#side_form_close" data-kt-drawer-width="500px">
        <!--begin::Card-->
        <div class="card w-100">
            <!--begin::Card header-->
            <div class="card-header pe-5">
                <!--begin::Title-->
                <div class="card-title">
                    <!--begin::User-->
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 lh-1 title_side_form"></a>
                    </div>
                    <!--end::User-->
                </div>
                <!--end::Title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="side_form_close">
                        <!--begin::Svg Icon | path: icons/duotone/Navigation/Close.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                    fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                    <rect fill="#000000" opacity="0.5"
                                        transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                        x="0" y="7" width="16" height="2" rx="1" />
                                </g>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body hover-scroll-overlay-y">
                <form class="form-data">

                    <input type="hidden" name="id">
                    <input type="hidden" name="uuid">

                    <div class="mb-10">
                        <label class="form-label">Harga Satuan Real Cost</label>
                        <input type="text" id="satuan_real_cost" class="form-control" name="satuan_real_cost">
                        <small class="text-danger satuan_real_cost_error"></small>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Pajak</label>
                        <select name="pajak_po" class="form-select" data-control="select2" id="pajak-select"
                            data-placeholder="Pilih jenis inputan">
                        </select>
                        <small class="text-danger pajak_po_error"></small>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Pajak</label>
                        <select name="pajak_pph" class="form-select" data-control="select2" id="pajak_pph-select"
                            data-placeholder="Pilih jenis inputan">
                        </select>
                        <small class="text-danger pajak_pph_error"></small>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Discount</label>
                        <input type="text" id="disc_item" class="form-control" name="disc_item">
                        <small class="text-danger disc_item_error"></small>
                    </div>

                    <div class="separator separator-dashed mt-8 mb-5"></div>
                    <div class="d-flex gap-5">
                        <button type="submit" class="btn btn-primary btn-sm btn-submit d-flex align-items-center"><i
                                class="bi bi-file-earmark-diff"></i> Simpan</button>
                        <button type="reset" id="side_form_close"
                            class="btn mr-2 btn-light btn-cancel btn-sm d-flex align-items-center"
                            style="background-color: #ea443e65; color: #EA443E"><i class="bi bi-trash-fill"
                                style="color: #EA443E"></i>Batal</button>
                    </div>
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
@endsection
@section('script')
    <script>
        let control = new Control();
        // Deklarasikan variabel formData di luar fungsi
        let formData = new FormData();
        let formDataRealCost = new FormData();

        var currentPath = window.location.pathname;
        var pathParts = currentPath.split('/'); // Membagi path menggunakan karakter '/'
        var lastPart = pathParts[pathParts.length - 1]; // Mengambil elemen terakhir dari array

        $(function() {
            // Format anggaran input using numeral.js
            $('#disc').on('input', function() {
                let value = $(this).val();
                if (value !== "") {
                    value = numeral(value).format('0,0'); // Format to rupiah
                    $(this).val('Rp ' + value);
                }
            });

            $('#satuan_real_cost').on('input', function() {
                let value = $(this).val();
                if (value !== "") {
                    value = numeral(value).format('0,0'); // Format to rupiah
                    $(this).val('Rp ' + value);
                }
            });

            $('#disc_item').on('input', function() {
                let value = $(this).val();
                if (value !== "") {
                    value = numeral(value).format('0,0'); // Format to rupiah
                    $(this).val('Rp ' + value);
                }
            });

            $(".kt_datepicker_7").flatpickr({
                dateFormat: "d-m-Y",
            });
        });

        $(document).on('click', '.button-update', function(e) {
            e.preventDefault();
            let uuid_realCostPo;
            let uuid_realCost;
            $.ajax({
                url: '/procurement/get-realCost',
                method: 'GET',
                async: false, // Pastikan request berjalan secara sinkron
                success: function(res) {
                    if (res.success === true) {
                        $.each(res.data, function(x, y) {
                            uuid_realCostPo = y.uuid_po // Mengambil UUID pertama
                            uuid_realCost = y.uuid // Mengambil UUID pertama
                        })
                    } else {
                        console.error('Gagal mengambil data:', res.message);
                    }
                },
                error: function(error) {
                    console.error('Gagal melakukan permintaan AJAX:', error);
                }
            });

            formDataRealCost.append('uuid_po', $(this).attr('data-uuid'));

            if (uuid_realCostPo === $(this).attr('data-uuid')) {
                let url = '/procurement/show-realCost/' + uuid_realCost;
                control.overlay_form('Update', 'Client', url);
            } else {
                control.overlay_form('Tambah', 'Real Cost');
            }
        })

        $(document).on('keyup', '#search_', function(e) {
            e.preventDefault();
            control.searchTable(this.value);
        })

        let columns = [{
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            data: 'kegiatan',
            className: 'text-center',
        }, {
            data: 'qty',
            className: 'text-center',
        }, {
            data: 'satuan_kegiatan',
            className: 'text-center',
        }, {
            data: 'freq',
            className: 'text-center',
        }, {
            data: 'satuan',
            className: 'text-center',
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const value = numeral(row.harga_satuan).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const jumlah = parseFloat(row.harga_satuan) * parseFloat(row
                    .freq) * parseFloat(row
                    .qty);
                const value = numeral(jumlah).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const value = numeral(row.satuan_real_cost).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const jumlah = parseFloat(row.satuan_real_cost) * parseFloat(row
                    .freq) * parseFloat(row
                    .qty);
                const value = numeral(jumlah).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const valuePo = row.pajak_po === '0' || row.pajak_po === null ? '' : row.pajak_po;
                const valuePPH = row.pajak_pph === '0' || row.pajak_pph === null ? '' : row.pajak_pph;
                return `${valuePo} ${valuePPH}`;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const value = numeral(row.disc_item).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: 'uuid',
        }];
        let selectedUUIDs = []; // Menyimpan UUID yang dipilih

        let uuid_penjualan;

        const get = () => {
            // Fetch data secara sinkron
            $.ajax({
                url: '/procurement/get-po',
                method: 'GET',
                async: false, // Pastikan request berjalan secara sinkron
                success: function(res) {
                    if (res.success === true) {
                        uuid_penjualan = res.data // Mengambil UUID pertama
                    } else {
                        console.error('Gagal mengambil data:', res.message);
                    }
                },
                error: function(error) {
                    console.error('Gagal melakukan permintaan AJAX:', error);
                }
            });
        }

        let columnDefs = [{
            targets: -1,
            title: 'Aksi',
            width: '8rem',
            orderable: false,
            className: 'text-center',
            render: function(data, type, full, meta) {
                let hasil;
                $.each(uuid_penjualan, function(x, y) {
                    if (data === y.uuid_penjualan) {
                        hasil = y;
                    }
                });

                if (hasil) {
                    if (hasil.status === 'progres') {
                        return `
                        <div class="btn btn-warning px-2 py-1">${hasil.status}</div>
                        `;
                    } else {
                        return `
                        <div class="btn btn-success px-2 py-1">${hasil.status}</div>
                        `;
                    }
                } else {
                    return `
                    <a href="javascript:;" type="button" data-uuid="${data}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn-primary button-update btn-icon btn-sm">

                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.5 16.2738C3.5 17.8891 4.80945 19.1986 6.42474 19.1986H10.8479L11.1681 17.9178C11.3139 17.3347 11.6155 16.8022 12.0405 16.3771L17.3522 11.0655C17.9947 10.423 18.8591 10.138 19.6986 10.2103V5.92474C19.6986 4.30945 18.3891 3 16.7738 3H10.6994V7.27463C10.6994 8.88992 9.38992 10.1994 7.77463 10.1994H3.5V16.2738ZM9.34949 3.39597L3.89597 8.84949H7.77463C8.6444 8.84949 9.34949 8.1444 9.34949 7.27463V3.39597ZM17.9886 11.7018L12.6769 17.0135C12.3672 17.3231 12.1475 17.7112 12.0412 18.1361L11.6293 19.7836C11.4503 20.5 12.0993 21.1491 12.8157 20.9699L14.4632 20.558C14.8881 20.4518 15.2761 20.2321 15.5859 19.9224L20.8975 14.6107C21.7008 13.8074 21.7008 12.5051 20.8975 11.7018C20.0943 10.8984 18.7919 10.8984 17.9886 11.7018Z" fill="white"/>
                        <mask id="mask0_1953_23043" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="3" y="3" width="19" height="18">
                        <path d="M3.5 16.2738C3.5 17.8891 4.80945 19.1986 6.42474 19.1986H10.8479L11.1681 17.9178C11.3139 17.3347 11.6155 16.8022 12.0405 16.3771L17.3522 11.0655C17.9947 10.423 18.8591 10.138 19.6986 10.2103V5.92474C19.6986 4.30945 18.3891 3 16.7738 3H10.6994V7.27463C10.6994 8.88992 9.38992 10.1994 7.77463 10.1994H3.5V16.2738ZM9.34949 3.39597L3.89597 8.84949H7.77463C8.6444 8.84949 9.34949 8.1444 9.34949 7.27463V3.39597ZM17.9886 11.7018L12.6769 17.0135C12.3672 17.3231 12.1475 17.7112 12.0412 18.1361L11.6293 19.7836C11.4503 20.5 12.0993 21.1491 12.8157 20.9699L14.4632 20.558C14.8881 20.4518 15.2761 20.2321 15.5859 19.9224L20.8975 14.6107C21.7008 13.8074 21.7008 12.5051 20.8975 11.7018C20.0943 10.8984 18.7919 10.8984 17.9886 11.7018Z" fill="white"/>
                        </mask>
                        <g mask="url(#mask0_1953_23043)">
                        <rect x="0.5" width="24" height="24" fill="white"/>
                        </g>
                        </svg>


                        </a>
                                <input class="checkbox-uuid" type="checkbox" data-uuid="${data}" onclick="handleCheckboxClick('${data}')" />
                            `;
                }
            },
        }];


        // Fungsi untuk menangani peristiwa klik checkbox
        function handleCheckboxClick(uuid) {
            // Cek apakah UUID sudah dipilih sebelumnya
            var index = selectedUUIDs.indexOf(uuid);

            if (index !== -1) {
                // Jika sudah dipilih, hapus dari daftar
                selectedUUIDs.splice(index, 1);
            } else {
                // Jika belum dipilih, tambahkan ke daftar
                selectedUUIDs.push(uuid);
            }

            // Tampilkan UUID yang dipilih saat ini
            formData.append('uuid_penjualan', selectedUUIDs);

            // Periksa apakah ada UUID yang dipilih
            var cetakButton = document.getElementById('cetakButton');
            if (selectedUUIDs.length > 0) {
                // Jika ada, hapus class 'disabled-link'
                cetakButton.classList.remove('disabled-link');
            } else {
                // Jika tidak ada, tambahkan class 'disabled-link'
                cetakButton.classList.add('disabled-link');
            }
        }

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();

            let satuan_real_cost = $('#satuan_real_cost').val();
            let pajak_po = $('#pajak-select').val();
            let pajak_pph = $('#pajak_pph-select').val();
            let disc_item = $('#disc_item').val();
            formDataRealCost.append('satuan_real_cost', satuan_real_cost);
            formDataRealCost.append('pajak_po', pajak_po);
            formDataRealCost.append('pajak_pph', pajak_pph);
            formDataRealCost.append('disc_item', disc_item);

            let type = $(this).attr('data-type');
            if (type == 'add') {
                control.submitForm('/procurement/add-realCost', 'Tambah',
                    'Real Cost',
                    'POST', formDataRealCost);
            } else {
                let uuid = $("input[name='uuid']").val();
                control.submitForm('/procurement/update-realCost/' + uuid, 'Update',
                    'ClieReal Costnt', 'POST', formDataRealCost);
            }
        });

        $(function() {
            control.push_select2('/admin/master-data/get-datavendor', '#from_select');
            control.push_select_pajak('/admin/master-data/get-datapajak', '#pajak-select');
            control.push_select_pajak('/admin/master-data/get-datapajak', '#pajak_pph-select');
            control.initDatatable1(
                '/procurement/get-penjualan/' + lastPart, columns,
                columnDefs);
            get();
        })

        $('#export-excel').click(function(e) {
            e.preventDefault();

            // Fungsi untuk mengonversi objek menjadi string query parameter
            function objectToQueryString(obj) {
                return Object.keys(obj).map(key => key + '=' + encodeURIComponent(obj[key])).join('&');
            }

            let vendor = $('#from_select').val();
            let disc = $('#disc').val();
            let tempo = $('#tempo').val();
            let no_invoice = $('#no_invoice').val();
            let dataPo = {
                uuid_penjualan: selectedUUIDs,
                vendor: vendor,
                disc: disc,
                tempo: tempo,
                no_invoice: no_invoice
            };

            // Mengonversi objek dataPo menjadi string query parameter
            let queryString = objectToQueryString(dataPo);

            $.ajax({
                url: '/procurement/get-po',
                method: 'GET',
                async: false, // Pastikan request berjalan secara sinkron
                success: function(res) {
                    if (res.success === true) {
                        if (res.data.length > 0) {
                            $.each(res.data, function(x, y) {
                                if (y.file === no_invoice) {
                                    $('.no_invoice_error').text('No invoice telah di gunakan')
                                } else {
                                    // Membuka URL dengan query parameter
                                    window.open(`/procurement/export-invoice?${queryString}`,
                                        "_blank");

                                    control.submitForm('/procurement/add-po', 'Tambah',
                                        'Po',
                                        'POST', formData);
                                    get();
                                    $('#kt_modal_1').modal('hide');
                                }
                            })
                        } else {
                            // Membuka URL dengan query parameter
                            window.open(`/procurement/export-invoice?${queryString}`,
                                "_blank");

                            control.submitForm('/procurement/add-po', 'Tambah',
                                'Po',
                                'POST', formData);
                            get();
                            $('#kt_modal_1').modal('hide');
                        }
                    } else {
                        console.error('Gagal mengambil data:', res.message);
                    }
                },
                error: function(error) {
                    console.error('Gagal melakukan permintaan AJAX:', error);
                }
            });

            var cetakButton = document.getElementById('cetakButton');
            if (selectedUUIDs.length > 0) {
                // Jika ada, hapus class 'disabled-link'
                cetakButton.classList.remove('disabled-link');
            } else {
                // Jika tidak ada, tambahkan class 'disabled-link'
                cetakButton.classList.add('disabled-link');
            }
        });
    </script>
@endsection
