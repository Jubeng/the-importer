@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Manage Import') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="{{ $errors->count() === 1 ? ' mb-0' : '' }}">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Import
                                </div>
                                <div class="card-body">
                                    <form class="row col" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                            <input class="form-control form-control-sm" id="importFile" name="import_file" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    Export
                                </div>
                                <div class="card-body">
                                    <form class="row col" action="{{ route('export') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-text">Export by current page or all your data.</p>
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="page" value="1">
                                        <div class="d-flex flex-row">
                                            <div class="p-2">
                                                <button type="submit" name="export_type" value="page" class="btn btn-primary">Export Current Page</button>
                                            </div>
                                            <div class="p-2">
                                                <button type="submit" name="export_type" value="all" class="btn btn-primary">Export All</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive mt-5">
                        <thead class="thead ">
                            <tr class="table-light">
                                <th scope="col-1">
                                    <input class="form-check-input me-1" type="checkbox" name="chkBoxImportAll">
                                </th>
                                <th scope="col-3">Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Mobile</th>
                                <th scope="col">Email</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($imports as $aImport)
                                <tr>
                                    <td scope="row">
                                        <input class="form-check-input me-1" type="checkbox" value="{{$aImport['import_id'] }}" name="chkBoxImport">
                                    </td>
                                    <td>
                                        {{ $aImport['first_name'] }} {{$aImport['middle_name']}} {{ $aImport['last_name'] }}
                                    </td>
                                    <td>{{ $aImport['address_street'] }} {{ $aImport['address_brgy'] }} {{ $aImport['address_city'] }} {{ $aImport['address_province'] }}</td>
                                    <td>{{ $aImport['contact_phone'] === '' ? 'N/A' : $aImport['contact_phone'] }}</td>
                                    <td>{{ $aImport['contact_mobile'] }}</td>
                                    <td>{{ $aImport['email'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-primary">Edit</button>
                                            <button type="button" class="btn btn-danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
