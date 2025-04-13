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
                            <li class="breadcrumb-item active text-dark" aria-current="page">Add Purchase</li>
                        </ol>
                    </nav>
                    <h1 class="mb-0 fw-bold">Add Purchase</h1>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center container">
                                <div class="row">
                                    @foreach ($purchases as $data)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card">
                                                <p hidden class="product_id">{{ $data['id'] }}</p>
                                                <div class="bg-image">
                                                    <img src="{{ asset('storage/' . $data['image']) }}" class="w-50 mt-3"
                                                        alt="">
                                                </div>
                                                <div class="card-body">

                                                    <div class="card-title mb-3">{{ $data['name'] }}</div>
                                                    <p>Stock <span class="product_stock">{{ $data['stock'] }}</span></p>
                                                    <h6 class="mb-3 product_price">{{ formatRupiah($data->price) }}</h6>

                                                    <center>
                                                        <table>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding: 0px 10px 0px 10px; cursor: pointer;"
                                                                        class="product_min">
                                                                        <b>-</b>
                                                                    </td>
                                                                    <td style="padding: 0px 10px 0px 10px;"
                                                                        class="product_sum">0
                                                                    </td>
                                                                    <td style="padding: 0px 10px 0px 10px; cursor: pointer;"
                                                                        class="product_plus">
                                                                        <b>+</b>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </center>
                                                    <p class="mt-3">
                                                        Sub Total
                                                        <b class="sub_total">Rp. 0 ,-</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('employee.PurchaseStore') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div id="hidden-inputs"></div>
                    <div class="fixed-bottom bg-white shadow p-3 border-top border-warning w-100 d-flex justify-content-center">
                        <button class="btn btn-primary w-20">Next</button>
                    </div>
                </form>
                
            </div>

            @push('script')
            <script>
                $(".product_plus, .product_min").click(function() {
                    var card = $(this).closest(".card");
                    var quantityElement = card.find(".product_sum");
                    var stock = parseInt(card.find(".product_stock").text().trim()); // Ambil stok
                    var price = parseFloat(card.find(".product_price").text().replace(/[^\d]/g, '')); // Ambil harga
                    var quantity = parseInt(quantityElement.text()); // Ambil jumlah saat ini
                    var productId = card.find(".product_id").text().trim(); // Ambil ID produk
                    var productName = card.find(".card-title").text().trim(); // Ambil nama produk
            
                    if ($(this).hasClass("product_plus")) {
                        if (quantity < stock) {
                            quantity++;
                        } else {
                            alert("Stock is not enough!");
                            return;
                        }
                    } else if ($(this).hasClass("product_min") && quantity > 0) {
                        quantity--;
                    }
            
                    quantityElement.text(quantity);
                    var subtotal = quantity * price;
                    card.find(".sub_total").text("Rp. " + subtotal.toLocaleString() + " ,-");
            
                    updateHiddenInputs(productId, productName, price, quantity, subtotal);
                });
            
                function updateHiddenInputs(productId, productName, price, quantity, totalPrice) {
                    var hiddenInputsContainer = $("#hidden-inputs");
                    var existingInput = hiddenInputsContainer.find("input[data-id='" + productId + "']");
            
                    var inputValue = productId + ";" + productName + ";" + price + ";" + quantity + ";" + totalPrice;
            
                    if (existingInput.length > 0) {
                        if (quantity > 0) {
                            existingInput.val(inputValue);
                        } else {
                            existingInput.remove(); // Hapus input jika quantity = 0
                        }
                    } else if (quantity > 0) {
                        hiddenInputsContainer.append('<input type="hidden" name="products[]" data-id="' + productId + '" value="' +
                            inputValue + '">');
                    }
                }
            </script>
            @endpush
            
        @endsection