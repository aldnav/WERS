
@extends('layouts.app')


@section('content')
<user-update email="{{$user->email}}" name="{{$user->name}}" :lat="{{$user->lat}}" :lng="{{$user->lng}}" contact-number=" {{$user->contact_number}}" user-id="{{$user->id}}"></user-update>
@endsection
