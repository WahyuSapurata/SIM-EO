@extends('layouts.layout')
@section('content')
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
                                            <th>NPWP</th>
                                            <th>Nama Pemotong/Client</th>
                                            <th>Nomor Faktur</th>
                                            <th>Tanggal Faktur</th>
                                            <th>Masa</th>
                                            <th>Tahun</th>
                                            <th>Status Faktur</th>
                                            <th>DPP</th>
                                            <th>PPN</th>
                                            <th>Nama Event</th>
                                            <th>Area</th>
                                            <th>PPH 23</th>
                                            <th>Total Tagihan</th>
                                            <th>Realisasi Dana Masuk</th>
                                            <th>Deskripsi</th>
                                            <th>Selisih</th>
                                            <th>No. Bupot</th>
                                            <th>TGL Bupot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    {{-- <tfoot class="bg-primary rounded">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <td style="text-align: left !important;" colspan="5">Total</td>
                                            <td style="text-align: left !important;" colspan="2" id="totalNominal">
                                                Rp 0
                                            </td>
                                        </tr>
                                    </tfoot> --}}
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

        $(document).on('keyup', '#search_', function(e) {
            e.preventDefault();
            control.searchTable(this.value);
        })

        const initDatatable = async () => {
            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#kt_table_data')) {
                $('#kt_table_data').DataTable().clear().destroy();
            }

            var groupedData = {}; // Menyimpan nilai grup sebelumnya

            // Initialize DataTable
            $('#kt_table_data').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [1, 'asc']
                ],
                processing: true,
                ajax: '/pajak/laporan/get-faktur-keluar',
                columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'event',
                    className: 'text-center',
                }, {
                    data: 'client',
                    className: 'text-center',
                }, {
                    data: 'tanggal',
                    className: 'text-center',
                }, {
                    data: 'no',
                    className: 'text-center',
                }, {
                    data: 'nominal',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        const value = numeral(data).format(
                            '0,0'); // Format to rupiah
                        return 'Rp ' + value;
                    }
                }, {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        let result;
                        if (row.file_po) {
                            result =
                                `<a href="{{ asset('pdf/${row.file_po}') }}" target="_blank" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-2 py-1">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px; color: red;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                        </svg>
                                        Lihat File
                                    </div>
                                </a>`;
                        } else if (row.file_invoice) {
                            result =
                                `<a href="{{ asset('pdf-invoice/${row.file_invoice}') }}" target="_blank" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-2 py-1">
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
                }],

                rowCallback: function(row, data, index) {
                    var api = this.api();
                    var startIndex = api.context[0]._iDisplayStart;
                    var rowIndex = startIndex + index + 1;
                    $('td', row).eq(0).html(rowIndex);
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalNominal = 0;

                    // Calculate total for 'harga_satuan' column
                    api.column(5, {
                        search: 'applied'
                    }).data().each(function(value) {
                        // Harga satuan diubah menjadi float dan dikalikan dengan freq
                        totalNominal += parseFloat(value);
                    });
                    // Update the total row in the footer
                    $('#totalNominal').html('Rp ' + numeral(totalNominal).format('0,0'));
                },
            });
        };

        $(function() {
            initDatatable();
        });
    </script>
@endsection
