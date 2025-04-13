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
                            <li class="breadcrumb-item active text-dark" aria-current="page">User</li>
                        </ol>
                    </nav>
                    <h1 class="mb-0 fw-bold">User</h1>
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

                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary mb-3" href="{{ route('admin.UserCreate') }}">Add User</a>
                            </div>

                            <div class="table-responsive">
                                <table id="user" class="table table-hover data">
                                    <thead>
                                        <tr>
                                            <th class="text-dark" scope="col">No</th>
                                            <th class="text-dark" scope="col">Email</th>
                                            <th class="text-dark" scope="col">Name</th>
                                            <th class="text-dark" scope="col">Role</th>
                                            <th class="text-dark" scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($users as $data)
                                            <tr>
                                                <th class="text-center pointer" scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-start pointer">{{ $data['email'] }}</td>
                                                <td class="text-start pointer">{{ $data['name'] }}</td>
                                                <td class="text-start pointer">{{ $data['role'] }}</td>
                                                <td class="justify-content-center">
                                                    <a href="{{ route('admin.UserEdit', $data['id']) }}"
                                                        class="btn btn-warning me-3">Edit</a>
                                                    <button type="button" class="btn pointer btn-danger text-white"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal-{{ $data['id'] }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- modal delete --}}
                                            <div class="modal" id="deleteModal-{{ $data['id'] }}" tabindex="-1"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel1">
                                                <div class="modal-dialog modal-md modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-body-secondary">
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
                                                                        action="{{ route('admin.UserDelete', $data['id']) }}"
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
            $('#user').DataTable();
        });
    </script>
@endsection
