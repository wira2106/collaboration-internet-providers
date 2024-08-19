<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Access | Login</title>
    <link rel="stylesheet" href="{{url('/modules/user/css/lupapassword.css')}}">
    <link rel="stylesheet" href="{{url('/modules/user/bootstrap5/css/bootstrap5.min.css')}}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    {!! Form::open(['route' => 'reset.post']) !!}

        <main class="d-flex flex-row">
            <div class="col-md-6 col-12 col-lg-4 left-sidebar">
                <div class="header">
                    <img src="{{url('/modules/user/img/icon.png')}}" alt="" class="logo">
                    <h2 class="title">Open Access</h2>
                </div>

                
                <div class="col-12 form">
                    <div class="title">{{ trans('user::auth.reset password') }}</div>
                    <div class="desc">{{ trans('user::auth.to reset password complete this form') }}</div>

                    @if (session('status'))
                        <div class="alert alert-success mt-2" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group-input col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" name="email" id="email" placeholder="{{ trans('user::auth.email') }}">
                            <label for="email" class="form-label">{{ trans('user::auth.email') }}</label>
                        </div>
                        {!! $errors->first('email', '
                            <span class="invalid-feedback" role="alert">
                                <strong>:message</strong>
                            </span>') 
                        !!}

                    </div>
                    <div class="col-12 d-flex flex-row justify-content-center mt-5">
                        <button class="btn btn-primary-2" >{{ trans('user::auth.reset password') }}</button>
                    </div>
            </div>
            <div class="col-md-6 col-lg-8 hero">
            </div>
        </main>
    </form>
    
</body>
</html>