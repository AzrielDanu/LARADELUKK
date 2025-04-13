@extends('components.navbar')

@section('container')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-flex align-items-center">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="link"><i
                                        class="mdi mdi-home-outline fs-4"></i></a></li>
                            <li class="breadcrumb-item active text-dark" aria-current="page">Product</li>
                        </ol>
                    </nav>
                    <h1 class="mb-0 fw-bold">Product</h1>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if (Session::get('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                            @if (Session::get('deleted'))
                                <div class="alert alert-warning">{{ Session::get('deleted') }}</div>
                            @endif
                            @if (Session::get('failed'))
                                <div class="alert alert-warning">{{ Session::get('deleted') }}</div>
                            @endif

                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary mb-3" href="{{ route('admin.ProductCreate') }}">Add Product</a>
                            </div>

                            <div class="table-responsive">
                                <table id="products" class="table table-hover data">
                                    <thead>
                                        <tr>
                                            <th class="text-dark" scope="col">No</th>
                                            <th class="text-dark" scope="col"></th>
                                            <th class="text-dark" scope="col">Name Product</th>
                                            <th class="text-dark" scope="col">Price</th>
                                            <th class="text-dark" scope="col">Stock</th>
                                            <th class="text-dark" scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($products as $data)
                                            <tr>
                                                <th class="text-center pointer" scope="row">{{ $loop->iteration }}</th>
                                                <td class="pointer">
                                                    <img src="{{ asset('storage/' . $data->image) }}"
                                                        alt="{{ $data->name }}" width="75">
                                                </td>
                                                <td class="text-start pointer">{{ $data['name'] }}</td>
                                                <td class="text-start pointer">{{ formatRupiah($data->price) }}</td>
                                                <td class="text-start pointer">{{  $data->stock }}</td>
                                                <td class="justify-content-center">
                                                    <a href="{{ route('admin.ProductEdit', $data['id']) }}"
                                                        class="btn btn-warning me-3">Edit</a>
                                                    <button type="button"
                                                        class="btn pointer btn-primary me-3 text-white"
                                                        id="update-stok-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updateModal-{{ $data['id'] }}"
                                                        data-id="{{ $data['id'] }}"
                                                        data-name="{{ $data['name'] }}"
                                                        data-stock="{{ $data['stock'] }}">
                                                        Update Stock
                                                    </button>
                                                    <button type="button" class="btn pointer btn-danger text-white"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal-{{ $data['id'] }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- modal stock --}}
                                            <div class="modal" id="updateModal-{{ $data['id'] }}" tabindex="-1"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel1">
                                                <div class="modal-dialog modal-md modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-body-secondary">
                                                            <div class="d-flex justify-content-center w-100">
                                                                <h5 class="modal-title fw-semibold"
                                                                    id="exampleModalToggleLabel1">
                                                                    Update Stock "<span
                                                                        class="text-danger">{{ $data['name'] }}</span>"
                                                                </h5>
                                                            </div>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="bg-white p-2">
                                                                <div
                                                                    class="align-items-center justify-content-center gap-3">

                                                                    <form method="POST" id="form-stock-{{ $data['id'] }}" action="{{ route('admin.ProductStock', $data['id']) }}">
                                                                        @csrf
                                                                        @method('PUT')
                                                                    
                                                                        <div class="col-md-12 mb-3">
                                                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control" name="name" id="name" value="{{ $data['name'] }}" disabled>
                                                                        </div>
                                                                    
                                                                        <div class="col-md-12 mb-3">
                                                                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                                                            <input type="number" class="form-control" name="stock" id="stock-{{ $data['id'] }}" value="{{ $data['stock'] }}">
                                                                            <small class="text-danger d-none" id="error-stock-{{ $data['id'] }}">Stock baru harus lebih besar dari {{ $data['stock'] }}</small>
                                                                        </div>
                                                                    
                                                                        <div class="d-flex justify-content-end gap-2 pt-3 mt-3">
                                                                            <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary">Cancel</button>
                                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                                        </div>
                                                                    </form>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- modal delete --}}
                                            <div class="modal" id="deleteModal-{{ $data['id'] }}" tabindex="-1"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel1">
                                                <div class="modal-dialog modal-md modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-body-secondary border">
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="bg-white p-2">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-center gap-3">
                                                                    <h6 class="mb-0 text-md">
                                                                        Are you sure you want to delete this "<span
                                                                            class="text-danger">{{ $data['name'] }}</span>"
                                                                        permanently?
                                                                    </h6>
                                                                </div>
                                                                <div class="d-flex justify-content-end gap-2 pt-3 mt-3">
                                                                    <button type="button" data-bs-dismiss="modal"
                                                                        class="btn btn-outline-secondary">
                                                                        Cancel
                                                                    </button>
                                                                    <form
                                                                        action="{{ route('admin.ProductDelete', $data['id']) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger text-white">Hapus</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("form[id^='form-stock']").submit(function(e) {
                e.preventDefault();
    
                let id = $(this).find("[name='id']").val();
                let oldStock = parseInt($("#stock-" + id).attr("value"));
                let newStock = parseInt($("#stock-" + id).val());
    
                if (newStock <= oldStock) {
                    $("#error-stock-" + id).removeClass("d-none");
                    return false;
                }
    
                $(this).unbind('submit').submit();
            });
        });
    </script>
    
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css"></script>

    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            $('#products').DataTable();
        });
    </script>
@endsection
