<nav class="sidebar-nav">
    <div style=" padding: 0 15px;">
        <div style="overflow: hidden;" class="mt-2">
            <a class="navbar-brand" href="#!" style="color: #67757c;">
                <img src="{{asset('/uploadgambar/'.Auth::user()->company()->logo_perusahaan)}}"
                    onerror="this.src = '{{asset('/uploadgambar/nologo.png')}}'" class="light-logo" width="40px"
                    height="40px" style="border-radius:50%; object-fit:cover" />
                <span class="company_name_sidebar" style="padding:0 5px; font-size:14px;">{{Auth::user()->company()->name}}</span>
            </a>
        </div>
    </div>
    {!! $sidebar !!}
</nav>