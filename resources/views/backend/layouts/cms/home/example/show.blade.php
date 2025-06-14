@php
$url = 'admin.cms.'.$name.'.'.$section;
@endphp

@extends('backend.app', ['title' => $name . ' - ' . $section])

@section('content')

<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">CMS : {{ ucfirst($name ?? '') }} Page {{ ucfirst($section ?? '') }} Section.</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">CMS</li>
                        <li class="breadcrumb-item">{{ ucfirst($name ?? '') }}</li>
                        <li class="breadcrumb-item">{{ ucfirst($section ?? '') }}</li>
                        <li class="breadcrumb-item active" aria-current="page">Show</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Content {{ ucfirst($name ?? '') }}</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Page</th>
                                    <td>
                                        {{ Str::limit($data->page ?? '', 20) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td>
                                        {{ Str::limit($data->section ?? '', 20) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>
                                        {{ Str::limit($data->name ?? '', 20) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>
                                        {{ Str::limit($data->slug ?? '', 20) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>
                                        {{ $data->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sub Title</th>
                                    <td>
                                        {{ $data->sub_title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>
                                        {!! $data->description ?? '' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sub Description</th>
                                    <td>
                                        {{ $data->sub_description ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>BG</th>
                                    <td>
                                        <img src="{{ asset(!empty($data->bg) && file_exists(public_path($data->bg)) ? $data->bg : 'default/logo.svg') }}" style="width: 108px; height: 108px" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Image</th>
                                    <td>
                                        <img src="{{ asset(!empty($data->image) && file_exists(public_path($data->image)) ? $data->image : 'default/logo.svg') }}" style="width: 108px; height: 108px" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Button</th>
                                    <td>
                                        <button onclick="window.open('{{ $data->btn_link ?? '' }}')" style="{{ $data->btn_color ?? '' }}">{{ $data->btn_text ?? '' }}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Metadata</th>
                                    <td>
                                        {{ $item->metadata['rating'] ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- CONTAINER CLOSED -->
@endsection
@push('scripts')
@endpush