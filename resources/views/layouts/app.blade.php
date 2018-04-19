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
     <div class="float-left mr-auto" id="navbarCollapse">
        <div class="form-inline my-2 my-lg-0">
          <place-search></place-search>
          <!-- <button id="show-modal" @click="showModal = true" aria-label="Left Align">Add</button > -->
          
            <!-- <button type="button" class="btn btn-sm js-add-btn" aria-label="Left Align"><i class="fas fa-plus-circle"></i></button> -->
      <!--    <modal></modal> -->
          

           <button class="btn btn-sm js-add-btn" id="show-modal" @click="showModal = true">Add</button>
            <!-- use the modal component, pass in the prop -->
            <modal v-if="showModal" @close="showModal=false" >
            <!-- ongoing bug-fix for report incident -->
            <!--   <template slot='body'>
                <report-form :user-id={{Auth::user()}}  @close="showModal = false"></report-form>
              </template> -->
            </modal>
        </div>
      </div>
   
      <a href="#">Notifications</a>
      <button type="button" class="btn btn-primary btn-sm">Sign in</button>
    </nav>

    
    @yield('content')
  </div>
    <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>    

</body>
    <script crossorigin="anonymous" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7a-pVRxc_cx00QNTiPWQZW50qxiqZGO0&libraries=places&callback=geoLocationInit"></script> <!-- replaced with vue component -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>