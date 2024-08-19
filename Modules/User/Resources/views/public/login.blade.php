<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trans('user::auth.login') }}</title>
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

            {!! Form::open(['route' => 'login.post']) !!}
                <div class="col-12 form">
                    <div class="title">{{ trans('user::auth.login') }}</div>
                    <div class="desc">{{ trans('user::auth.sign in welcome message') }}</div>

                    <div class="form-group-input col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="{{ trans('user::auth.email') }}" required name="email" value="{{ old('email') }}">
                            <label for="email" class="form-label">{{ trans('user::auth.email') }}</label>
                            {!! $errors->first('email','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                        <hr style="margin:0px!important">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ trans('user::auth.password') }}" required >
                            <label for="password" class="form-label">{{ trans('user::auth.password') }}</label>
                            {!! $errors->first('password','
                                <span class="invalid-feedback" role="alert">
                                    <strong>:message </strong>
                                </span>'
                            ) !!}
                        </div>
                    </div>
                        <p class="forgot-password">
                            <a href="{{ route('reset')}}">
                            {{ trans('user::auth.forgot password') }}
                            </a> 
                        </p>
                    <div class="col-12 d-flex flex-row justify-content-center mt-5">
                        <button type="submit" class="btn btn-primary-2">{{ trans('user::auth.login') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 col-lg-7 col-xl-8 hero">
        </div>
    </main>
    
</body>
</html>