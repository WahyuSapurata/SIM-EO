@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">
                <div class="card">
                    <div class="card-body p-5">
                        <form class="form-data">
                            <input type="hidden" name="id" value="{{ $akun->id }}">
                            <input type="hidden" name="uuid" value="{{ $akun->uuid }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fw-bolder fs-4 mb-5">Detail Akun</div>
                                    <div class="card">
                                        <div class="mb-10">
                                            <label class="form-label">Nama</label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                value="{{ $akun->name }}">
                                            <small class="text-danger name_error"></small>
                                        </div>

                                        <div class="mb-10">
                                            <label class="form-label">Username</label>
                                            <input type="text" id="username" class="form-control"
                                                value="{{ $akun->username }}" name="username">
                                            <small class="text-danger username_error"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-bolder fs-4 mb-5">Ganti Password Akun</div>
                                    <div class="card">
                                        <div class="mb-10" data-kt-password-meter="true">
                                            <label class="form-label">Password Lama</label>
                                            <div class="position-relative">
                                                <input class="form-control bg-transparent" id="password_lama"
                                                    type="password" name="password_lama" autocomplete="off" />
                                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50"
                                                    style="right: -15px;" data-kt-password-meter-control="visibility">
                                                    <i class="bi bi-eye-slash fs-2"></i>
                                                    <i class="bi bi-eye fs-2 d-none"></i>
                                                </span>
                                                <small class="text-danger password_lama_error"></small>
                                            </div>
                                        </div>
                                        <div class="mb-10" data-kt-password-meter="true">
                                            <label class="form-label">Password Baru</label>
                                            <div class="position-relative">
                                                <input class="form-control bg-transparent" id="password" type="password"
                                                    name="password" autocomplete="off" />
                                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50"
                                                    style="right: -15px;" data-kt-password-meter-control="visibility">
                                                    <i class="bi bi-eye-slash fs-2"></i>
                                                    <i class="bi bi-eye fs-2 d-none"></i>
                                                </span>
                                                <small class="text-danger password_error"></small>
                                            </div>
                                        </div>
                                        <div class="mb-10" data-kt-password-meter="true">
                                            <label class="form-label">Password Baru Ulang</label>
                                            <div class="position-relative">
                                                <input class="form-control bg-transparent" id="password_confirmation"
                                                    type="password" name="password_confirmation" autocomplete="off" />
                                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50"
                                                    style="right: -15px;" data-kt-password-meter-control="visibility">
                                                    <i class="bi bi-eye-slash fs-2"></i>
                                                    <i class="bi bi-eye fs-2 d-none"></i>
                                                </span>
                                                <small class="text-danger password_confirmation_error"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-5"></div>
                            <div class="d-flex gap-5 justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary btn-sm btn-submit d-flex align-items-center"><i
                                        class="bi bi-file-earmark-diff"></i> Simpan</button>
                            </div>
                        </form>
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

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();
            let uuid = $("input[name='uuid']").val();
            control.submitFormMultipartData('/admin/update-password/' + uuid, 'Update',
                'Data Akun', 'POST');
            $('#password_lama').val(null);
            $('#password').val(null);
            $('#password_confirmation').val(null);
        });
    </script>
@endsection
