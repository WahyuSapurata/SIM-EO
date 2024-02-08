@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">
                @foreach ($dataPajak as $pajak)
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25"
                                        viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <style>
                                            svg {
                                                fill: #2b3674
                                            }
                                        </style>
                                        <path
                                            d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM112 192H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                                    </svg>
                                    <div>
                                        <div class="fs-6">{{ $pajak->deskripsi_pajak }}</div>
                                        <div class="fs-5">{{ $pajak->pajak . '%' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
