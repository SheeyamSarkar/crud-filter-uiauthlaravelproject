@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('/home') }}" class="btn btn-info btn-sm active">Home</a>
                    <a href="" class="btn btn-info btn-sm">Category</a>
                    <a href="" class="btn btn-info btn-sm">Subcategory</a>
                    <a href="" class="btn btn-info btn-sm">Product</a>
                    <br>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
