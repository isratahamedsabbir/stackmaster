@extends('backend.app', ['title' => 'Create Project'])

@push('styles')

@endpush

@section('content')

<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Project</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Project</a></li>
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
                                    <form class="form-horizontal" method="post" action="{{ route('admin.project.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row mb-4">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="type_id" class="form-label">Type:</label>
                                                        <select class="form-control @error('type_id') is-invalid @enderror" name="type_id" id="type_id">
                                                            <option>Select a Type ID</option>
                                                            @if(!empty($types) && $types->count() > 0)
                                                            @foreach($types as $type)
                                                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                        @error('type_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" id="name" value="{{ old('name') ?? '' }}" required>
                                                @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="title" class="form-label">Title:</label>
                                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Title" id="title" value="{{ old('title') ?? '' }}" required>
                                                @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="icon" class="form-label">Icon:</label>
                                                        <input type="file" data-default-file="{{ url('default/logo.png') }}" class="dropify form-control @error('icon') is-invalid @enderror" name="icon" id="icon" required>
                                                        <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                        @error('icon')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="thumbnail" class="form-label">Thumbnail:</label>
                                                        <input type="file" data-default-file="{{ url('default/logo.png') }}" class="dropify form-control @error('thumbnail') is-invalid @enderror" name="thumbnail" id="thumbnail">
                                                        <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                        @error('thumbnail')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="file" class="form-label">File:</label>
                                                        <input type="file" data-default-file="{{ url('default/logo.png') }}" class="dropify form-control @error('file') is-invalid @enderror" name="file" id="file">
                                                        <p class="textTransform">Image Size Less than 25MB and Image Type must be zip.</p>
                                                        @error('file')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="description" class="form-label">Description:</label>
                                                <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Enter here description" rows="5">{{ old('description') ?? '' }}</textarea>
                                                @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="credintials" class="form-label">Credintials:</label>
                                                <textarea class="summernote form-control @error('credintials') is-invalid @enderror" name="credintials" id="credintials" placeholder="Enter here credintials" rows="5">{{ old('credintials') ?? '' }}</textarea>
                                                @error('credintials')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="technologies" class="form-label">Technologies:</label>
                                                <textarea class="summernote form-control @error('technologies') is-invalid @enderror" name="technologies" id="technologies" placeholder="Enter here technologies" rows="5">{{ old('technologies') ?? '' }}</textarea>
                                                @error('technologies')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="features" class="form-label">Features:</label>
                                                <textarea class="summernote form-control @error('features') is-invalid @enderror" name="features" id="features" placeholder="Enter here features" rows="5">{{ old('features') ?? '' }}</textarea>
                                                @error('features')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="features" class="form-label">Note:</label>
                                                <textarea class="summernote form-control @error('note') is-invalid @enderror" name="note" id="note" placeholder="Enter here note" rows="5">{{ old('note') ?? '' }}</textarea>
                                                @error('note')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="backend" class="form-label">Backend Web Url:</label>
                                                <input type="text" class="form-control @error('backend') is-invalid @enderror" name="backend" placeholder="backend" id="backend" value="{{ old('backend') ?? '' }}">
                                                @error('backend')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="frontend" class="form-label">Frontend Web Url:</label>
                                                <input type="text" class="form-control @error('frontend') is-invalid @enderror" name="frontend" placeholder="frontend" id="frontend" value="{{ old('frontend') ?? '' }}">
                                                @error('frontend')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="github" class="form-label">Github:</label>
                                                <input type="text" class="form-control @error('github') is-invalid @enderror" name="github" placeholder="github" id="github" value="{{ old('github') ?? 'https://github.com' }}" required>
                                                @error('github')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="start_date" class="form-label">Start Date:</label>
                                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" placeholder="start date" id="start_date" value="{{ old('start_date') ?? now()->format('Y-m-d') }}" required>
                                                    @error('start_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="end_date" class="form-label">End Date:</label>
                                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" placeholder="end_date" id="end_date" value="{{ old('end_date') ?? now()->format('Y-m-d') }}" required>
                                                    @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <h4>Metadata</h4>
                                            <div class="form-group">
                                                <div id="key-value-pair-container">
                                                    <div class="key-value-pair">
                                                        <div class="row mt-2">
                                                            <div class="col-md-4">
                                                                <input type="text" name="key[]" class="form-control" placeholder="key" required />
                                                            </div>
                                                            <div class="col-md-7">
                                                                <input type="text" name="value[]" class="form-control" placeholder="value" required />
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-danger remove-pair"> - </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-12">
                                                        <button type="button" id="add-key-value" class="btn btn-success">+ Add Metadata</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit">Submit</button>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("key-value-pair-container");

        // Add new key-value pair
        document.getElementById("add-key-value").addEventListener("click", function() {
            const newPair = document.createElement("div");
            newPair.classList.add("key-value-pair");

            newPair.innerHTML = `
                <div class="row mt-2">
                    <div class="col-md-4">
                        <input type="text" name="key[]" class="form-control" placeholder="key" required />
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="value[]" class="form-control" placeholder="value" required />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-pair"> - </button>
                    </div>
                </div>
            `;

            container.appendChild(newPair);
        });

        // Remove key-value pair
        container.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-pair")) {
                e.target.closest(".key-value-pair").remove();
            }
        });
    });
</script>
@endpush