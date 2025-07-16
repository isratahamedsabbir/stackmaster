@extends('backend.app', ['title' => 'Menu'])

@push('styles')
<link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />  
@endpush


@section('content')
<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">


            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Menu</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-4 -->
            <div class="row">
                <div class="col-6 col-sm-6">
                    <div class="card product-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">List</h3>
                        </div>
                        <div class="card-body">

                            @if($menus->count() > 0)
                                <ul class="list-group">
                                    @foreach($menus as $menu)
                                        <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="mb-0">{{ $menu->name }}</h5>
                                                <p class="text-muted mb-0">{{ $menu->description }}</p>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-0">No data found</h5>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div><!-- COL END -->

                <div class="col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between border-bottom">
                            <h3 class="card-title">Content Edit</h3>
                        </div>
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-12">
                                        <x-form.text name="title" label="Title" placeholder="Enter here title" :value="old('title') ?? ''" />
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button class="submit btn btn-primary" type="submit">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ROW-4 END -->

        </div>
    </div>
</div>
<!-- CONTAINER CLOSED -->
@endsection



@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush