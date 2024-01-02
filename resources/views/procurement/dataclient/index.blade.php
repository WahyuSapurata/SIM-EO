@php
    $role = auth()->user()->role;
@endphp
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
                                            <th>Venue</th>
                                            <th>Project Date</th>
                                            <th>Nama PIC</th>
                                            <th>No. PIC</th>
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

        $(".kt_datepicker_7").flatpickr({
            dateFormat: "d-m-Y",
        });

        $(document).on('click', '#button-side-form', function() {
            control.overlay_form('Tambah', 'Client');
        })

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();
            let type = $(this).attr('data-type');
            if (type == 'add') {
                control.submitFormMultipartData('/procurement/add-dataclient', 'Tambah',
                    'Client',
                    'POST');
            } else {
                let uuid = $("input[name='uuid']").val();
                control.submitFormMultipartData('/procurement/update-dataclient/' + uuid, 'Update',
                    'Client', 'POST');
            }
        });

        $(document).on('click', '.button-update', function(e) {
            e.preventDefault();
            let url = '/procurement/show-dataclient/' + $(this).attr('data-uuid');
            control.overlay_form('Update', 'Client', url);
        })

        $(document).on('click', '.button-delete', function(e) {
            e.preventDefault();
            let url = '/procurement/delete-dataclient/' + $(this).attr('data-uuid');
            let label = $(this).attr('data-label');
            control.ajaxDelete(url, label)
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
            data: 'nama_client',
            className: 'text-center',
        }, {
            data: 'event',
            className: 'text-center',
        }, {
            data: 'venue',
            className: 'text-center',
        }, {
            data: 'project_date',
            className: 'text-center',
        }, {
            data: 'nama_pic',
            className: 'text-center',
        }, {
            data: 'no_pic',
            className: 'text-center',
        }];

        $(function() {
            control.initDatatable('/procurement/get-dataclient', columns);
        })
    </script>
@endsection
