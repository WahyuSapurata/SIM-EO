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
                                            <th>Nama Event</th>
                                            <th>Client</th>
                                            <th>Budget Client</th>
                                            <th>Real Cost</th>
                                            <th>Keuntungan</th>
                                            <th>Persentase Keuntungan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot class="bg-primary rounded">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <td style="text-align: left !important;" colspan="3">Total</td>
                                            <td style="text-align: left !important;" colspan="1" id="total_budget">
                                                Rp 0
                                            </td>
                                            <td style="text-align: left !important;" colspan="1" id="total_realCost">
                                                Rp 0
                                            </td>
                                            <td style="text-align: left !important;" colspan="2" id="keuntungan">
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
                ajax: '/admin/data-laporan/get-laporan-fee',
                columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'event',
                    className: 'text-center',
                }, {
                    data: 'nama_client',
                    className: 'text-center',
                }, {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        const value = numeral(row.budget).format(
                            '0,0'); // Format to rupiah
                        return 'Rp ' + value;
                    }
                }, {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        const value = numeral(row.real_cost).format(
                            '0,0'); // Format to rupiah
                        return 'Rp ' + value;
                    }
                }, {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        const value = numeral(row.keuntungan).format(
                            '0,0'); // Format to rupiah
                        return 'Rp ' + value;
                    }
                }, {
                    data: 'persentase_keuntungan',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        const value = Math.round(
                            data); // Format to rupiah with one decimal place
                        return value + '%';
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
                    var totalBudget = 0;
                    var totalRealCost = 0;
                    var totalKeuntungan = 0;

                    // Calculate total for 'harga_satuan' column
                    api.column(3, {
                        search: 'applied'
                    }).data().each(function(value) {
                        // Harga satuan diubah menjadi float dan dikalikan dengan freq
                        totalBudget += value.budget;
                    });

                    api.column(4, {
                        search: 'applied'
                    }).data().each(function(value) {
                        // Harga satuan diubah menjadi float dan dikalikan dengan freq
                        totalRealCost += value.real_cost;
                    });

                    api.column(5, {
                        search: 'applied'
                    }).data().each(function(value) {
                        // Harga satuan diubah menjadi float dan dikalikan dengan freq
                        totalKeuntungan += value.keuntungan;
                    });

                    // Update the total row in the footer
                    $('#total_budget').html('Rp ' + numeral(totalBudget).format('0,0'));
                    $('#total_realCost').html('Rp ' + numeral(totalRealCost).format('0,0'));
                    $('#keuntungan').html('Rp ' + numeral(totalKeuntungan).format('0,0'));
                },
            });
        };

        $(function() {
            initDatatable();
        });
    </script>
@endsection
