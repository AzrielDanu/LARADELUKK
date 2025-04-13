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
                        <li class="breadcrumb-item active text-dark" aria-current="page">Payment</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Payment</h1> 
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <form action="{{ route('employee.paymentProcess') }}" method="POST" class="row g-3">
                            @csrf
                        
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <h4 class="fw-bold mb-4">Produk yang dipilih</h4>
                    
                                    @foreach ($products as $item)
                                        <div class="d-flex justify-content-between align-items-start mt-2">
                                            <div>
                                                <div class="fw-medium">{{ $item['name'] }}</div>
                                                <div class="text-muted small">{{ formatRupiah($item['price']) }} x {{ $item['quantity'] }}</div>
                                            </div>
                                            <div class="fw-bold">{{ formatRupiah($item['sub_total']) }}</div>
                                        </div>
                                        <input type="hidden" name="shop[]"
                                            value="{{ $item['product_id'] . ';' . $item['name'] . ';' . $item['price'] . ';' . $item['quantity'] . ';' . $item['sub_total'] }}"
                                            hidden="">
                                    @endforeach
                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <div class="fw-bold fs-5">Total</div>
                                        <div class="fw-bold fs-5">{{ formatRupiah($total) }}</div>
                                    </div>
                    
                                    <input type="hidden" name="total" value="{{ $total }}">
                                </div>
                    
                                <div class="col-lg-6">
                                    <!-- Member Status -->
                                    {{-- <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="fw-semibold">Member Status</label>
                                        <small class="text-danger">Can create member</small>
                                    </div>
                                    <select class="form-select mb-3" name="member" id="member" required>
                                        <option value="0">Non-Member</option>
                                        <option value="1">Member</option>
                                    </select> --}}
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="member" class="form-label">Member Status</label>
                                            <small class="text-danger">Can create member</small>
                                            <select name="member" id="member" class="form-select"
                                                onchange="memberDetect()">
                                                <option value="Non-Member">Non-Member</option>
                                                <option value="Member">Member</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Nomor HP (hanya untuk member) -->
                                    <div id="member-wrap" class="d-none mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="fw-medium">Number Phone Member</label>
                                            <small class="text-danger">(Regist / Use Member)</small>
                                        </div>
                                        <input type="text" name="phone" id="phone" class="form-control mt-1">
                                    </div>

                                    <!-- Input pembayaran (sama untuk member dan non-member) -->
                                    <div class="mb-3">
                                        <label class="fw-medium" for="total_payment" >Total Payment</label>
                                        <input type="text" id="total_payment" name="total_payment" class="form-control" required>
                                        <small id="warningMessage" class="text-danger d-none">Jumlah bayar kurang.</small>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" id="submitButton" class="btn btn-primary px-4">Order</button>
                                    </div>                                  
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function memberDetect() {
        const detectElement = document.getElementById('member');
        const phone = document.getElementById('member-wrap');
        const is_member = detectElement.value;

        if (is_member == 'Member') {
            phone.classList.remove('d-none');
        } else {
            phone.classList.add('d-none');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const total = {{ $total }};
        const paymentInput = document.getElementById("total_payment");
        const warning = document.getElementById("warningMessage");
        const submitButton = document.getElementById("submitButton");

        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateValidation() {
            const bayar = parseInt(paymentInput.value.replace(/[^0-9]/g, '')) || 0;

            if (bayar < total) {
                warning.classList.remove("d-none");
                submitButton.disabled = true;
            } else {
                warning.classList.add("d-none");
                submitButton.disabled = false;
            }

            paymentInput.value = "Rp. " + formatRupiah(bayar.toString());
        }

        paymentInput.addEventListener("input", updateValidation);
        updateValidation();
    });
</script>

    
@endsection
