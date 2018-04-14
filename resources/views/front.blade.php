
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                  <div class="row" >
                    <div class="col-md-4 col-md-12">
                    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                      <form>
                          <div class="form-inline">  
                             <input type="hidden" id="id">
                            <select id="incident_id" class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>7</option>
                                <option>6</option>
                                <option>5</option>
                            </select>
                          </div>
<!--                           {{ csrf_field() }} -->
                          <div class=form-group>
                            <textarea id="body" class="controls" placeholder="What is this report about? (e.g. Fire on bldg...)"></textarea>  
                          </div>
                          <div class=form-group>
                              <input id="owner_id" class="controls">  
                          </div>
                            <div class=form-group>
                              <input id="lat" class="controls">  
                          </div>
                            <div class=form-group>
                              <input id="lng" class="controls">  
                          </div>
                          <div class=form-group>
                              <input id="status" class="controls">  
                          </div>
                           <button  type="submit" class="btn btn-success" onclick="submitReport();">Send Report</button>
                        </form>
                      
                      <div id="map"> </div> 
                      </div>
                  </div>
                
            </div>

        </div>
        
    </div>

</div>
@endsection
<!-- <script type="text/javascript">
$(document).ready(function(){
  $('#submitReport').on(submit(function(e){
       e.preventDefault();
       var formData = $(this).serialize();
       var ajaxUrl = $(this).attr('action');
/*        var val=$('#locationSelect').val();*/
      $.ajax({
        url:'http://127.0.0.1:8000/report',
        data:formData,
        method:"POST",
        success: function (data){
          $(".message").addClass('alert alert-success');
          $(".message").html(data.result);
        },
        error : function(data){
          $(".message").addClass('alert alert-danger');
          $(".message").html(data.result);
        }
      });
    })
  );
});</script> -->