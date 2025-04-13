@extends('components.navbar')

@section('container')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.dashboard') }}" class="link">
                                <i class="mdi mdi-home-outline fs-4"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.PurchaseIndex') }}" class="link">Purchase</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.PurchaseCreate') }}" class="link">Add Purchase</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.PurchasePayment') }}" class="link">Payment</a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Member</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Member</h1> 
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <div class="row">
                            <!-- Kiri: Rincian Produk -->
                            <div class="col-md-6 mb-4">
                                <div class="border border-black rounded p-3 h-100">
                                    <table class="table mb-4">
                                        <thead>
                                            <tr>
                                                <th><b>Product</b></th>
                                                <th><b>QTY</b></th>
                                                <th><b>Price</b></th>
                                                <th><b>Sub Total</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detail_purchase as $data)
                                            <tr class="service">
                                                <td class="tableitem">
                                                    <p class="itemtext">{{ $data->product->name }}</p>
                                                </td>
                                                <td class="tableitem">
                                                    <p class="itemtext">Rp.
                                                        {{ number_format($data->product->price, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                <td class="tableitem">
                                                    <p class="itemtext">{{ $data->quantity }}</p>
                                                </td>
                                                <td class="tableitem">
                                                    <p class="itemtext">Rp.
                                                        {{ number_format($data->sub_total, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Total Price</strong>
                                        <strong>Rp. {{ number_format($purchase->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total Payment</strong>
                                        <strong>Rp. Rp. {{ number_format($purchase->total_payment, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 h-100">
                                    <form action="{{ route('employee.Member', $purchase['id']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                                        <input type="hidden" name="member_id" value="{{$purchase->member->id}}">
                                        <div class="mb-3">
                                            <label for="name" class="form-label fw-bold">Member Name</label>
                                        {{-- <pre>{{ dd($purchase->member->name) }}</pre> --}}
                                            
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $purchase->member->name ?? '') }}" required>
                                        </div>

                                        {{-- <pre>{{ dd($purchase->member) }}</pre> --}}

                                        <div class="mb-3">
                                            <label for="point" class="form-label fw-bold">Point</label>
                                            <input type="text" class="form-control bg-light" name="point" id="point" value="{{ $purchase->member ? $purchase->member->point : '' }}" readonly>
                                        </div>

                                        <div class="form-check mb-4">
                                            <input class="form-check-input" value="Ya" type="checkbox" id="check_point" name="check_point" {{ $isFirst ? 'disabled' : ''}}>
                                            <label class="form-check-label" for="check_point">
                                                Use point
                                            </label>
                                            @if ($isFirst)
                                                <small class="text-danger">Can't use point because is your first purchase.</small>
                                            @endif
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Next</button>
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
@endsection
