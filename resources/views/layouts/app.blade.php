<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WERS</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md">
      <a class="navbar-brand" href="#">WERS</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      @yield('add_report')
      @yield('reports')
      @yield('validate_report')
      @yield('notifications')
      <div class="quick-stats mr-auto">
        <stat></stat>
      </div>
      <button type="button" class="btn btn-sm js-add-btn" aria-label="Left Align" v-on:click="showModal=true"><i class="fas fa-plus-circle" id="show-modal"></i></button>
      @if(!Auth::guest())
      <!-- <a href="#" v-on:click="showReports=true" class="navbar-actions"><i class="fas fa-flag"></i></a> -->
      <a href="#" v-on:click="showNotifications=true"><i class="fas fa-bell js-notifications"></i></a>
      <a href="{{ route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> Logout</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST"
              style="display: none;">
            {{ csrf_field() }}
        </form>          

      @else
      <a href="{{ route('login') }}" type="button" class="btn btn-primary btn-sm js-login"><i class="fas fa-sign-in-alt"></i> &nbsp;Log in</a>
      @endif
    </nav>
    @yield('content')
  </div>
    <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>

</body>
    <script crossorigin="anonymous" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <!-- <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7a-pVRxc_cx00QNTiPWQZW50qxiqZGO0&libraries=places&callback=geoLocationInit"></script>  --><!-- replaced with vue component -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>