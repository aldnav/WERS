
@extends('layouts.app')
@section('add_report')
	<div class="float-left mr-auto" id="navbarCollapse">
	        <div class="form-inline my-2 my-lg-0">

	       <button class="btn btn-sm js-add-btn" id="show-modal" @click="showModal=true" >Add</button>
     </div>
  </div>
@endsection
@section('content')
<div class="container-fluid">
        <div class="row flex-xl-nowrap">
            <sidebar></sidebar>

            <div class=" col-xs-12 col-sm-9">
                <incident-map> </incident-map>
            
        </div>
    </div>
    </div>
@endsection