@php
$url = 'admin.cms.'.$page.'.'.$section;
@endphp

@extends('backend.app', ['title' => 'Create Banner'])

@section('content')

<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">CMS : {{ ucfirst($page ?? '') }} Page {{ ucfirst($section ?? '') }} Section Create.</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">CMS</li>
                        <li class="breadcrumb-item">{{ ucfirst($page ?? '') }}</li>
                        <li class="breadcrumb-item">{{ ucfirst($section ?? '') }}</li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">

                    <div class="tab-content">
                        <div class="tab-pane active show" id="editProfile">
                            <div class="card">
                                <div class="card-body border-0">
                                    <form class="form form-horizontal" method="POST" action="{{ route($url.'.store') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row {{ in_array('metadata', $components) ? '' : 'd-none' }}">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="rating" class="form-label">Rating:</label>
                                                    <input type="number" class="form-control @error('rating') is-invalid @enderror" name="rating" placeholder="Enter here rating" id="rating" value="{{ old('rating') }}" min="1" max="5">
                                                    @error('rating')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row {{ in_array('title', $components) ? '' : 'd-none' }}">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title" class="form-label">Title:</label>
                                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter here title" id="title" value="{{ old('title') }}">
                                                    @error('title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row {{ in_array('description', $components) ? '' : 'd-none' }}">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description" class="form-label">Description:</label>
                                                    <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Enter here description" rows="3">{{ old('description') }}</textarea>
                                                    @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row {{ in_array('sub_description', $components) ? '' : 'd-none' }}">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="sub_description" class="form-label">Sub Description:</label>
                                                    <textarea class="summernote form-control @error('sub_description') is-invalid @enderror" name="sub_description" id="sub_description" placeholder="Enter here sub description" rows="3">{{ old('sub_description') }}</textarea>
                                                    @error('sub_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 {{ in_array('btn_text', $components) ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="btn_text" class="form-label">Button Text:</label>
                                                    <input type="text" class="form-control @error('btn_text') is-invalid @enderror" name="btn_text" placeholder="Enter here button text" id="btn_text" value="{{ old('btn_text') }}">
                                                    @error('btn_text')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{ in_array('btn_link', $components) ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="btn_link" class="form-label">Button Link:</label>
                                                    <input type="text" class="form-control @error('btn_link') is-invalid @enderror" name="btn_link" placeholder="Enter here link" id="btn_link" value="{{ old('btn_link') }}">
                                                    @error('btn_link')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{ in_array('btn_color', $components) ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="btn_color" class="form-label">Button Color:</label>
                                                    <input type="color" class="form-control @error('btn_color') is-invalid @enderror" name="btn_color" placeholder="Enter here Color" id="btn_link" value="{{ old('btn_color') }}">
                                                    @error('btn_color')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 {{ in_array('image', $components) ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="image" class="form-label">Side Image:</label>
                                                    <input type="file" class="dropify @error('image') is-invalid @enderror" name="image"
                                                        id="image"
                                                        data-default-file="">
                                                    <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                    @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 {{ in_array('bg', $components) ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="bg" class="form-label">Background:</label>
                                                    <input type="file" class="dropify @error('bg') is-invalid @enderror" name="bg"
                                                        id="bg"
                                                        data-default-file="">
                                                    <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                    @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12 text-center">
                                                <button class="submit btn btn-primary" type="submit">Submit</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
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