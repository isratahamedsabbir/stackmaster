@extends('backend.app', ['title' => 'Edit Project'])

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
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">

                    <div class="tab-content">
                        <div class="tab-pane active show" id="editProfile">
                            <div class="card">
                                <div class="card-body border-0">
                                    <form class="form-horizontal" method="post" action="{{ route('admin.project.update', $project->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="row mb-4">

                                            <div class="form-group">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" id="name" value="{{ old('name', $project->name) }}" required>
                                                @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="image" class="form-label">Image:</label>
                                                        <input type="file" data-default-file="{{ $project->image && file_exists(public_path($project->image)) ? url($project->image) : url('default/logo.png') }}" class="dropify form-control @error('image') is-invalid @enderror" name="image" id="image">
                                                        @error('image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="file" class="form-label">File:</label>
                                                        <input type="file" data-default-file="{{ $project->file && file_exists(public_path($project->file)) ? url($project->file) : url('default/logo.png') }}" class="dropify form-control @error('file') is-invalid @enderror" name="file" id="file">
                                                        @error('file')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="description" class="form-label">Description:</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Enter here description" rows="5" required>{{ old('description', $project->description) }}</textarea>
                                                @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="credintials" class="form-label">Credintials:</label>
                                                <textarea class="form-control @error('credintials') is-invalid @enderror" name="credintials" id="credintials" placeholder="Enter here credintials" rows="5" required>{{ old('credintials', $project->credintials) }}</textarea>
                                                @error('credintials')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="url" class="form-label">Live Url:</label>
                                                <input type="text" class="form-control @error('url') is-invalid @enderror" name="url" placeholder="url" id="url" value="{{ old('url', $project->url) }}" required>
                                                @error('url')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="github" class="form-label">Github:</label>
                                                <input type="text" class="form-control @error('github') is-invalid @enderror" name="github" placeholder="github" id="github" value="{{ old('github', $project->github) }}" required>
                                                @error('github')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <h4>Metadata</h4>
                                            <div class="form-group">
                                                <div id="key-value-pair-container">
                                                    @foreach (json_decode($project->metadata, true) as $key => $value)
                                                        <div class="key-value-pair">
                                                            <div class="row mt-2">
                                                                <div class="col-md-4">
                                                                    <input type="text" name="key[]" class="form-control" placeholder="key" value="{{ $key }}" required />
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <input type="text" name="value[]" class="form-control" placeholder="value" value="{{ $value }}" required />
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger remove-pair"> - </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
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
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("key-value-pair-container");

        // Add new key-value pair
        document.getElementById("add-key-value").addEventListener("click", function () {
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
        container.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-pair")) {
                e.target.closest(".key-value-pair").remove();
            }
        });
    });
</script>
@endpush