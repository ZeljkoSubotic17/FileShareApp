@extends('layouts.app')

@section('content')
    <div class="container">
        <users-table :initial-users="{{ json_encode($users) }}" :auth-user="{{ json_encode($user) }}"></users-table>
    </div>
@endsection
