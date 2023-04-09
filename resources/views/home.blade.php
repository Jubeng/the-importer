@extends('layouts.app')

@section('content')
@php
    $page = (int)$page;
    $totalPage = $count / 10;
@endphp
<div class="container">
    @if ($jobs !== false)
        <div class="modal-backdrop fade show"></div>
        <!-- Modal -->
        <div class="modal fade show" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">
                            Importing data...
                        </h1>
                    </div>
                    <div class="modal-body">
                        <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{ $jobs }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $jobs }}%;"></div>
                        </div>
                        Please wait before importing again, thank you...
                    </div>
                </div>
            </div>
        </div>
    @endif
    
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
                            <ul class="mb-0">
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
                    @if($count > 10)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{(($page  - 10) <= 0 ? 'disabled' : '')}}">
                                    <a class="page-link" href="{{ route('home', [
                                            'page' => ($page - 10) <= 1 ? 1 : $page - 10
                                        ]) }}"><<</a>
                                </li>
                                @for ($iCounter = ($page >= 6 ? ($page - 4) : 1); $iCounter <= $totalPage; $iCounter++)
                                    @if ($iCounter <= ($page < 6 ? 10 : $page + 5))
                                        <li class="page-item"><a class="page-link {{ $page === $iCounter ? 'active' : ''}}" href="{{ route('home', ['page' => $iCounter])}}">{{ $iCounter }}</a></li>
                                    @endif
                                @endfor
                                <li class="page-item {{(($page + 10) >= $totalPage ? 'disabled' : '')}}">
                                    <a class="page-link" href="{{ route('home', ['page' => ($page + 10) >= $totalPage ? $totalPage : $page + 10])}}">>></a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
