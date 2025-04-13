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
                            <a href="{{ route('employee.PurchaseIndex') }}" class="link">Add Purchase</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.PurchaseStore') }}" class="link">Payment</a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">Invoice</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Invoice</h1> 
            </div>
        </div>
    </div>

    <style>
        .invoice-price {
            background: #f0f3f4;
            display: table;
            width: 100%
        }

        .invoice-price .invoice-price-left,
        .invoice-price .invoice-price-right {
            display: table-cell;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            width: 75%;
            position: relative;
            vertical-align: middle
        }

        .invoice-price .invoice-price-left .sub-price {
            display: table-cell;
            vertical-align: middle;
            padding: 0 20px
        }

        .invoice-price small {
            font-size: 12px;
            font-weight: 400;
            display: block
        }

        .invoice-price .invoice-price-row {
            display: table;
            float: left
        }

        .invoice-price .invoice-price-right {
            width: 25%;
            background: #2d353c;
            color: #fff;
            font-size: 28px;
            text-align: right;
            vertical-align: bottom;
            font-weight: 300
        }

        .invoice-price .invoice-price-right small {
            display: block;
            opacity: .6;
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px
        }
    </style>
    <div class="row bg-light px-3 py-4 gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card p-4">
                <div class="card-body p-0">
                    <div class="invoice-container">
                        <div class="invoice-header">
                            <!-- Row start -->
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="custom-actions-btns mb-5">
                                        <a href="{{ route('employee.ExportPDF', $purchase->id) }}" class="btn btn-primary">
                                            <i class="icon-download"></i> Download
                                        </a>
                                        <a href="{{ route('employee.PurchaseIndex') }}" class="btn btn-secondary">
                                            <i class="icon-printer"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details">
                                        <address>
                                            @if ($purchase->member)
                                                <b>{{ $purchase->member->phone }}</b><br>
                                                MEMBER SINCE :
                                                {{-- liya aku nambahin ini yang carbon--}}
                                                {{ \Carbon\Carbon::parse($purchase->member->created_at)->format('d F Y') }}
                                                <br>
                                                MEMBER POIN : {{ $purchase->member->point }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                    <div class="invoice-details">
                                        <div class="invoice-num">
                                            <div>Invoice - #{{ $purchase->id }}</div>
                                            <div>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row end -->
                        </div>
                        <div class="invoice-body">
                            <!-- Row start -->
                            <div class="row gutters">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table custom-table m-0">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Sub Total</th>
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
                                    </div>
                                </div>
                            </div>
                            <!-- Row end -->
                        </div>
                        <div class="invoice-price">
                            <div class="invoice-price-left">
                                <div class="invoice-price-row">
                                    <div class="sub-price">
                                        <small>USED POIN</small>
                                        <span class="text-inverse">{{ $purchase->used_point }}</span>
                                    </div>
                                    <div class="sub-price">
                                        <small>CASHIER</small>
                                        <span class="text-inverse">{{ $purchase->user->name }}</span>
                                    </div>
                                    <div class="sub-price">
                                        <small>TOTAL PAY</small>
                                        <span class="text-inverse">Rp
                                            {{ number_format($purchase->total_payment, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="sub-price">
                                        <small>CHANGE</small>
                                        <span class="text-inverse">Rp
                                            {{ number_format($purchase->change, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-price-right">
                                <small>TOTAL</small>
                                @if ( $purchase->used_point > 0 )
                                    <h2 class="text-white text-end me-5 text-decoration-line-through">Rp. {{ number_format($purchase->total_price + $purchase->used_point, 0, ',', '.') }}</h2>
                                    <h2 class="text-white text-end me-5">Rp. {{ number_format($purchase->total_price, 0, ',', '.') }}</h2>
                                @else
                                    <h2 class="text-white text-end me-5">Rp. {{ number_format($purchase->total_price, 0, ',', '.') }}</h2>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection