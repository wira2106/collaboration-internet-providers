<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ $currentUser->getFirstApiKey() }}">
    <meta name="current-locale" content="{{ locale() }}">
    <meta name="baseurl" content="{{url('/')}}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/themes/adminpress/images/favicon.png')}}">
    <title>Openaccess</title>
    @foreach($cssFiles as $css)
      <link media="all" type="text/css" rel="stylesheet" href="{{ URL::asset($css) }}">
    @endforeach
    @routes
</head>
<body >
    
        <div id="app">
           @yield("content-header")
           @yield('content')
           <router-view></router-view>
        </div>

  <!--Google Maps -->
  @foreach($jsFiles as $js)
    <script src="{{ URL::asset($js) }}" type="text/javascript"></script>
  @endforeach
  <script>
    window.AsgardCMS = {
        translations: {!! $staticTranslations !!},
        locales: {!! json_encode(LaravelLocalization::getSupportedLocales()) !!},
        currentLocale: '{{ locale() }}',
        editor: '{{ $activeEditor }}',
        adminPrefix: '{{ config('asgard.core.core.admin-prefix') }}',
        hideDefaultLocaleInURL: '{{ config('laravellocalization.hideDefaultLocaleInURL') }}',
        filesystem: '{{ config('asgard.media.config.filesystem') }}',
        permissions: {!! json_encode($user_permission) !!},
        profile: {!! json_encode($user_detail) !!},
        roles: {!! json_encode($user_roles) !!},
        company: {!! json_encode($user_company) !!}
    };
</script>
<script src="{{ mix('js/app.js') }}"></script>

@stack('js-stack')
</body>
</html>