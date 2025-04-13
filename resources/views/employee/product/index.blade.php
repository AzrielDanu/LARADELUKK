@extends('components.navbar')

@section('container')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row align-items-center">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-flex align-items-center">
                            <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}" class="link"><i
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
                            <div class="table-responsive">
                                <table id="products" class="table table-hover data">
                                    <thead>
                                        <tr>
                                            <th class="text-dark" scope="col">No</th>
                                            <th class="text-dark" scope="col"></th>
                                            <th class="text-dark" scope="col">Name Product</th>
                                            <th class="text-dark" scope="col">Price</th>
                                            <th class="text-dark" scope="col">Stock</th>
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
                                            </tr>
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
