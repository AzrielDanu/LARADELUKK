@extends('components.navbar')

@section('container')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.ProductHome') }}" class="link">Product</i></a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Add Product</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Add Product</h1> 
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.ProductStore') }}" enctype="multipart/form-data" method="POST" class="row g-3">
                            @csrf
                            @if (Session::get('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <div class="col-md-6">
                                <label for="name" class="form-label" required>Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"name="name" id="name">
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label" required>Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="image" id="image" required accept=".png, .jpg, .jpeg, .svg">
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label" required>Price <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="price" id="price">
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label" required>Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" id="stock">
                            </div>

                            <div class="col-12 d-flex justify-content-end my-3">
                                <button type="submit" class="btn btn-primary w-25">Add Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<script src="{{ asset('js/priceFormatter.js') }}"></script>
@endsection