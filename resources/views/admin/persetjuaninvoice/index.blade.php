@extends('layouts.layout')
@section('content')
    <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Putihkan Data</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form class="form-invoice">

                        <input type="hidden" id="uuid" name="uuid">

                        <div class="mb-10">
                            <label class="form-label">Keterangan</label>
                            <input class="form-control" type="text" name="ket" id="ket">
                            <small class="text-danger ket_error"></small>
                        </div>

                        <div class="separator separator-dashed mt-8 mb-5"></div>
                        <div class="d-flex gap-5">
                            <button type="submit" id="button-reload"
                                class="btn btn-primary btn-sm d-flex align-items-center"><i
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
                        <div class="container">
                            <div class="py-5 table-responsive">
                                <table id="kt_table_data"
                                    class="table table-striped table-rounded border border-gray-300 table-row-bordered table-row-gray-300">
                                    <thead class="text-center">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <th>No</th>
                                            <th>No Invoice</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Client</th>
                                            <th>Deskripsi</th>
                                            <th>Total</th>
                                            <th>File</th>
                                            <th>Jumlah Terbayar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
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
                <form class="form-data" enctype="multipart/form-data">

                    <input type="hidden" name="id">
                    <input type="hidden" name="uuid">

                    <div class="mb-10">
                        <label class="form-label">Jumlah Terbayar</label>
                        <input type="text" id="tagihan" class="form-control" name="tagihan">
                        <small class="text-danger tagihan_error"></small>
                    </div>

                    <div class="mb-10">
                        <label class="form-label">Persetujuan</label>
                        <select name="status" class="form-select" data-control="select2" id="from_select"
                            data-placeholder="Pilih jenis inputan">
                        </select>
                        <small class="text-danger status_error"></small>
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

        let formData = new FormData();
        let uuid_persetujuanInvoice;

        $('#tagihan').on('input', function() {
            let value = $(this).val();
            if (value !== "") {
                value = numeral(value).format('0,0'); // Format to rupiah
                $(this).val('Rp ' + value);
            }
        });

        $(document).on('click', '#button-side-form', function() {
            control.overlay_form('Tambah', 'Invoice');
        })

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();
            var formArray = $(".form-data").serializeArray();
            $.each(formArray, function(i, field) {
                formData.append(field.name, field.value);
            });
            formData.append('uuid_invoice', uuid_persetujuanInvoice);
            control.submitForm('/admin/data-invoice/update-persetujuaninvoice/' + uuid_persetujuanInvoice, 'Tambah',
                'Persetujuan Invoice', 'POST', formData);
        });

        $(document).on('click', '.button-update', function(e) {
            e.preventDefault();
            uuid_persetujuanInvoice = $(this).attr('data-uuid');
            control.overlay_form('', 'Persetujuan Invoice');
        })

        $(document).on('click', '#button-reload', function(e) {
            e.preventDefault();
            let formDataReload = new FormData();
            formDataReload.append('uuid', $('#uuid').val());
            formDataReload.append('ket', $('#ket').val());
            control.submitForm('/admin/data-invoice/reload-persetujuaninvoice',
                'Reload', 'Persetujuan invoice', 'POST', formDataReload);
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
            data: 'no_invoice',
            className: 'text-center',
        }, {
            data: 'tanggal_invoice',
            className: 'text-center',
        }, {
            data: 'vendor',
            className: 'text-center',
        }, {
            data: 'deskripsi',
            className: 'text-center',
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const value = numeral(row.total).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                let result;
                if (row.file === null) {
                    result = `
                        <div class="alert alert-danger d-grid align-items-center p-5">
                            <!--begin::Icon-->
                            <span class="svg-icon svg-icon-2hx svg-icon-primary">
                                <svg id="file_color" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <style>#file_color{fill:#ad2416}</style>
                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                </svg>
                                </span>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <!--end::Title-->
                                <!--begin::Content-->
                                <span>File telah di return</span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                            `
                } else {
                    result =
                        `<a href="{{ asset('pdf-invoice/${row.file}') }}" target="_blank" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-2 py-1">
                        <div class="d-flex justify-content-center align-items-center" style="gap: 5px; color: red;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                            </svg>
                            Lihat File
                        </div>
                    </a>`;
                }
                return result;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                const value = numeral(row.tagihan).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: 'uuid',
        }];
        let columnDefs = [{
            targets: -1,
            title: 'Aksi',
            className: 'text-center',
            width: '10rem',
            orderable: false,
            render: function(data, type, full, meta) {
                $('#uuid').val(data);
                if (full.tagihan) {
                    return `
                                    <div class="btn btn-success px-2 py-1">Setujui</div>
                        `;
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

                                    <a href="javascript:;" type="button" data-uuid_invoice=, $('#uuid_invoice').val()"Persetujuan Po Non Vendor" class="btn btn-warning btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512" fill="white"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/></svg>
                                    </a>

                                    `;
                }
            },
        }];

        const setuju = [{
            text: "setujui"
        }];

        $(function() {
            control.push_select3(setuju, '#from_select');
            control.initDatatable('/admin/data-invoice/get-invoice', columns, columnDefs);
        })
    </script>
@endsection
