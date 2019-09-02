<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();


Route::get('/sitemap',function(){
    return response()->view('sitemap')
        ->header('Content-Type', 'xml');
});
Route::get('/', 'Auth\LoginController@welcome');
Route::get('/infographics', 'Auth\LoginController@infographics');
Route::get('/refund-policy', 'Auth\LoginController@refundPolicy');
Route::get('/contact-us', 'Auth\LoginController@contactUs');
Route::get('/thankyou', 'Auth\LoginController@thankYou');
Route::get('/thankyourefer', 'Auth\LoginController@thankYouRefer');
Route::get('/about-us', 'Auth\LoginController@aboutUs');
Route::get('/buy', 'Auth\LoginController@buy');
Route::post('/send-query', 'Auth\LoginController@sendQuery');
Route::get('/refer-friend', 'Auth\LoginController@referFriend');
Route::get('/terms', 'Auth\LoginController@terms');
Route::get('/privacy', 'Auth\LoginController@privacy');
Route::get('/backend', function () {
    return Redirect::to('login');
});
/**
 * Payment Class and controller
 * */
Route::group(['prefix' => 'pte'], function () {
    //Route::post('/payment', 'PteController@payment');
    Route::post('/payment-request', 'PteController@createPaymentRequest');
    Route::get('/redirect', 'PteController@checkPaymentStatus');
    Route::post('/confirm', 'PteController@createOrder');
});

Auth::routes();
Route::get('after_reset_password', ['uses' => 'Api\LoginController@afterResetPassword']);
Route::get('/maintanance', function (){
    return view('maintanance');
});

/*
 *  Dashboard Management
 *  get file from resources/views
 * */

Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);





/*
 *  Role Management
 *  get files from resources/views/role
 * */

Route::group(array('prefix' => 'role','Permission'=>"role_manage", 'as' => 'role::'), function() {
    Route::any('list', ['as' => 'indexRole', 'uses' => 'RoleController@index']);
    Route::get('add', ['as' => 'createRole', 'uses' => 'RoleController@create']);
    Route::get('edit/{id}', ['as' => 'editRole', 'uses' => 'RoleController@edit']);
    Route::post('store', ['as' => 'storeRole', 'uses' => 'RoleController@store']);
    Route::post('update', ['as' => 'updateRole', 'uses' => 'RoleController@update']);
});

/*
 *  Permission Management
 *  get files from resources/views/permission
 * */

Route::group(array('prefix' => 'permission','Permission'=>"permission_management", 'as' => 'permission::'), function() {
    Route::any('list', ['as' => 'indexPermission', 'uses' => 'PermissionController@index']);
    Route::get('add', ['as' => 'createPermission', 'uses' => 'PermissionController@create']);
    Route::get('edit/{id}', ['as' => 'editPermission', 'uses' => 'PermissionController@edit']);
    Route::post('store', ['as' => 'storePermission', 'uses' => 'PermissionController@store']);
    Route::post('update', ['as' => 'updatePermission', 'uses' => 'PermissionController@update']);
});

/*
 *  User Management
 *  get files from resources/views/permission
 * */
Route::get('/change-password', ['uses' => 'UserController@changePassword']);
Route::post('/update_password', ['uses' => 'UserController@updatePassword']);
Route::group(array('prefix' => 'user','Permission'=>"user_management", 'as' => 'user::'), function() {
    Route::any('list', ['as' => 'indexUser', 'uses' => 'UserController@index']);
    Route::get('add', ['as' => 'createUser', 'uses' => 'UserController@create']);
    Route::get('profile', ['uses' => 'UserController@profile']);
    Route::get('edit/{id}', ['as' => 'editUser', 'uses' => 'UserController@edit']);
    Route::post('delete', ['as' => 'deleteUser', 'uses' => 'UserController@delete']);
    Route::post('store', ['as' => 'storeUser', 'uses' => 'UserController@store']);
    Route::post('update', ['as' => 'updateUser', 'uses' => 'UserController@update']);
    Route::post('change_status', ['uses' => 'UserController@changeStatus']);
    Route::post('datatable', ['uses' => 'UserController@datatable']);
});


Route::group(['prefix' => 'voucher'], function () {

    Route::any('/list', 'PromoController@index')->name('promoPromo');
    Route::get('/add/{flag?}', 'PromoController@create')->name('createPromo');
    Route::get('/profile', 'PromoController@profile');
    Route::get('/edit/{id}/{flag?}', 'PromoController@edit')->name('editPromo');
    Route::post('/delete', 'PromoController@delete')->name('deletePromo');
    Route::post('/store', 'PromoController@store')->name('storePromo');
    Route::post('/update', 'PromoController@update')->name('updatePromo');
    Route::post('/change_status', 'PromoController@changeStatus');
    Route::post('/datatable', 'PromoController@datatable');
    

});

Route::group(['prefix' => 'purchase'], function () {

    Route::any('/list', 'PurchaseDataController@index');
    Route::get('/add', 'PurchaseDataController@create');
    Route::get('/edit/{id}', 'PurchaseDataController@edit');
    Route::post('/delete', 'PurchaseDataController@delete');
    Route::post('/store', 'PurchaseDataController@store');
    Route::post('/update', 'PurchaseDataController@update');
    Route::post('/datatable', 'PurchaseDataController@datatable');
});

