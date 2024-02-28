@extends('layouts.layout')
@section('button')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack gap-2">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <input type="text" id="tanggal" class="form-control kt_datepicker_7" name="tanggal"
                placeholder="Filer Tanggal">
            <!--end::Title-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <button type="button" id="cetakButton" class="btn btn-sm btn-warning d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#kt_modal_2">
                Tambah Saldo Awal
            </button>

            <button type="button" id="export-excel" class="btn btn-sm btn-success d-flex align-items-center gap-1">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.14933 1.3335H4.44445C3.97295 1.3335 3.52076 1.50909 3.18737 1.82165C2.85397 2.13421 2.66667 2.55814 2.66667 3.00016V13.0002C2.66667 13.4422 2.85397 13.8661 3.18737 14.1787C3.52076 14.4912 3.97295 14.6668 4.44445 14.6668H11.5556C12.0271 14.6668 12.4792 14.4912 12.8126 14.1787C13.146 13.8661 13.3333 13.4422 13.3333 13.0002V5.256C13.3333 5.035 13.2396 4.82307 13.0729 4.66683L9.77778 1.57766C9.61112 1.42137 9.38506 1.33354 9.14933 1.3335ZM9.33333 4.25016V2.5835L12 5.0835H10.2222C9.98648 5.0835 9.76038 4.9957 9.59368 4.83942C9.42698 4.68314 9.33333 4.47118 9.33333 4.25016ZM6.11911 6.90016L8 9.016L9.88089 6.89933C9.95645 6.81446 10.0649 6.76121 10.1823 6.75128C10.2998 6.74136 10.4166 6.77558 10.5071 6.84641C10.5976 6.91725 10.6544 7.0189 10.665 7.129C10.6756 7.23909 10.6391 7.34863 10.5636 7.4335L8.57867 9.66683L10.5636 11.9002C10.6357 11.9854 10.6694 12.0936 10.6575 12.2018C10.6456 12.3101 10.5891 12.4096 10.4999 12.4792C10.4108 12.5489 10.2961 12.5831 10.1805 12.5745C10.0648 12.566 9.95729 12.5154 9.88089 12.4335L8 10.3177L6.11911 12.4343C6.04355 12.5192 5.93513 12.5725 5.81769 12.5824C5.70025 12.5923 5.58342 12.5581 5.49289 12.4872C5.40236 12.4164 5.34556 12.3148 5.33497 12.2047C5.32439 12.0946 5.36089 11.985 5.43645 11.9002L7.42133 9.66683L5.43645 7.4335C5.39742 7.39168 5.36772 7.34297 5.34907 7.29023C5.33043 7.2375 5.32322 7.18179 5.32788 7.12641C5.33254 7.07102 5.34896 7.01706 5.37619 6.96772C5.40342 6.91837 5.4409 6.87462 5.48642 6.83906C5.53195 6.80349 5.58461 6.77681 5.64129 6.76061C5.69798 6.7444 5.75755 6.73898 5.8165 6.74467C5.87545 6.75037 5.93259 6.76706 5.98456 6.79376C6.03653 6.82046 6.08228 6.85664 6.11911 6.90016Z"
                        fill="white" />
                    <mask id="mask0_198_8745" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="2" y="1"
                        width="12" height="14">
                        <path
                            d="M9.14933 1.3335H4.44445C3.97295 1.3335 3.52076 1.50909 3.18737 1.82165C2.85397 2.13421 2.66667 2.55814 2.66667 3.00016V13.0002C2.66667 13.4422 2.85397 13.8661 3.18737 14.1787C3.52076 14.4912 3.97295 14.6668 4.44445 14.6668H11.5556C12.0271 14.6668 12.4792 14.4912 12.8126 14.1787C13.146 13.8661 13.3333 13.4422 13.3333 13.0002V5.256C13.3333 5.035 13.2396 4.82307 13.0729 4.66683L9.77778 1.57766C9.61112 1.42137 9.38506 1.33354 9.14933 1.3335ZM9.33333 4.25016V2.5835L12 5.0835H10.2222C9.98648 5.0835 9.76038 4.9957 9.59368 4.83942C9.42698 4.68314 9.33333 4.47118 9.33333 4.25016ZM6.11911 6.90016L8 9.016L9.88089 6.89933C9.95645 6.81446 10.0649 6.76121 10.1823 6.75128C10.2998 6.74136 10.4166 6.77558 10.5071 6.84641C10.5976 6.91725 10.6544 7.0189 10.665 7.129C10.6756 7.23909 10.6391 7.34863 10.5636 7.4335L8.57867 9.66683L10.5636 11.9002C10.6357 11.9854 10.6694 12.0936 10.6575 12.2018C10.6456 12.3101 10.5891 12.4096 10.4999 12.4792C10.4108 12.5489 10.2961 12.5831 10.1805 12.5745C10.0648 12.566 9.95729 12.5154 9.88089 12.4335L8 10.3177L6.11911 12.4343C6.04355 12.5192 5.93513 12.5725 5.81769 12.5824C5.70025 12.5923 5.58342 12.5581 5.49289 12.4872C5.40236 12.4164 5.34556 12.3148 5.33497 12.2047C5.32439 12.0946 5.36089 11.985 5.43645 11.9002L7.42133 9.66683L5.43645 7.4335C5.39742 7.39168 5.36772 7.34297 5.34907 7.29023C5.33043 7.2375 5.32322 7.18179 5.32788 7.12641C5.33254 7.07102 5.34896 7.01706 5.37619 6.96772C5.40342 6.91837 5.4409 6.87462 5.48642 6.83906C5.53195 6.80349 5.58461 6.77681 5.64129 6.76061C5.69798 6.7444 5.75755 6.73898 5.8165 6.74467C5.87545 6.75037 5.93259 6.76706 5.98456 6.79376C6.03653 6.82046 6.08228 6.85664 6.11911 6.90016Z"
                            fill="white" />
                    </mask>
                    <g mask="url(#mask0_198_8745)">
                        <rect width="16" height="16" fill="white" />
                    </g>
                </svg>
                Export Excel
            </button>
        </div>
        <!--end::Actions-->
    </div>
