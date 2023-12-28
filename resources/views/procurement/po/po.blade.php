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
                class="btn btn-sm btn-info d-flex align-items-center gap-1">
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
                Cetak Invoice
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
                    <h3 class="modal-title">Buat Invoice</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form class="form-data" enctype="multipart/form-data">

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
                            <label class="form-label">Pajak</label>
                            <select name="pajak" class="form-select" data-control="select2" id="pajak-select"
                                data-placeholder="Pilih jenis inputan">
                            </select>
                            <small class="text-danger pajak_error"></small>
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
                        <div class="container">
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
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-primary rounded">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <td style="text-align: left !important;" colspan="7">Total</td>
                                            <td style="text-align: left !important;" colspan="3" id="total-subtotal">
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
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('script')
    <script>
        let control = new Control();
        // Deklarasikan variabel formData di luar fungsi
        let formData = new FormData();
        let formDataPo = new FormData();

        var currentPath = window.location.pathname;
        var pathParts = currentPath.split('/'); // Membagi path menggunakan karakter '/'
        var lastPart = pathParts[pathParts.length - 1]; // Mengambil elemen terakhir dari array

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
                    .freq);
                const value = numeral(jumlah).format(
                    '0,0'); // Format to rupiah
                return 'Rp ' + value;
            }
        }, {
            data: 'ket',
            className: 'text-center',
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
            formDataPo.append('uuid_penjualan', selectedUUIDs);
        }

        $(function() {
            control.push_select2('/admin/master-data/get-datavendor', '#from_select');
            control.push_select_pajak('/admin/master-data/get-datapajak', '#pajak-select');
            control.initDatatable1(
                '/procurement/get-penjualan/' + lastPart, columns,
                columnDefs);
            get();
        })

        // Use jQuery to capture the click event of the checkbox
        // $(document).on('click', '.checkbox-uuid', function() {
        //     // Extract the data-uuid attribute and call the handleCheckboxClick function
        //     var uuid = $(this).data('uuid');
        //     handleCheckboxClick(uuid);
        // });

        $('#export-excel').click(function(e) {
            e.preventDefault();
            // console.log($('#from_select').val());
            // control.submitForm('/procurement/add-po', 'Tambah',
            //     'Po',
            //     'POST', formData);
            // get();

            formDataPo.append('vendor', $('#from_select').val());
            formDataPo.append('pajak', $('#pajak-select').val());
            // Menggunakan jQuery AJAX untuk mengirim FormData ke server
            // $.ajaxSetup({
            //     headers: {
            //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            //     },
            // });
            // $.ajax({
            //     url: '/procurement/export-invoice',
            //     method: 'POST',
            //     data: formDataPo,
            //     processData: false, // Set processData dan contentType menjadi false
            //     contentType: false,
            //     success: function(response) {
            //         // Membuat URL blob dan membuka jendela baru dengan URL tersebut
            //         // const url = window.URL.createObjectURL(response);
            //         window.open('/procurement/export-invoice', "_blank");
            //     },
            //     error: function(error) {
            //         console.error('Gagal melakukan permintaan AJAX:', error);
            //     }
            // });

            // Menggunakan fetch untuk mengirim FormData ke server
            fetch('/procurement/export-invoice', {
                    method: 'POST',
                    body: formDataPo,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                })
                .then(response => response.blob())
                .then(blob => {
                    // Membuat URL blob dan membuka jendela baru dengan URL tersebut
                    const url = window.URL.createObjectURL(blob);
                    window.open(url, "_blank");
                })
                .catch(error => console.error('Gagal melakukan permintaan fetch:', error));
        });
    </script>
@endsection
