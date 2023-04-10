@extends('layouts.app')

@section('content')
<div class="container">    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Import Data') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="row g-3" action="{{ route('edit-data') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="page" value="{{ $page }}">
                        <input type="hidden" name="import_id" value="{{ $import->import_id }}">
                        <div class="col-md-4">
                            <label for="inputEmail4" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $import->last_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="inputPassword4" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="{{ $import->first_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="inputPassword4" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle_name" value="{{ $import->middle_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Street</label>
                            <input type="text" class="form-control" name="address_street" value="{{ $import->address_street }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress2" class="form-label">Barangay</label>
                            <input type="text" class="form-control" name="address_brgy" value="{{ $import->address_brgy }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="form-label">City</label>
                            <input type="text" class="form-control" name="address_city" value="{{ $import->address_city }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputState" class="form-label">Province</label>
                            <input type="text" class="form-control" name="address_province" value="{{ $import->address_province }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail4" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="contact_phone" value="{{ $import->contact_phone }}">
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail4" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" name="contact_mobile" value="{{ $import->contact_mobile }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $import->email }}" required>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection