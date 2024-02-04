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
                                            <th>Nama Client</th>
                                            <th>Project/Event</th>
                                            <th>Status</th>
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
@section('script')
    <script>
        let control = new Control();

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
            data: 'nama_client',
            width: '8rem',
            className: 'text-center',
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                let result = `
                                <a href="/procurement/po-client/${row.uuid}" class="btn btn-success px-3 py-2">${row.event}</a>
                            `;
                return result;
            }
        }, {
            data: null,
            className: 'text-center',
            render: function(data, type, row, meta) {
                if (row.status) {
                    return `
                        <div class="btn btn-success px-2 py-1">${row.status}</div>
                        `;
                } else {
                    return `
                        <div class="btn btn-warning px-2 py-1">Progres</div>
                        `;
                }
            }
        }];

        $(function() {
            control.initDatatable('/procurement/get-dataclient', columns);
        })
    </script>
@endsection
