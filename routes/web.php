<?php

use App\Http\Controllers\AdAccountController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardCreditController;
use App\Http\Controllers\CardDebitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OtherExpController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPrivilegeController;
use App\Models\Customer;
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

// Route::get('/', function () {
//     return view('welcome');
// });

//user_login_register routes
// Route::get('/register', [UserController::class, 'register_form']);
// Route::get('/login', [UserController::class, 'login_form'])->name('login_form');
// Route::post('/login', [UserController::class, 'login'])->name('login');
// Route::post('/register', [UserController::class, 'register'])->name('register');

//end here

//User auth routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');
});

//end here

//user_login_register routes
Route::get('admin/register', [AdminController::class, 'register_form']);
Route::get('/', [AdminController::class, 'login_form'])->name('admin.login_form');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/register', [AdminController::class, 'register'])->name('admin.register');


//end here

//admin auth routes
Route::middleware('admin')->group(function () {

    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [AdController::class, 'summarydashboard'])->name('admin.dashboard');
    Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    //ad routes
    Route::post('/admin/dashboard/ads', [AdController::class, 'storeAd'])->name('storeAd');
    Route::get('/admin/dashboard/ads/add', [AdController::class, 'ad_form'])->name('ad_form');
    Route::get('/admin/dashboard/ads_list', [AdController::class, 'showAds'])->name('ads.show');
    Route::get('/admin/dashboard/ads_complete_list', [AdController::class, 'showCompleteAds'])->name('ads_complete.show');
    Route::post('/admin/dashboard/ads/edit/{id}', [AdController::class, 'update'])->name('ads.update');
    Route::get('/admin/dashboard/ads/edit/{id}', [AdController::class, 'edit'])->name('ads.edit');
    Route::get('/admin/dashboard/ads/delete/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::get('/admin/dashboard/ads/search', [AdController::class, 'search'])->name('search_ad');
    ROute::get('/admin/dashboard/ads_complete/search', [AdController::class, 'search_ad_complete'])->name('search_complete_ad');
    Route::get('/admin/dashboard/ads/summary', [AdController::class, 'summary'])->name('ads.summary');
    Route::get('/admin/dashboard/ads/this_day', [AdController::class, 'thisDay'])->name('ads.this_day');
    Route::get('/admin/dashboard/ads/yesterday', [AdController::class, 'yesterday'])->name('ads.yesterday');
    Route::get('/admin/dashboard/ads/this_week', [AdController::class, 'thisWeek'])->name('ads.this_week');
    Route::get('/admin/dashboard/ads/this_month', [AdController::class, 'thisMonth'])->name('ads.this_month');
    Route::get('/admin/dashboard/ads/this_day_complete', [AdController::class, 'thisDay_complete'])->name('ads.this_day_complete');
    Route::get('/admin/dashboard/ads/yesterday_complete', [AdController::class, 'yesterday_complete'])->name('ads.yesterday_complete');
    Route::get('/admin/dashboard/ads/this_week_complete', [AdController::class, 'thisWeek_complete'])->name('ads.this_week_complete');
    Route::get('/admin/dashboard/ads/this_month_complete', [AdController::class, 'thisMonth_complete'])->name('ads.this_month_complete');

    //end here

    //customer routes
    Route::get('/admin/dashboard/customer/add', [CustomerController::class, 'add_form'])->name('customer.add');
    Route::post('/admin/dashboard/customer/add', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/admin/dashboard/customer_list', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/admin/dashboard/customer/edit/{id}', [CustomerController::class, 'update_form'])->name('customer.edit');
    Route::post('/admin/dashboard/customer/edit/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/admin/dashboard/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.destroy');
    Route::get('/admin/dashboard/customer/search', [CustomerController::class, 'search'])->name('search_customer');


    //ends here

    //Item routes
    Route::get('/admin/dashboard/item/add', [ItemController::class, 'add_form'])->name('item.add');
    Route::post('/admin/dashboard/item/add', [ItemController::class, 'store'])->name('item.store');
    Route::get('/admin/dashboard/item_list', [ItemController::class, 'show'])->name('item.show');
    Route::get('/admin/dashboard/item/edit/{id}', [ItemController::class, 'update_form'])->name('item.edit');
    Route::post('/admin/dashboard/item/edit/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::get('/admin/dashboard/item/delete/{id}', [ItemController::class, 'delete'])->name('item.destroy');
    Route::get('/admin/dashboard/item/search', [ItemController::class, 'search'])->name('search_item');
    //ends here


    //client routes
    Route::get('/admin/dashboard/client/add', [ClientController::class, 'add_form'])->name('client.add');
    Route::post('/admin/dashboard/client/add', [ClientController::class, 'store'])->name('client.store');
    Route::get('/admin/dashboard/client_list', [ClientController::class, 'show'])->name('client.show');
    Route::get('/admin/dashboard/client/edit/{id}', [ClientController::class, 'update_form'])->name('client.edit');
    Route::post('/admin/dashboard/client/edit/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::get('/admin/dashboard/client/delete/{id}', [ClientController::class, 'delete'])->name('client.destroy');
    Route::get('/admin/dashboard/client/search', [ClientController::class, 'search'])->name('search_client');
    Route::get('/admin/dashboard/client/summary', [ClientController::class, 'summary'])->name('client_summary');
    Route::get('/admin/dashboard/clients/this_week', [ClientController::class, 'thisWeek'])->name('client.this_week');
    Route::get('/admin/dashboard/clients/this_month', [ClientController::class, 'thisMonth'])->name('client.this_month');
    Route::get('/admin/dashboard/clients/this_day', [ClientController::class, 'thisDay'])->name('client.this_day');
    Route::get('/admin/dashboard/clients/yesterday', [ClientController::class, 'yesterday'])->name('client.yesterday');
    //ends here

    //admin profile routes
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    //end here

    //Ad account routes
    Route::get('/admin/dashboard/ad_account/add', [AdAccountController::class, 'add_form'])->name('ad_account.add');
    Route::post('/admin/dashboard/ad_account/add', [AdAccountController::class, 'store'])->name('ad_account.store');
    Route::get('/admin/dashboard/ad_accounts', [AdAccountController::class, 'show'])->name('ad_account.show');
    Route::get('/admin/dashboard/ad_account/edit/{id}', [AdAccountController::class, 'update_form'])->name('ad_account.edit');
    Route::post('/admin/dashboard/ad_account/edit/{id}', [AdAccountController::class, 'update'])->name('ad_account.update');
    Route::get('/admin/dashboard/ad_account/delete/{id}', [AdAccountController::class, 'delete'])->name('ad_account.destroy');
    Route::post('/admin/dashboard/ad_account/search', [AdAccountController::class, 'search'])->name('search_ad_account');
    //end here

    //invoice route here
    Route::get('/admin/dashboard/invoice/add_form', [InvoiceController::class, 'showForm'])->name('invoice.add');
    Route::post('/admin/dashboard/invoice/add', [InvoiceController::class, 'saveInvoice'])->name('invoice.store');
    Route::get('/admin/dashboard/invoice/list', [InvoiceController::class, 'list'])->name('invoice.list');
    Route::get('/admin/dashboard/invoice/update/{id}', [InvoiceController::class, 'update_form'])->name('invoice.edit');
    Route::post('/admin/dashboard/invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::post('/admin/dashboard/invoice/delete/{id}', [InvoiceController::class, 'delete'])->name('invoice.delete');
    //ends here

    //card routes
    Route::get('/admin/dashboard/card/add', [CardController::class, 'add_form'])->name('card.add');

    Route::post('/admin/dashboard/card/add', [CardController::class, 'store'])->name('card.store');
    Route::get('/admin/dashboard/card_list', [CardController::class, 'show'])->name('card.show');
    Route::get('/admin/dashboard/card/edit/{id}', [CardController::class, 'update_form'])->name('card.edit');
    Route::post('/admin/dashboard/card/edit/{id}', [CardController::class, 'update'])->name('card.update');
    Route::get('/admin/dashboard/card/delete/{id}', [CardController::class, 'delete'])->name('card.destroy');
    Route::get('/admin/dashboard/card/search', [CardController::class, 'search'])->name('search_card');
    Route::get('/admin/dashboard/card/summary', [CardController::class, 'summary'])->name('card.summary');
    Route::get('/admin/dashboard/card_cre_deb/', [CardController::class, 'all_in_one'])->name('all_in_one');
    //ends here

    //credit route
    Route::get('/admin/dashboard/credit/credit_form', [CardCreditController::class, 'credit_form'])->name('credit.add');
    Route::post('/admin/dashboard/credit/add', [CardCreditController::class, 'credit'])->name('credit.store');
    Route::get('/admin/dashboard/credit/list', [CardCreditController::class, 'show'])->name('credit.show');
    Route::get('/admin/dashboard/credit/summary', [CardCreditController::class, 'summary'])->name('credit.summary');
    Route::get('/admin/dashboard/credit/search', [CardCreditController::class, 'search'])->name('search_credit');
    Route::get('/admin/dashboard/credit/search_list', [CardCreditController::class, 'search_list'])->name('search_credit_list');
    //end here

    //debit route
    Route::get('/admin/dashboard/debit/debit_form', [CardDebitController::class, 'debit_form'])->name('debit.add');
    Route::post('/admin/dashboard/debit/add', [CardDebitController::class, 'debit'])->name('debit.store');
    Route::get('/admin/dashboard/debit/list', [CardDebitController::class, 'show'])->name('debit.show');
    Route::get('/admin/dashboard/debit/summary', [CardDebitController::class, 'summary'])->name('debit.summary');
    Route::get('/admin/dashboard/debit/search', [CardDebitController::class, 'search'])->name('search_debit');
    Route::get('/admin/dashboard/debit/search_list', [CardDebitController::class, 'search_list'])->name('search_debit_list');
    //end here


    //note route
    Route::post('/api/saveNote', [NoteController::class, 'saveNote']);
    Route::get('/api/getNote', [NoteController::class, 'getNotes']);
    //end here

    //user privillage route
    Route::get('/admin/dashboard/user/add', [UserPrivilegeController::class, 'register_form'])->name('admin.user.add');
    Route::post('/admin/dashboard/user/add', [UserPrivilegeController::class, 'register'])->name('admin.user.store');
    Route::get('/admin/dashboard/user/list', [UserPrivilegeController::class, 'show'])->name('admin.user.show');
    Route::get('/admin/dashboard/user/delete/{id}', [UserPrivilegeController::class, 'delete'])->name('admin.user.delete');
    Route::get('/admin/dashboard/user/search', [UserPrivilegeController::class, 'search'])->name('search_user');
    Route::get('/admin/dashboard/user/edit/{id}', [UserPrivilegeController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/dashboard/user/update/{id}', [UserPrivilegeController::class, 'update'])->name('admin.user.update');
    Route::get('/admin/dashboard/user/privilege/{id}', [UserPrivilegeController::class, 'privilege'])->name('admin.user.privilege');
    Route::post('/admin/dashboard/user/privilege/{id}', [UserPrivilegeController::class, 'privilege_store'])->name('admin.user.privilege_store');
    //end here

    //excel export
    Route::get('/admin/dashboard/export', [CardController::class, 'exportToExcel'])->name('excel_export');
    //end here

    //send email
    Route::get('/admin/dashboard/send_email/{id}', [AdController::class, 'email_to_send'])->name('send_email');
    //end here

    //other expences
    Route::get('/admin/dashboard/exp/add', [OtherExpController::class, 'add_form'])->name('exp.add');
    Route::post('/admin/dashboard/exp/add', [OtherExpController::class, 'store'])->name('exp.store');
    Route::get('/admin/dashboard/exp_list', [OtherExpController::class, 'show'])->name('exp.show');
    Route::get('/admin/dashboard/exp/edit/{id}', [OtherExpController::class, 'update_form'])->name('exp.edit');
    Route::post('/admin/dashboard/exp/edit/{id}', [OtherExpController::class, 'update'])->name('exp.update');
    Route::get('/admin/dashboard/exp/delete/{id}', [OtherExpController::class, 'delete'])->name('exp.destroy');
    Route::get('/admin/dashboard/exp/search', [OtherExpController::class, 'search'])->name('search_exp');

    //end here

});

// pdf route start here
Route::get('/receipt/show/{id}', [ReceiptController::class, 'show']);
Route::get('/receipt/pdf_gen/{id}', [ReceiptController::class, 'create_pdf']);
Route::get('/invoice/show_invoice/{id}', [ReceiptController::class, 'show_invoice'])->name('invoice.view');
Route::get('/invoice/pdf_gen_invoice/{id}', [ReceiptController::class, 'create_pdf_invoice'])->name('invoice.download');
//end here



// Route::get('/admin/dashboard/customer_list_js', function () {
//     $customers = Customer::all();

//     return response()->json([
//         'data' => $customers
//     ]);
// });
