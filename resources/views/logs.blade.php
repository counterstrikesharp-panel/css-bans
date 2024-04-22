@extends('layouts.app')
<style>
    pre {
        background: black;
        color: white;
        text-wrap: balance;
    }
</style>
@section('content')
    <pre>{{ $logContent }}</pre>
@endsection


