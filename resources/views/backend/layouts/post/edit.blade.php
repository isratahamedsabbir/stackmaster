@extends('backend.app', ['title' => 'Update Post'])

@section('content')

<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Post</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Post</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">{{ Str::limit($post->title, 50) }}</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body border-0">
                            <form class="form form-horizontal" method="POST" action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title" class="form-label">Title:</label>
                                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter here title" id="title" value="{{ $post->title ?? '' }}">
                                                @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="content" class="form-label">Content:</label>
                                                <textarea class="summernote form-control @error('content') is-invalid @enderror" name="content" id="description" placeholder="Enter here content" rows="3">{{ $post->content ?? '' }}</textarea>
                                                @error('content')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category_id" class="form-label">Category:</label>
                                                <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                                    <option value="">Select a Category ID</option>
                                                    @if(!empty($categories) && $categories->count() > 0)
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('category_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="subcategory_id" class="form-label">Subcategory:</label>
                                                <select class="form-control @error('subcategory_id') is-invalid @enderror" name="subcategory_id" id="subcategory_id">
                                                    @if(!empty($subcategories) && $subcategories->count() > 0)
                                                    @foreach($subcategories as $subcategory)
                                                        @if($post->subcategory_id == $subcategory->id)
                                                            <option value="{{ $subcategory->id }}" selected >{{ $subcategory->name }}</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('subcategory_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="thumbnail" class="form-label">Thumbnail:</label>
                                                <input type="file" data-default-file="{{ $post->thumbnail && file_exists(public_path($post->thumbnail)) ? url($post->thumbnail) : url('default/logo.png') }}" class="dropify form-control @error('thumbnail') is-invalid @enderror" name="thumbnail" id="thumbnail">
                                                <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                @error('thumbnail')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="images" class="form-label">Images:</label>
                                                <input type="file" class="dropify form-control @error('images') is-invalid @enderror" data-default-file="{{ url('default/logo.png') }}" name="images[]" id="images" multiple>
                                                <p class="textTransform">Image Size Less than 5MB and Image Type must be jpeg,jpg,png.</p>
                                                @error('images')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div id="image_load"></div>
                                    </div>

                                    <div class="form-group">
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
<!-- CONTAINER CLOSED -->
@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#category_id').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $('select[name="category_id"]').on('change', function() {
            NProgress.start();
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: `{{ route('ajax.subcategory', ':category_id') }}`.replace(':category_id', category_id),
                    type: "GET",
                    success: function(res) {
                        NProgress.done();
                        let $html = '<option value="">Select a Subcategory ID</option>';
                        res.data.subcategories.forEach(function(subcategory) {
                            $html += '<option value="' + subcategory.id + '">' + subcategory.name + '</option>';
                        });
                        if ($('select[name="subcategory_id"]').val() != '') {
                            $('select[name="subcategory_id"]').html($html).val($('select[name="subcategory_id"]').val());
                        } else {
                            $('select[name="subcategory_id"]').html($html);
                        }
                    }
                });
            } else {
                $('select[name="subcategory_id"]').empty();
            }
        });
    });
</script>
<script>
    function imagesLoad(post_id) {
        $('#image_load').empty();
        $.ajax({
            url: `{{ route('admin.post.image.index', ':post_id') }}`.replace(':post_id', post_id),
            type: 'GET',
            success: function(resp) {
                let html = '';
                $.each(resp.data.images, function(key, image) {
                    let image_src = image.path ? "{{ asset('') }}/" + image.path : '{{ asset("default/logo.png") }}';
                    html += `<div style="width: 100px; height: 100px; display: inline-block; position: relative; margin: 5px;">
                                <a href="` + image_src + `" target="_blank"><img src="` + image_src + `" alt="post image"></a>
                                <i class="fa fa-trash" style="cursor: pointer; position: absolute; top: 0; right: 0; background-color: #fff; padding: 5px; border-radius: 50%; color: red; margin: 5px;" onclick="deleteImage(` + image.id + `)"></i>
                            </div>`;
                });
                $('#image_load').html(html);
            },
            error: function(resp) {
                $('#image_load').html(resp.message);
            }
        });
    }

    imagesLoad(`{{ $post->id }}`);

    function deleteImage(imageId) {
        if (confirm("Are you sure you want to delete this image?")) {
            NProgress.start();
            $.ajax({
                url: `{{ route('admin.post.image.destroy', ':id') }}`.replace(':id', imageId),
                type: 'GET',
                success: function(resp) {
                    NProgress.done();
                    imagesLoad(resp.post_id);
                    toastr.success(resp.message);
                },
                error: function(resp) {
                    NProgress.done();
                    toastr.error(resp.message);
                }
            });
        }
    }
</script>
@endpush