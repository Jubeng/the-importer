@extends('layouts.app')
@section('content')
<div class="container text-center">
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="card border-secondary mb-3">
                <div class="card-header">Welcome to The Importer</div>
                <div class="card-body">
                    <h4 class="card-title">A simple website to import .xls and .xlsx and manage its data.</h4>
                    <p class="card-text">
                        <p>The Importer uses queue to handle big data imports so that it can track the importing progress.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">Get Started</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
        </div>
    </div>
  </div>
@endsection
