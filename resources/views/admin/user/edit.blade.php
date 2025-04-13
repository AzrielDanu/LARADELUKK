@extends('components.navbar')
    
@section('container')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.UserHome') }}" class="link">User</i></a></li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Edit User</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Edit User</h1> 
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.UserUpdate', $users['id']) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PATCH')

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
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control"name="email" id="email" value="{{ $users['email'] }}">
                            </div>                         
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $users['name'] }}">
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label" required>Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" id="role" required>
                                    <option value="" selected disabled>Select Role</option>
                                    <option value="admin" {{ $users['role'] == 'admin' ? 'selected' :  '' }}>Admin</option>
                                    <option value="employee" {{ $users['role'] == 'employee' ? 'selected' :  '' }}>Employee</option>
                                </select>
                            </div> 
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                            

                            <div class="col-12 d-flex justify-content-end my-3">
                                <button type="submit" class="btn btn-primary w-25">Edit Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection