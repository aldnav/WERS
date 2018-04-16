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
    <nav class="navbar navbar-expand-md">
      <a class="navbar-brand" href="#">WERS</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <form class="form-inline mt-2 mt-md-0 search-form">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        </form>
      </div>
      <div class="quick-stats mr-auto">
        <div class="stat">
          <div class="stat-counter">5</div>
          <div class="stat-text">Fires</div>
        </div>
      </div>
      <!-- <i class="far fa-plus-circle"></i> -->
      <button type="button" class="btn btn-sm js-add-btn" aria-label="Left Align"><i class="fas fa-plus-circle"></i></button>
      <a href="#"><i class="fas fa-bell js-notifications"></i></a>
      <button type="button" class="btn btn-primary btn-sm js-login"><i class="fas fa-sign-in-alt"></i> &nbsp;Log in</button>
    </nav>
    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script crossorigin="anonymous" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7a-pVRxc_cx00QNTiPWQZW50qxiqZGO0&libraries=places&callback=geoLocationInit"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
