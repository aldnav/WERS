
@extends('layouts.app')


@section('content')
    <report-tracker repid="{{$rep}}" :user-id="{{Auth::id()}}"></report-tracker>
@endsection
