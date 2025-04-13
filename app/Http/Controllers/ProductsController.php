<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $removeRP = str_replace(['RP. ', '.'], '', $request->price);
    //     $request->merge(['price' => $removeRP]);

    //     $request->validate([
    //         'name' => 'required|min:3',
    //         'image' => 'required|image|mimes:png,jpg,jpeg,svg|max:5120',
    //         'price' => 'required|numeric|min:1',
    //         'stock' => 'required|numeric|min:1'
    //     ]);
        
    //     $imagePath = $request->file('image')->store('products', 'public');
    
    //     Product::create([
    //         'name' => $request->name,
    //         'image' => $imagePath,
    //         'price' => $request->price, 
    //         'stock' => $request->stock
    //     ]);
    
    //     return redirect()->route('admin.ProductHome')->with('success', 'Product added successfully!');
    // }
    
    public function store(Request $request)
    {
        // Hapus prefix 'RP. ' dan titik pada harga
        $removeRP = str_replace(['RP. ', '.'], '', $request->price);
        $request->merge(['price' => $removeRP]);
    
        // Validasi form input (kecuali gambar)
        $request->validate([
            'name' => 'required|min:3',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:1'
        ]);
    
        // Validasi file image secara manual
        $image = $request->file('image');
        $imageValidation = Validator::make(
            ['image' => $image],
            ['image' => 'image|mimes:png,jpg,jpeg,svg|max:5120']
        );
        
        if ($imageValidation->fails()) {
            return back()->withErrors($imageValidation)->withInput();
        }
        
        // Generate nama file: 20250413_original-name.jpg
        $filename = time() . '_' . $image->getClientOriginalName();
        
        // Pastikan folder untuk penyimpanan gambar ada
        $destinationPath = public_path('storage/assets/images/products');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true);
        }
    
        // Simpan file image ke: storage/app/public/assets/images/products/...
        $image->move($destinationPath, $filename);
        
        // Simpan path untuk database (akses url via public/storage/... setelah symlink dibuat)
        $imagePath = 'assets/images/products/' . $filename;
        
        // Simpan produk ke database
        Product::create([
            'name'  => $request->name,
            'image' => $imagePath,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        
        return redirect()->route('admin.ProductHome')->with('success', 'Product added successfully');
    }
    


    /**
     * Display the specified resource.
     */
    public function show()
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $products = Product::find($id);

        return view('admin.product.edit', compact('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $removeRP = str_replace(['RP. ', '.'], '', $request->price);
        $request->merge(['price' => $removeRP]);

        $request->validate([
            'name' => 'required|min:3',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120',
            'price' => 'required|numeric|min:1',
        ]);
        
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
    
        return redirect()->route('admin.ProductHome')->with('success', 'Product edited successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $purchaseWithProduct = $product->detail_purchase()->exists();
        if ($purchaseWithProduct) {
            return redirect()->back()->with('failed', 'Product is already listed with purchase!');
        } else {
        $product->delete();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    
        $product->delete();

        return redirect()->route('admin.ProductHome')->with('deleted', 'Product deleted successfully!');
        }
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'stock' => 'required|numeric|min:' . ($product->stock + 1),
        ], [
            'stock.min' => 'Stock baru harus lebih besar dari stock lama.',
        ]);

        $product->update([
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.ProductHome')->with('success', 'Stock updated successfully!');
    }

    public function employeeIndex()
    {
        $products = Product::all();

        return view('employee.product.index', compact('products'));
    }
    
}
