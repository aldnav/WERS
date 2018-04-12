
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
					<div id="map"> </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
