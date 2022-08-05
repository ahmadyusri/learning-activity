@extends('layouts.user.index')
@section('titlePage', 'Change Password .:.')
@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">{{ __('Change Password') }}</div>

            <div class="card-body">
                @include('layouts.user.components.status-alert')

                <form class="form-horizontal" method="post">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label>Old Password</label>
                        </div>

                        <div class="col-sm-8">
                            <div class="input-group">
                                <input required value="{{ old('old_password') }}" type="password" minlength="8"
                                    maxlength="30" name="old_password" class="form-control">

                                <span onclick="toggle_password(this, ['old_password'])" class="input-group-text"
                                    data-toggle="tooltip" title="View Password" data-placement="left"><i
                                        class="fa fa-eye"></i></span>

                            </div>

                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label>New Password</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input required value="{{ old('password') }}" type="password" minlength="8" maxlength="30"
                                    name="password" class="form-control">
                                <span onclick="toggle_password(this, ['password', 'password_confirmation'])"
                                    class="input-group-text" data-toggle="tooltip" title="View Password"
                                    data-placement="left"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label>Confirmation Password</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input required value="{{ old('password_confirmation') }}" type="password" minlength="8"
                                    maxlength="30" name="password_confirmation" class="form-control">
                                <span class="input-group-text" data-toggle="tooltip"
                                    title="Confirmation Password must be the same as the new Password"
                                    data-placement="left"><i class="fa fa-info-circle"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-lock"></i>
                                Change Password
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        function toggle_password(e, e_input) {
            $.each(e_input, function(e_input, v_input) {
                var type = $('[name="' + v_input + '"]').attr('type');
                if (type == "password") {
                    $('[name="' + v_input + '"]').attr('type', 'text');
                    $(e).html('<i class="fa fa-eye-slash"></i>');
                } else {
                    $('[name="' + v_input + '"]').attr('type', 'password');
                    $(e).html('<i class="fa fa-eye"></i>');
                }
            });
        }
    </script>
@endsection
