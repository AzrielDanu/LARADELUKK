<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('isGuest')->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
});

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/error-permission', function() { 
    return view('error.permission');
})->name('error.permission');


// Route::middleware(['isLogin'])->group(function () {
//     Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
// });

//admin
Route::middleware('isLogin', 'isAdmin')->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [UserController::class, 'dashboardAdmin'])->name('dashboard');

    //product
    Route::prefix('/product')->group(function() {
        Route::get('/', [ProductsController::class, 'index'])->name('ProductHome');
        Route::get('/create', [ProductsController::class, 'create'])->name('ProductCreate');
        Route::post('/store', [ProductsController::class, 'store'])->name('ProductStore');
        Route::get('/{id}', [ProductsController::class, 'edit'])->name('ProductEdit');
        Route::patch('/{id}', [ProductsController::class, 'update'])->name('ProductUpdate');
        Route::delete('/{id}', [ProductsController::class, 'destroy'])->name('ProductDelete');
        Route::put('/stock/{id}', [ProductsController::class, 'updateStock'])->name('ProductStock');
    });

    //user
    Route::prefix('/user')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('UserHome');
        Route::get('/create', [UserController::class, 'create'])->name('UserCreate');
        Route::post('/store', [UserController::class, 'store'])->name('UserStore');
        Route::get('/{id}', [UserController::class, 'edit'])->name('UserEdit');
        Route::patch('/{id}', [UserController::class, 'update'])->name('UserUpdate');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('UserDelete');
    });

    //purchases
    Route::prefix('/purchase')->group(function() {
        Route::get('/', [PurchasesController::class, 'adminIndex'])->name('PurchaseHome');
    });
});

Route::middleware('isLogin', 'isEmployee')->prefix('employee')->name('employee.')->group(function() {
    Route::get('/dashboard', [UserController::class, 'dashboardEmployee'])->name('dashboard');
    Route::get('/print/{id}', [PurchasesController::class, 'exportPDFAdmin'])->name('ExportPDFAdmin');    

    
    //product
    Route::prefix('/product')->group(function() {
        Route::get('/', [ProductsController::class, 'employeeIndex'])->name('ProductIndex');
    });

    //purchases
    Route::prefix('/purchase')->group(function() {
        Route::get('/', [PurchasesController::class, 'employeeindex'])->name('PurchaseIndex');
        Route::get('/create', [PurchasesController::class, 'create'])->name('PurchaseCreate');
        Route::post('/store', [PurchasesController::class, 'store'])->name('PurchaseStore');
        Route::post('/payment', [PurchasesController::class, 'payment'])->name('PurchasePayment');      
        Route::post('/payment-proses', [PurchasesController::class, 'paymentProcess'])->name('paymentProcess'); 
        Route::get('/member-edit/{id}', [PurchasesController::class, 'EditMember'])->name('EditMember');
        Route::put('/member/{id}', [PurchasesController::class, 'member'])->name('Member');      
        Route::get('/detail-print/{id}', [PurchasesController::class, 'print'])->name('DetPrint');      
        Route::get('/print/{id}', [PurchasesController::class, 'exportPDF'])->name('ExportPDF');    
        Route::get('/data', [PurchasesController::class, 'dataExcel'])->name('dataExcel');    
        Route::get('/excel', [PurchasesController::class, 'Excel'])->name('Excel');    

        
    });


});