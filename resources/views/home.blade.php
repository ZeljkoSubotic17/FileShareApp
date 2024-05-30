@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (!auth()->user()->company_id)
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('You are logged in! Create Company to continue') }}
                        </div>
                    </div>
                @endif


                @if (!auth()->user()->company_id)
                    <create-company-component></create-company-component>
                @else
                    <div class="mt-10">
                        Zdravo!!!
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
