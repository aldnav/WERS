
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row flex-xl-nowrap">
            <sidebar></sidebar>
            <div class="col-12 col-md-9 col-xl-10 m-0 p-0">
                <incident-map> </incident-map>
            
            </div>
        </div>
    </div>
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
            <report-validate-detail :report="selectedReport"></report-validate-detail>
        </ul>
        </div>
        @endif
    </template>
</modal>
@endsection