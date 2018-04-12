@extends('layouts.master')

@section('content')
 <div class="container">
    <div class="col-md-3">
    </div>
 	
    <div class="col-md-6">
        <h4>Create an organization</h4>
        <form action="">
            <div class="form-group">
                <label for="id_organization_name">Organization name</label>
                <input type="text" id="id_organization_name" class="form-control" placeholder="Iron Hills Medical Organization" />
            </div>
            <div class="form-group">
                <label for="id_admin_email_1">Admin Email address</label>
                <input type="email" class="form-control" id="id_admin_email_1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                <button type="button" class="btn btn-outline-dark pull-right">Add another admin</button>
            </div>
            <button type="submit" class="btn btn-success">Create organization</button>
        </form>
    </div>
 </div>
@endsection
