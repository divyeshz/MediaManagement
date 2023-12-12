@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
    <h1 class=" text-center ">Welcome to {{ config('variables.templateName') }}</h1>
    <h2 class=" text-center ">Hey!!  {{ Auth::user()->name }}</h2>
@endsection
