
@extends('layouts.app')

@section('content')
<div class="container-fluid" id="app">
    <div class="row flex-xl-nowrap">
        <sidebar></sidebar>
        <div class="col-12 col-md-9 col-xl-10 m-0 p-0">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection
