@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-5">Upload documents for {{ $company->name }}</h1>
    </div>

    <div class="container">
        <document-component :user="{{ json_encode($user) }}"></document-component>
    </div>
@endsection
