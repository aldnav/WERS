
@extends('layouts.app')


@section('content')
	@if(!Auth::guest() AND Auth::user()->user_role==1)
    @section('add_button')
        <a href="#" v-on:click="showReports=true" class="navbar-actions"><i class="fas fa-flag"></i></a>
    @endsection
    		<div class="container-fluid">
	        	<div class="row flex-xl-nowrap">
	            <sidebar :user-lat="{{Auth::user()->lat}}" :user-lng="{{Auth::user()->lng}}"></sidebar>
	            <div class=" col-xs-12 col-sm-9">
	                <responder-map user-lat="{{Auth::user()->lat}}" user-lng="{{Auth::user()->lng}}" :user-id="{{Auth::id()}}"> </responder-map>            
	        	</div>
	    		</div>
    		</div>
    @else
		@section('add_button')
			       <button type="button" class="btn btn-sm js-add-btn" aria-label="Left Align" v-on:click="showModal=true"><i class="fas fa-plus-circle" id="show-modal"></i></button>
	  	@endsection

	    <modal v-if="showModal" @close="showModal = false">
	      <template slot='header'>
	        <div class="panel-default clearfix">
	          <h5 class="panel-title float-left">Add Report</h5>
	          <button type="button" class="close float-right" aria-label="Close" @click="showModal = false"><span aria-hidden="true">&times;</span></button>
	        </div>
	      </template>
	      <template slot='body'>
	          @if(Auth::guest())
	          <div class="panel-body">
	            <report-form :user-id= "''" :user-contact="''" @close="showModal = false"></report-form>
	          </div>
	          @else
	          <div class="panel-body">
	            <report-form contact-number="{{Auth::user()->contact_number}}" :user-id= "{{ Auth::id()}}"   @close="showModal = false"></report-form>
	          </div>
	          @endif
	      </template>
	    </modal>
		<div class="row flex-xl-nowrap">	
			@if(Auth::guest())    
            	<reporter-sidebar :user-id="''" :responder="false"></reporter-sidebar>
            @else
            	<reporter-sidebar :user-id="{{ Auth::id() }}" ></reporter-sidebar>

            @endif
            <div class="col-12 col-md-9 col-xl-10 m-0 p-0">
                <incident-map user-lat="{{Auth::user()->lat}}" user-lng="{{Auth::user()->lng}}" :user-id= "{{ Auth::id()}}" > </incident-map>
            </div>
        </div>
	@endif
@endsection


@section('add_report')
<modal v-if="showModal" @close="showModal = false">
    <template slot='header'>
    <div class="panel-default clearfix">
        <h5 class="panel-title float-left">Add a report</h5>
        <button type="button" class="close float-right" aria-label="Close" @click="showModal = false"><span aria-hidden="true">&times;</span></button>
    </div>
    </template>
    <template slot='body'>
        @if(Auth::guest())
        <div class="panel-body">
        <report-form :user-id= "''" :user-contact="''" @close="showModal = false"></report-form>
        </div>
        @else
        <div class="panel-body">
        <report-form :user-id= "{{ Auth::id() }}" @close="showModal = false"></report-form>
        </div>
        @endif
    </template>
</modal>
@endsection

@section('reports')
<modal v-if="showReports" @close="showReports = false">
    <template slot='header'>
    <div class="panel-default clearfix">
        <h5 class="panel-title float-left">Reports</h5>
        <button type="button" class="close float-right" aria-label="Close" @click="showReports = false"><span aria-hidden="true">&times;</span></button>
    </div>
    </template>
    <template slot='body'>
        @if(Auth::guest())
        <div class="panel-body">
        No reports here.
        </div>
        @else
        <div class="panel-body">
            <report-list></report-list>
        </ul>
        </div>
        @endif
    </template>
</modal>
@endsection

@if(!Auth::guest() AND Auth::user()->user_role==1)
    @section('validate_report')
    <modal v-if="showSpecificReport" @close="showSpecificReport = false">
        <template slot='header'>
        <div class="panel-default clearfix">
            <h5 class="panel-title float-left">Review a report</h5>
            <button type="button" class="close float-right" aria-label="Close" @click="showSpecificReport = false"><span aria-hidden="true">&times;</span></button>
        </div>
        </template>
        <template slot='body'>
            @if(Auth::guest())
            <div class="panel-body">
            No reports here.
            </div>
            @else
            <div class="panel-body">
                <report-validate-detail :user-id="{{Auth::id()}}" :report="selectedReport"></report-validate-detail>
            </ul>
            </div>
            @endif
        </template>
    </modal>
    @endsection
@endif


@section('notifications')
<modal v-if="showNotifications" @close="showNotifications = false" class="notif-modal">
    <template slot='header'>
    <div class="panel-default clearfix">
        <h5 class="panel-title float-left">Notifications</h5>
        <button type="button" class="close float-right" aria-label="Close" @click="showNotifications = false"><span aria-hidden="true">&times;</span></button>
    </div>
    </template>
    <template slot='body'>
        @if(Auth::guest())
        <div class="panel-body">
        No notifications here.
        </div>
        @else
        <div class="panel-body notifications-panel-body">
            <notifications :user-role="{{Auth::user()->user_role}}"></notifications>
        </ul>
        </div>
        @endif
    </template>
</modal>
@endsection