@endsection
@section('content')
    <div class="modal fade" tabindex="-1" id="kt_modal_2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Saldo Management</h3>

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
                            <label for="saldo" class="form-label">Total Saldo</label>
                            <input class="form-control" type="text" name="saldo" id="saldo">
                            <small class="text-danger saldo_error"></small>
                        </div>

                        <div class="separator separator-dashed mt-8 mb-5"></div>
                        <div class="d-flex gap-5">
                            <button type="submit"
                                class="btn btn-primary btn-sm btn-submit-saldo d-flex align-items-center"><i
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
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Keluar</th>
                                            <th>Masuk</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-primary rounded">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <td style="text-align: left !important;" colspan="6">Total Saldo</td>
                                            <td style="text-align: left !important;" colspan="1" id="total-saldo">
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
        let formData = new FormData();
        let saldoAwal = 0;

        $(".kt_datepicker_7").flatpickr({
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "d-m-Y",
            mode: "range",
            onClose: function(selectedDates, dateStr, instance) {
                // Tangkap perubahan tanggal dan kirimkan data ke server
                $('.tanggal-proide').empty();
                sendDataToServer(dateStr);
            }
        });

        const getSaldo = () => {
            $.ajax({
                url: '/admin/data-laporan/get-saldo/',
                method: 'GET',
                async: false, // Pastikan request berjalan secara sinkron
                success: function(res) {
                    if (res.success === true) {
                        $.each(res.data, function(x, y) {
                            saldoAwal += parseFloat(y.saldo);
                        });
                        var cetakButton = document.getElementById('cetakButton');
                        if (saldoAwal) {
                            // Jika ada, hapus class 'disabled-link'
                            cetakButton.classList.add('disabled-link');
                        }
                    } else {
                        console.error('Gagal mengambil data:', res.message);
                    }
                },
                error: function(error) {
                    console.error('Gagal melakukan permintaan AJAX:', error);
                }
            });
        }

        getSaldo();

        $(function() {
            $('#saldo').on('input', function() {
                let value = $(this).val();
                if (value !== "") {
                    value = numeral(value).format('0,0'); // Format to rupiah
                    $(this).val('Rp ' + value);
                }
            });
        });

        $(document).on('click', ".btn-submit-saldo", function(e) {
            e.preventDefault();

            let saldo = $('#saldo').val();
            formData.append('saldo', saldo)
            control.submitForm('/admin/data-laporan/add-saldo', 'Tambah',
                'Saldo',
                'POST', formData);
            $('#kt_modal_2').modal('hide');
            window.location.reload();
        });

        $(document).on('keyup', '#search_', function(e) {
            e.preventDefault();
            control.searchTable(this.value);
        })

        function sendDataToServer(selectedDateStr) {
            let cumulativeSaldo = 0;
            let columns = [{
                data: null,
                className: 'text-center',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: 'lokasi_user',
                className: 'text-center',
            }, {
                data: 'tanggal',
                className: 'text-center',
                render: function(data, type, row) {
                    // Mengubah format tanggal menggunakan Moment.js
                    return data;
                },
            }, {
                data: 'deskripsi',
                className: 'text-center',
            }, {
                data: null,
                className: 'text-center',
                render: function(data, type, row, meta) {
                    let value;
                    if (row.keluar === 0) {
                        value = '-';
                    } else {
                        value = 'Rp ' + numeral(row.keluar).format(
                            '0,0'); // Format to rupiah
                    }
                    return value;
                }
            }, {
                data: null,
                className: 'text-center',
                render: function(data, type, row, meta) {
                    let value;
                    if (row.masuk === 0) {
                        value = '-';
                    } else {
                        value = 'Rp ' + numeral(row.masuk).format(
                            '0,0'); // Format to rupiah
                    }
                    return value;
                }
            }, {
                data: null,
                className: 'text-center',
                render: function(data, type, row) {
                    // Menghitung sisa saldo
                    let sisaSaldo = row.masuk - row.keluar;

                    // Menambahkan sisa saldo ke variabel kumulatif
                    cumulativeSaldo += sisaSaldo;

                    // Menampilkan sisa saldo kumulatif
                    return 'Rp ' + numeral(cumulativeSaldo).format('0,0');
                },
            }];

            $(function() {
                control.initDatatable3(`/admin/data-laporan/get-laporan/${selectedDateStr}`, columns);
            })
        }

        $('#export-excel').click(function(e) {
            e.preventDefault();
            let val = $('#tanggal').val();
            console.log(val);
            if (!val) {
                swal
                    .fire({
                        text: `Filter tanggal priode terlebih dahulu`,
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 1500,
                    })
                return;
            }
            window.open(`/admin/data-laporan/export-laporan/${val}`, "_blank");
        });
    </script>
@endsection
