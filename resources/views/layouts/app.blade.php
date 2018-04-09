<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chat Vago') }}</title>

    <!-- Styles -->
    <!--     <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ asset('/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/sb-admin.css') }}">
    <link  href="{{ asset('/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/') }}/css/style.css">
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('vendor/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ asset('js/sb-admin.min.js')}}"></script>
        <!-- Custom scripts for this page-->
        <script src="{{ asset('js/sb-admin-datatables.min.js')}}"></script>
    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    
</head>

<body class="fixed-nav sticky-footer bg-dark">
<!--     <div id="app"> -->
        @guest
            @yield('content')
        @else
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="{{ url('/') }}"> {{ config('app.name', 'Chat Vago') }}</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                  <a class="nav-link" href="{{route('home')}}">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Analytics">
                  <a class="nav-link" href="{{route('analytics')}}">
                    <i class="fa fa-fw fa-area-chart"></i>
                    <span class="nav-link-text">Analytics</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
                  <a class="nav-link" href="{{ route('broadcastList')}}">
                    <i class="fa fa-fw fa-sitemap"></i>
                    <span class="nav-link-text">Braodcast</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
                  <a class="nav-link" href="{{ route('messengerCode')}}">
                    <i class="fa fa-fw fa-link"></i>
                    <span class="nav-link-text">Messenger Code</span>
                  </a>
                </li>
              </ul>
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-sign-out"></i>{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
                </li>
              </ul>
            </div>
          </nav>
        <div class="content-wrapper">
                    @yield('content')
        <!-- /.container-fluid-->
            <!-- /.content-wrapper-->
            <footer class="sticky-footer">
              <div class="container">
                <div class="text-center">
                  <small>Copyright © ChatVago </small>
                </div>
              </div>
            </footer>
        </div>
        @endguest
    
</body>
</html>
