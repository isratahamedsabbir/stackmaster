@extends('backend.app')

@section('content')
<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            {{-- PAGE-HEADER --}}
            <div class="page-header">
                <div>
                    <h1 class="page-title">Other Settings <i class="fa-solid fa-triangle-exclamation text-danger" title="Warning"></i></h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Other</li>
                    </ol>
                </div>
            </div>
            {{-- PAGE-HEADER --}}


            <div class="row">
                <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                    <div class="card box-shadow-0">
                        <div class="card-body">
                            
                            <div class="row mb-4 align-items-center">
                                <label for="mail" class="col-md-3 col-form-label fw-bold">MAIL Send</label>
                                <div class="col-md-9">
                                    <div class="form-check form-switch ps-0 d-flex align-items-center">
                                        <input class="form-check-input me-2 @error('mail') is-invalid @enderror" type="checkbox" id="mail" name="mail" {{ $settings['mail'] == 'on' ? 'checked' : '' }} data-url="{{ route('admin.setting.other.mail') }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4 align-items-center">
                                <label for="sms" class="col-md-3 col-form-label fw-bold">SMS Send</label>
                                <div class="col-md-9">
                                    <div class="form-check form-switch ps-0 d-flex align-items-center">
                                        <input class="form-check-input me-2 @error('sms') is-invalid @enderror" type="checkbox" id="sms" name="sms" {{ $settings['sms'] == 'on' ? 'checked' : '' }} data-url="{{ route('admin.setting.other.sms') }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4 align-items-center">
                                <label for="recaptcha" class="col-md-3 col-form-label fw-bold">RECAPTCHA Enabel</label>
                                <div class="col-md-9">
                                    <div class="form-check form-switch ps-0 d-flex align-items-center">
                                        <input class="form-check-input me-2 @error('recaptcha') is-invalid @enderror" type="checkbox" id="recaptcha" name="recaptcha" {{ $settings['mail'] == 'yes' ? 'checked' : '' }} data-url="{{ route('admin.setting.other.recaptcha') }}" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- CONTAINER CLOSED -->
@endsection



@push('scripts')
<script>
    $(document).ready(function() {
        function jsonUpdate(e) {
            NProgress.start();
            $.ajax({
                url: $(e).data('url'),
                type: "GET",
                success: function(response) {
                    NProgress.done();
                    if (response.status === 't-success') {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        $("#mail").on("change", function() {
            jsonUpdate(this);
        });

        $("#sms").on("change", function() {
            jsonUpdate(this);
        });

        $("#recaptcha").on("change", function() {
            jsonUpdate(this);
        });
    });
</script>
@endpush