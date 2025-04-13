<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Models\Detail_purchase;
use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function employeeindex()
    {
        $purchases = Purchase::with(['member', 'user'])->get();
        $detail_purchase = Detail_purchase::with(['product'])->get();
    
        return view('employee.purchases.index', compact('purchases', 'detail_purchase'));
    }

    public function AdminIndex()
    {
        $purchases = Purchase::with(['member', 'user'])->get();
        $detail_purchase = Detail_purchase::with(['product'])->get();

        return view('admin.purchases.index',  compact('purchases', 'detail_purchase'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchases = Product::all();

        return view('employee.purchases.create', compact('purchases'));
    }

    public function paymentProcess(Request $request)
    {
        $products = $request->shop;
        $purchase_product = [];
        $total_pay = (int)str_replace(['Rp. ', '.'], '', $request->total_payment);
        $total = (int)str_replace(['Rp. ', '.'], '', $request->total);        
        $member_id = null;

        if ($request->member == 'Member') {
            $phone = $request->phone;
            $name = $request->name;
            $existCustomer = Member::where('phone', $phone)->first();

            if ($existCustomer) {
                // Update data customer lama
                $existCustomer->update([
                    'point' => $existCustomer->point + ($total / 100),
                ]);
                $member_id = $existCustomer->id;
            } else {
                // Buat customer baru
                $newCustomer = Member::create([
                    'name' => $name,
                    'phone' => $phone,
                    'point' => $total / 100,
                ]);
                $member_id = $newCustomer->id;
            }
        }

        // Buat transaksi baru
        $purchase = Purchase::create([
            'purchase_date' => now(),
            'member_id' => $member_id, // Bisa NULL untuk non-member
            'total_price' => $total,
            'total_payment' => $total_pay,
            'change' => $total_pay - $total,
            'user_id' => Auth::user()->id,
            'purchase_product' => implode(', ', $purchase_product) ?? '',
            'used_point' => 0
        ]);

        // Simpan detail produk yang dibeli
        foreach ($products as $product) {
            $product = explode(';', $product);
            $id = $product[0];
            $name = $product[1];
            $price = number_format($product[2], 0, ',', '.');
            $quantity = (int)$product[3];
            $subtotal = (int)$product[4];

            $purchase_product[] = "{$name} ( {$quantity} : Rp. {$price} )";

            // Update stok produk
            $productModel = Product::find($id);
            if ($productModel) {
                $productModel->update(['stock' => $productModel->stock - $quantity]);
            }

            // Simpan detail penjualan
            Detail_purchase::create([
                'purchase_id' => $purchase->id,
                'product_id' => $id,
                'quantity' => $quantity,
                'sub_total' => $subtotal,
            ]);
        }

        // Update purchase_product di Sale setelah data dikumpulkan
        $purchase->update(['purchase_product' => implode(' , ', $purchase_product)]);

        // Redirect sesuai kondisi
        if ($request->member == 'Member') {
            return redirect()->route('employee.EditMember', $purchase->id);
        }

        return redirect()->route('employee.DetPrint', $purchase->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $products = $request->products;
        $data['products'] = [];
        $data['total'] = 0;
        foreach ($products as $product) {
            $product = explode(';', $product);
            $id = $product[0];
            $name = $product[1];
            $price = $product[2];
            $quantity = $product[3];
            $subtotal = $product[4];

            $data['products'][] = [
                'product_id' => $id,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'sub_total' => $subtotal,
            ];
            $data['total'] += $subtotal;
        }
        // dd($data['proucts']);
        return view('employee.purchases.payment', $data);
    }
    
    public function Member(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'purchase_id' => 'required|exists:purchases,id',
            'member_id' => 'required|exists:members,id',
            'check_point' => 'nullable|in:Ya',
        ]);

        $member = Member::findOrFail($request->member_id);
        $name = $request->input('name');

        // Update nama member secara langsung
        $member->name = $name;
        $member->save();

        $purchase = Purchase::findOrFail($request->purchase_id);

        if ($request->check_point == 'Ya') {
            $used_point = $member->point;

            $member->point = 0;
            $member->save();

            $purchase->used_point = $used_point;
            $purchase->total_price -= $used_point;
            $purchase->change = $purchase->total_payment - $purchase->total_price;
        }

        $purchase->member_id = $member->id;
        $purchase->save();

        return redirect()->route('employee.DetPrint', $purchase->id)->with('success', 'Successfully created purchase');
    }

    public function EditMember($id)
    {
        $purchase = Purchase::with(['member', 'user'])->findOrFail($id);
        $detail_purchase = Detail_purchase::where('purchase_id', $purchase->id)->with('product')->get();

        $isFirst = false;

        if ($purchase->member) {
            $countPurchase = Purchase::where('member_id', $purchase->member->id)->count();
            $isFirst = $countPurchase <= 1;
        }

        return view('employee.purchases.member', compact('purchase', 'detail_purchase', 'isFirst'));
    }

    public function Print($id)
    {
        $purchase = Purchase::with(['member', 'user'])->findOrFail($id);
        $detail_purchase = Detail_purchase::where('purchase_id', $purchase->id)->with('product')->get();
        return view('employee.purchases.print', compact('purchase', 'detail_purchase'));
    }

    public function exportPDF($id)
    {
        $purchase = Purchase::with(['member', 'user'])->findOrFail($id);
        $detail_purchase = Detail_purchase::where('purchase_id', $purchase->id)->with('product')->get();

        $data = [
            'purchase' => $purchase,
            'detail_purchase' => $detail_purchase
        ];
        
        $pdf = PDF::loadView('employee.purchases.exportpdf', $data);

        return $pdf->download('receipt.pdf');
    }

    public function exportPDFAdmin($id)
    {
        $purchase = Purchase::with(['member', 'user'])->findOrFail($id);
        $detail_purchase = Detail_purchase::where('purchase_id', $purchase->id)->with('product')->get();

        $data = [
            'purchase' => $purchase,
            'detail_purchase' => $detail_purchase
        ];
        
        $pdf = PDF::loadView('admin.purchases.exportpdf', $data);

        return $pdf->download('receipt.pdf');
    }

    public function dataExcel($id) 
    {
        // Fetch the purchase data correctly
        $purchase = Purchase::with(['member', 'user'])->find($id);  // Assuming $id is needed

        // Fetch the detail purchase data for the specific purchase
        $detail_purchase = Detail_purchase::where('purchase_id', $purchase->id)->with('product')->get();

        // Pass the data to the view
        return view("employee.purchases.excel", compact('purchase', 'detail_purchase')); // Corrected data passing
    }

    public function Excel()
    {
        $file_name = 'laporan_pembelian.xlsx'; 

        
        return Excel::download(new ExcelExport, $file_name);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $Purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $Purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $Purchase)
    {
        //
    }
}
