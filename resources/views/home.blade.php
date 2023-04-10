@extends('layouts.app')

@section('content')
@php
    $page = (int)$page;
    $totalPage = $count / 10;
    $maxPagePerGroup = 7;
    $pageToShow = 3;
    $midPage = 5;
@endphp
<div class="container">
    <div id="modalBackDrop" class="modal-backdrop fade" style="display: none;"></div>
    <div id="loader" class="mx-auto" style="
        z-index: 1056;
        position: fixed;
        top: 48%;
        left: 0px;
        width: 100%;
        height: 100%;
        display: none;
    ">
        <div  class="text-center">
            <div class="text-warning spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Importing data...
                    </h1>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <div>
                            <i class='fas fa-exclamation-circle' style='font-size:14px;color:#664d03'></i>
                        </div>
                    
                        <div class="ms-3 lh-sm">
                            Don't close your browser to keep track of your progress and please wait before importing again.
                        </div>
                    </div>
                    <div id="progressAnimation" class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Manage Import') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
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
                                            <input class="form-control form-control-sm" id="importFile" name="import_file" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" aria-labelledby="importHelpBlock">
                                            <div id="importHelpBlock" class="form-text">
                                                - Accepts only .xls and .xlsx file type. <br>
                                                - Maximum of 5mb per upload.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button id="importButton" type="submit" class="btn btn-primary"><i class='fas fa-upload' style='font-size:14px;color:white'></i> Import</button>
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
                                        <p class="card-text mb-0" aria-labelledby="exportHelpBlock">Export the current page or all your data.</p>
                                        <div id="importHelpBlock" class="form-text mb-4">
                                            Download your data to manage it outside of the app.
                                        </div>
                                        <input type="hidden" name="page" value="1">
                                        <div class="d-flex flex-row">
                                            <div class="p-2">
                                                <button type="submit" id="exportPageButton" name="export_type" value="page" class="btn btn-primary">
                                                    <i class='fas fa-download' style='font-size:14px;color:white'></i> 
                                                    Export Current Page
                                                </button>
                                            </div>
                                            <div class="p-2">
                                                <button type="submit" id="exportAllButton" name="export_type" value="all" class="btn btn-primary">
                                                    <i class='fas fa-download' style='font-size:14px;color:white'></i> 
                                                    Export All
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container text-center">
                        <div class="row">
                            <div class="col">
                                <div class="col d-grid gap-2 d-md-flex justify-content-md-start mt-4 text-start align-text-bottom">
                                    <strong>Total Rows:</strong> {{$count}}
                                </div>
                            </div>
                            <div class="col">
                                <div class="col d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                    <form action="{{ route('delete') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="page" value="{{ $page }}">
                                        <input type="hidden" name="type" value="all">
                                        <button id="deleteAllButton" type="submit" class="btn btn-danger">
                                            <i class='far fa-trash-alt' style='font-size:14px;color:white'></i> Delete all
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table align-middle">
                            <thead class="thead ">
                                <tr class="table-light">
                                    <th scope="col-3">Name</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Email</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table">
                                @foreach ($imports as $aImport)
                                    <tr>
                                        <td>
                                            {{ $aImport['first_name'] }} {{$aImport['middle_name']}} {{ $aImport['last_name'] }}
                                        </td>
                                        <td>{{ $aImport['address_street'] }} {{ $aImport['address_brgy'] }} {{ $aImport['address_city'] }} {{ $aImport['address_province'] }}</td>
                                        <td>{{ $aImport['contact_phone'] === '' ? 'N/A' : $aImport['contact_phone'] }}</td>
                                        <td>{{ $aImport['contact_mobile'] }}</td>
                                        <td>{{ $aImport['email'] }}</td>
                                        <td>
                                            <div class="d-flex flex-row">
                                                <div class="p">
                                                    <form action="{{ route('view-edit', [
                                                        'import_id' => $aImport['import_id'],
                                                        'page'      => $page,
                                                        ]) }}" method="GET" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="page" value="{{ $page }}">
                                                        <input type="hidden" name="import_id" value="{{ $aImport['import_id'] }}">
                                                        <button type="submit" name="editRow" class="btn text-primary"
                                                                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                                <i class='fas fa-edit'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="p">
                                                    <form action="{{ route('delete') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="page" value="{{ $page }}">
                                                        <input type="hidden" name="email" value="{{ $aImport['email'] }}">
                                                        <input type="hidden" name="type" value="single">
                                                        <button type="submit" name="import_id" value="{{ $aImport['import_id'] }}" class="btn text-danger"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                            <i class='far fa-trash-alt'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if (count($imports) <= 0)
                        <div class="alert alert-dark mt-2 text-center" role="alert">
                            No results found. You can import data using .xls and .xlsx files.
                        </div>
                    @endif
                    @if($count > 10)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{(($page  - $maxPagePerGroup) <= 0 ? 'disabled' : '')}}">
                                    <a class="page-link" href="{{ route('home', [
                                            'page' => ($page - $maxPagePerGroup) <= 1 ? 1 : $page - $maxPagePerGroup
                                        ]) }}"><<</a>
                                </li>
                                @for ($iCounter = ($page >= $midPage ? ($page - $pageToShow) : 1); $iCounter <= $totalPage; $iCounter++)
                                    @if ($iCounter <= ($page < $midPage ? $maxPagePerGroup : $page + $pageToShow))
                                        <li class="page-item"><a class="page-link {{ $page === $iCounter ? 'active' : ''}}" href="{{ route('home', ['page' => $iCounter])}}">{{ $iCounter }}</a></li>
                                    @endif
                                @endfor
                                <li class="page-item {{(($page + $maxPagePerGroup) >= $totalPage ? 'disabled' : '')}}">
                                    <a class="page-link" href="{{ route('home', ['page' => ($page + $maxPagePerGroup) >= $totalPage ? $totalPage : $page + $maxPagePerGroup])}}">>></a>
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
@section('script')
    @vite(['resources/js/checkProgress.js', 'resources/js/showLoader.js'])
@endsection