Route::group(['prefix' => 'agent'], function () {

    Route::any('/list', 'AgentController@index');
    Route::get('/add', 'AgentController@create');
    Route::get('/edit/{id}', 'AgentController@edit');
    Route::post('/delete', 'AgentController@delete');
    Route::post('/store', 'AgentController@store');
    Route::post('/update', 'AgentController@update');
    Route::post('/send-mail', 'AgentController@sendMail');
    Route::post('/datatable', 'AgentController@datatable');
});

Route::group(['prefix' => 'expense'], function () {

    Route::any('/list', 'ExpenseDataController@index');
    Route::get('/add', 'ExpenseDataController@create');
    Route::get('/edit/{id}', 'ExpenseDataController@edit');
    Route::post('/delete', 'ExpenseDataController@delete');
    Route::post('/store', 'ExpenseDataController@store');
    Route::post('/update', 'ExpenseDataController@update');
    Route::post('/datatable', 'ExpenseDataController@datatable');
});

Route::group(['prefix' => 'enquiry'], function () {

    Route::any('/list', 'EnquiryController@index');
    Route::get('/download/{file_name}','EnquiryController@downloadfile');
    Route::post('/datatable', 'EnquiryController@datatable');
});

Route::group(['prefix' => 'prize'], function () {
    Route::get('/add', 'PrizeController@create');
    Route::post('/store', 'PrizeController@store');
    Route::post('/update', 'PrizeController@update');
});

Route::group(['prefix' => 'detail'], function () {
    Route::get('/add', 'DetailController@create');
    Route::post('/store-prize', 'DetailController@storePrize');
    Route::post('/store-title', 'DetailController@storeTitle');
 });

Route::group(['prefix' => 'offline'], function () {
    Route::any('/list', 'OfflinePaymentController@index');
    Route::get('/add-new-agent', 'OfflinePaymentController@addNewAgentPayment');
    Route::get('/add-existing-agent-payment', 'OfflinePaymentController@addExistingAgentPayment');
    Route::post('/store-new-agent-payment', 'OfflinePaymentController@storeNewAgentPayment');
    Route::post('/store-existing-agent-payment', 'OfflinePaymentController@storeExistingAgentPayment');
    Route::get('/edit/{id}', 'OfflinePaymentController@edit');
    Route::post('/update-agent-payment', 'OfflinePaymentController@update');
    Route::post('/datatable', 'OfflinePaymentController@datatable');
    Route::post('/delete', 'OfflinePaymentController@delete');
    Route::post('/get_all_agent', 'OfflinePaymentController@getAllAgent');
    Route::post('/send-mail', 'OfflinePaymentController@sendMail');
});

Route::group(['prefix' => 'overseas'], function () {
    Route::any('/list', 'OverseasPaymentController@index');
    Route::get('/add-new-agent', 'OverseasPaymentController@addNewAgentPayment');
    Route::post('/store-new-agent-payment', 'OverseasPaymentController@storeNewAgentPayment');
    Route::get('/edit/{id}', 'OverseasPaymentController@edit');
    Route::post('/update-agent-payment', 'OverseasPaymentController@update');
    Route::post('/datatable', 'OverseasPaymentController@datatable');
    Route::post('/delete', 'OverseasPaymentController@delete');
    Route::post('/get_all_agent', 'OverseasPaymentController@getAllAgent');
    Route::get('/download-pdf', 'OverseasPaymentController@downloadpdf')->name('overseas-pdfdownload');
});

Route::group(['prefix' => 'pgoffline'], function () {
    Route::any('/list', 'PGOfflinePaymentController@index');
    Route::get('/add-new-agent', 'PGOfflinePaymentController@addNewAgentPayment');
    Route::get('/add-existing-agent-payment', 'PGOfflinePaymentController@addExistingAgentPayment');
    Route::post('/store-new-agent-payment', 'PGOfflinePaymentController@storeNewAgentPayment');
    Route::post('/store-existing-agent-payment', 'PGOfflinePaymentController@storeExistingAgentPayment');
    Route::get('/edit/{id}', 'PGOfflinePaymentController@edit');
    Route::post('/update-agent-payment', 'PGOfflinePaymentController@update');
    Route::post('/datatable', 'PGOfflinePaymentController@datatable');
    Route::post('/delete', 'PGOfflinePaymentController@delete');
    Route::post('/get_all_agent', 'PGOfflinePaymentController@getAllAgent');
    Route::post('/send-mail', 'PGOfflinePaymentController@sendMail');
});

Route::group(['prefix' => 'saledata'], function () {
    Route::any('/list', 'SaleDataController@index');
    Route::any('/invoice-list', 'SaleDataController@invoiceList');
    Route::post('/datatable', 'SaleDataController@datatable');
    Route::post('/invoie-datatable', 'SaleDataController@invoiceDatatable');
    Route::post('/delete', 'SaleDataController@delete');
    Route::get('/download-pdf', 'SaleDataController@downloadpdf')->name('saledata-pdfdownload');
    Route::get('/create-online-zip', 'SaleDataController@createZipOfOnlineCustomer');
    Route::get('/create-offline-zip', 'SaleDataController@createZipOfOfflineCustomer');
    Route::post('/generate-zip', 'SaleDataController@generateZip');
});

Route::group(['prefix' => 'refer'], function () {

    Route::any('/list', 'ReferController@index');
    Route::get('/download/{file_name}','ReferController@downloadfile');
    Route::post('/datatable', 'ReferController@datatable');
    Route::post('/store', 'ReferController@store');
});