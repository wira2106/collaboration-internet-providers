<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trans('user::auth.reset data') }}</title>
    <link rel="stylesheet" href="{{url('/modules/user/css/login.css')}}">
    <link rel="stylesheet" href="{{url('/modules/user/bootstrap5/css/bootstrap5.min.css')}}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="d-flex flex-row">
        <div class="col-md-6 col-12 col-lg-5 col-xl-4 left-sidebar">
            <div class="header">
                <img src="{{url('/modules/user/img/icon.png')}}" alt="" class="logo">
                <h2 class="title">Open Access</h2>
            </div>
            {!! Form::open() !!}
                <div class="col-12 form">
                    <div class="title">{{ trans('user::auth.reset data') }}</div>
                    {{-- {{ trans('user::auth.login') }} --}}
                    <div class="desc">{{ trans('user::auth.welcome reset password company') }}</div>
                    {{-- {{ trans('user::auth.sign in welcome message') }} --}}

                    <div class="form-group-input col-12">
                        <div class="form-floating">
                            <input type="text" readonly class="form-control @error('email') is-invalid @enderror" id="email" placeholder="{{ trans('user::auth.email') }}" required name="email" value="{{ old('email')?old('email'):$email}}">
                            <label for="email" class="form-label">{{ trans('user::auth.email') }}</label>
                            {!! $errors->first('email','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                        <hr class="hr">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="full_name" placeholder="Full name" required value="{{$full_name}}">
                            <label for="fullname" class="form-label">{{ trans('user::auth.fullname') }}</label>
                            {!! $errors->first('fullname','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                        <hr class="hr">
                        <div class="form-floating">
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Phone" required value="">
                            <label for="phone" class="form-label">{{ trans('user::auth.phone') }}</label>
                            {!! $errors->first('phone','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                        <hr class="hr">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ trans('user::auth.password') }}" required value="">
                            <label for="password" class="form-label">{{ trans('user::auth.password') }}</label>
                            {!! $errors->first('password','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                        <hr class="hr">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('re_password') is-invalid @enderror" id="re_password" name="re_password" placeholder="{{ trans('user::auth.password') }}" required value="">
                            <label for="re_password" class="form-label">{{ trans('user::auth.repassword') }}</label>
                            {!! $errors->first('re_password','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-row justify-content-center mt-5">
                        <button type="submit" class="btn btn-primary-2">{{ trans('user::auth.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-lg-7 col-xl-8 hero">
        </div>
    </main>
    
</body>
</html>

<style>
    .hr{
        margin:0px!important;
        border:solid 1px gray;
    }
</style>