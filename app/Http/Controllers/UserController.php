<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns|unique:users,email',
            'name' => 'required|min:3',
            'role' => 'required|in:admin,employee',
            'password' => 'required|min:3'
        ]);
    
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('error', 'Email already exists!');
        }
        
        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
    
        return redirect()->route('admin.UserHome')->with('success', 'user added successfully!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::find($id);

        return view('admin.user.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email:dns|unique:users,email,' . $id,
            'name' => 'required|min:3',
            'role' => 'required|in:admin,employee',
            'password' => 'nullable|min:3'
        ]);
        
        $data = [
            'email' => $request->email,
            'name' => $request->name,
            'role' => $request->role,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.UserHome')->with('error', 'user not found!');
        }
        
        $user->update($data);
        
        return redirect()->route('admin.UserHome')->with('success', 'user edited successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect()->route('admin.UserHome')->with('deleted', 'user deleted successfully!');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $users = $request->only(['email', 'password']);
        if (Auth::attempt($users)) {
            $user = Auth::user();
            
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            } elseif ($user->role == 'employee') {
                return redirect()->route('employee.dashboard')->with('success', 'Login successful!');
            } else {
                return redirect()->route('error.permission');
            }
        } else {
            return redirect()->back()->with('failed', 'Login process failed, please try again.');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'You have been logout!');
    }

    public function dashboardAdmin()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
    
        // Data untuk chart batang (purchase per hari)
        $chartData = DB::table('purchases')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();
    
        // Data untuk pie chart (penjualan produk per nama_produk)
        $productDataRaw = DB::table('purchases')
            ->join('detail_purchase', 'purchases.id', '=', 'detail_purchase.purchase_id')
            ->join('products', 'detail_purchase.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(detail_purchase.quantity) as total_sold')
            ->whereBetween('purchases.created_at', [$startDate, $endDate])
            ->groupBy('product_name')
            ->get();
    
        // Total semua penjualan untuk hitung persentase
        $totalAll = $productDataRaw->sum('total_sold');
    
        // Format untuk Highcharts pie chart
        $productData = $productDataRaw->map(function ($item) use ($totalAll) {
            return [
                'name' => $item->product_name,
                'y' => $totalAll > 0 ? round(($item->total_sold / $totalAll) * 100, 2) : 0,
            ];
        });
    
        return view('admin.dashboard', [
            'chartData' => $chartData,
            'productData' => $productData,
        ]);
    }
    

    public function dashboardEmployee()
    {
        $today = Carbon::today();
        $salesCountToday = Purchase::whereDate('created_at', $today)->count();
        $latestTransaksi = Purchase::latest()->first();

        return view('employee.dashboard', compact('salesCountToday', 'latestTransaksi'));
    }
}
