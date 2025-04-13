<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt </title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .invoice-title { font-size: 24px; font-weight: bold; }
        .shop-info { margin-bottom: 30px; }
        .member-info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #f4f4f4; }
        th, td { padding: 10px 15px; text-align: left; }
        th { background-color: #dcdcdc; }
        td { background-color: #fff; }
        .total-section { margin-top: 20px; text-align: right; }
        .thank-you { margin-top: 30px; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .border-top { border-top: 1px solid #000; }
        .total-row td {
            font-weight: bold;
        }
        .summary { background-color: #f0f0f0; padding: 10px; }
        .summary td { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-title">RECEIPT</div>
        <div>Indo April</div>
        <div>Jl. Raya Wangun No. 123, Bogor</div>
        <div>Phone: 081234567</div>
    </div>

    <div class="flex justify-between">
        <div class="member-info">
            Member Status: {{ $purchase->member ? 'Member' : 'Non-Member'}}<br>
            Member Phone: {{ $purchase->member ? $purchase->member->phone : '-' }}<br>
            Member Since: {{ $purchase->member ? $purchase->member->created_at->format('d F Y') : '-' }}<br>
            Member Points:  {{ $purchase->member ? $purchase->member->point : '-' }} 
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Price</th>
                <th class="text-right">Sub total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail_purchase as $data)
            <tr>
                <td>{{ $data->product->name }}</td>
                <td class="text-right">{{ $data->quantity }}</td>
                <td class="text-right">Rp {{ number_format($data->product->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data->sub_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
        <table class="summary">
            <tr>
                <td>Total Price</td>
                <td class="text-right">Rp {{ number_format($purchase->used_point > 0 ? $purchase->total_price + $purchase->used_point : $purchase->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Used Point</td>
                <td class="text-right">{{ $purchase->used_point > 0  ? $purchase->used_point : '0'  }}</td>
            </tr>
            <tr>
                <td>Price after used point</td>
                <td class="text-right">Rp {{ number_format($purchase->used_point > 0 ? $purchase->total_price : '0', 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pay</td>
                <td class="text-right">Rp {{ number_format($purchase->total_payment, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Change</td>
                <td class="text-right">Rp {{ number_format( $purchase->change, 0, ',', '.')}}</td>
            </tr>
        </table>


    <div class="thank-you">
        <div>
            <div class="text-bold">INVOICE #{{ $purchase->id }}</div>
            <div>Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</div>
            <div>Cashier: {{ $purchase->user->name }}</div>
        </div>

        <p>Thank you for shopping at Indo April</p>
        <p>Items purchased cannot be exchanged or returned</p>
    </div>
</body>
</html>