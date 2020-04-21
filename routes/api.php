<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/CreateAccount','UserController@CreateAccount')->middleware('cors');

Route::post('/LoginAccount','UserController@LoginAccount')->middleware('cors');
Route::post('/getAdminAccount','UserController@getAdminAccount')->middleware('cors');
Route::post('/emailExist','UserController@emailExist')->middleware('cors');
Route::post('/phoneExist','UserController@phoneExist')->middleware('cors');
Route::post('/adminAddingProductCategory','UserController@adminAddingProductCategory')->middleware('cors');

Route::post('/getAdminProfile','AdminController@getAdminProfile')->middleware('cors');
Route::get('/customerCount','AdminController@customerCount')->middleware('cors');
Route::get('/storeCount','AdminController@storeCount')->middleware('cors');
Route::get('/adminCount','AdminController@adminCount')->middleware('cors');
Route::post('/registerAdmin','AdminController@registerAdmin')->middleware('cors');
Route::get('/getFeaturedProducts','AdminController@getFeaturedProducts')->middleware('cors');
Route::get('/getAllTransaction','AdminController@getAllNumberOfTransaction')->middleware('cors');

Route::post('/addProductCategory','ProductCategoryController@addProductCategory')->middleware('cors');
Route::get('/fetchProductCategory','ProductCategoryController@fetchProductCategory')->middleware('cors');
Route::post('/updateProductCategory','ProductCategoryController@updateProductCategory')->middleware('cors');

Route::post('/StoreRegistrationRequest','RequestController@StoreRegistrationRequest')->middleware('cors');
Route::post('/fetchRequestwithFilter','RequestController@fetchRequestwithFilter')->middleware('cors');
Route::get('/fetchRequestAll','RequestController@fetchRequestAll')->middleware('cors');
Route::post('/viewSingleRequest','RequestController@viewSingleRequest')->middleware('cors');
Route::post('/acceptRequest','RequestController@acceptRequest')->middleware('cors');
Route::post('/rejectRequest','RequestController@rejectRequest')->middleware('cors');
Route::post('/viewRejectedRequest','RequestController@viewRejectedRequest')->middleware('cors');
Route::get('/fetchAllRecords','RequestController@getValidatedRequest')->middleware('cors');

Route::post('/getStoreAccount','StoreController@getStoreProfile')->middleware('cors');
Route::post('/getProductCategories','StoreController@getProductCategories')->middleware('cors');
Route::post('/addProductCategory','StoreController@addProductCategory')->middleware('cors');
Route::post('/loadAllProductCategories','StoreController@loadAllProductCategories')->middleware('cors');
Route::post('/loadAllProductCategoriesForIonic','StoreController@loadAllProductCategoriesForIonic')->middleware('cors');
Route::post('/removeProductCategory','StoreController@removeProductCategory')->middleware('cors');
Route::post('/registerProduct','StoreController@registerProduct')->middleware('cors');
Route::post('/loadProducts','StoreController@loadProducts')->middleware('cors');
Route::post('/editAvailability','StoreController@editAvailability')->middleware('cors');
Route::post('/addPaymentTerms','StoreController@addPaymentTerms')->middleware('cors');
Route::post('/getStoreAccess','StoreController@getStoreAccess')->middleware('cors');
Route::post('/getProductThatHasCategory','StoreController@getProductThatHasCategory')->middleware('cors');
Route::post('/getProductThatHasCategory','StoreController@getProductThatHasCategory')->middleware('cors');
Route::post('/removeProduct','StoreController@removeProduct')->middleware('cors');
Route::post('/loadNotification','StoreController@loadNotification')->middleware('cors');
Route::post('/viewSingleProduct','StoreController@viewSingleProduct')->middleware('cors');
Route::post('/updateProduct','StoreController@updateProduct')->middleware('cors');
Route::post('/loadProfilePicture','StoreController@loadProfilePicture')->middleware('cors');
Route::post('/saveProfilePicture','StoreController@saveProfilePicture')->middleware('cors');
Route::post('/CUPisOffered','StoreController@CUPisOffered')->middleware('cors');
Route::post('/CODisOffered','StoreController@CODisOffered')->middleware('cors');
Route::post('/loadPaymentTerms','StoreController@loadPaymentTerms')->middleware('cors');
Route::post('/sendSubscriptionRequest','StoreController@sendSubscriptionRequest')->middleware('cors');
Route::post('/hideStore','StoreController@hideStore')->middleware('cors');
Route::post('/checkSendSubscriptionRequest','StoreController@checkSendSubscriptionRequest')->middleware('cors');
Route::post('/getStoreIdFromCustomerOrder','StoreController@getStoreIdFromCustomerOrder')->middleware('cors');
Route::post('/getAllOrders','StoreController@getAllOrders')->middleware('cors');
Route::post('/StoregetAllOrderItems','StoreController@StoregetAllOrderItems')->middleware('cors');
Route::post('/viewSingleOrder','StoreController@viewSingleOrder')->middleware('cors');
Route::post('/cancellCustomerOrder','StoreController@cancellCustomerOrder')->middleware('cors');
Route::post('/acceptCustomerOrder','StoreController@acceptCustomerOrder')->middleware('cors');
Route::post('/getCustomerViaOrder','StoreController@getCustomerViaOrder')->middleware('cors');
Route::post('/sendCategoryRequest','StoreController@sendCategoryRequest')->middleware('cors');
Route::post('/filterOrder','StoreController@filterOrder')->middleware('cors');
Route::post('/storeGetMessageHeads','StoreController@storeGetMessageHeads')->middleware('cors');
Route::post('/storeViewConvo','StoreController@storeViewConvo')->middleware('cors');
Route::post('/sendMessageToCustomer','StoreController@sendMessageToCustomer')->middleware('cors');
Route::post('/sendMessageToCustomerFromOrder','StoreController@sendMessageToCustomerFromOrder')->middleware('cors');
Route::post('/orderCustomerInfo','StoreController@orderCustomerInfo')->middleware('cors');
Route::post('/filterOrdersByDueDate','StoreController@filterOrdersByDueDate')->middleware('cors');
Route::post('/saveStoreProfile','StoreController@saveStoreProfile')->middleware('cors');
Route::post('/getDeclineOrderInfo','StoreController@getDeclineOrderInfo')->middleware('cors');
Route::post('/getTop3Products','StoreController@getTop3Products')->middleware('cors');
Route::post('/getTop3Buyers','StoreController@getTop3Buyers')->middleware('cors');
Route::post('/saveDeliveryNote','StoreController@saveDeliveryNote')->middleware('cors');
Route::post('/storeUnseenMsg','StoreController@storeUnseenMsg')->middleware('cors');


Route::post('/getCustomerName','CustomerController@getCustomerName')->middleware('cors');
Route::get('/getCustomerProductCategories','CustomerController@getCustomerProductCategories')->middleware('cors');
Route::post('/customersearchProduct','CustomerController@customersearchProduct')->middleware('cors');
Route::post('/customerfetchSingleProduct','CustomerController@customerfetchSingleProduct')->middleware('cors');
Route::post('/getMoreProducts','CustomerController@getMoreProducts')->middleware('cors');
Route::post('/getAllCartStore','CustomerController@getAllCartStore')->middleware('cors');
Route::post('/getAllCartItems','CustomerController@getAllCartItems')->middleware('cors');
Route::post('/saveToCart','CustomerController@saveToCart')->middleware('cors');
Route::post('/updateCartProductQty','CustomerController@updateCartProductQty')->middleware('cors');
Route::post('/getPaymentTermForCheckOut','CustomerController@getPaymentTermForCheckOut')->middleware('cors');
Route::post('/removeCartProduct','CustomerController@removeCartProduct')->middleware('cors');
Route::post('/viewCheckOutCart','CustomerController@viewCheckOutCart')->middleware('cors');
Route::post('/cartCheckOut','CustomerController@cartCheckOut')->middleware('cors');
Route::post('/getAllOrderStore','CustomerController@getAllOrderStore')->middleware('cors');
Route::post('/getAllOrderItems','CustomerController@getAllOrderItems')->middleware('cors');
Route::post('/checkCustomerValidity','CustomerController@checkCustomerValidity')->middleware('cors');
Route::post('/customerGetProfile','CustomerController@customerGetProfile')->middleware('cors');
Route::post('/sendSingleOrder','CustomerController@sendSingleOrder')->middleware('cors');
Route::post('/saveCustomerProfile','CustomerController@saveCustomerProfile')->middleware('cors');
Route::post('/sendMessageToStore','CustomerController@sendMessageToStore')->middleware('cors');
Route::post('/customerGetMessageHeads','CustomerController@customerGetMessageHeads')->middleware('cors');
Route::post('/CustomerViewConvo','CustomerController@CustomerViewConvo')->middleware('cors');
Route::post('/sendReplyToStore','CustomerController@sendReplyToStore')->middleware('cors');
Route::post('/getCustomerSingleOrderItems','CustomerController@getCustomerSingleOrderItems')->middleware('cors');
Route::post('/orderStoreInfo','CustomerController@orderStoreInfo')->middleware('cors');
Route::post('/customerUnseenMsg','CustomerController@customerUnseenMsg')->middleware('cors');
Route::post('/filterCustomerOrderList','CustomerController@filterCustomerOrderList')->middleware('cors');
Route::post('/filterCustomerOrderListByDueDate','CustomerController@filterCustomerOrderListByDueDate')->middleware('cors');
Route::post('/getOrderDueToday','CustomerController@getOrderDueToday')->middleware('cors');
Route::post('/CustomerisEmailVerified','CustomerController@CustomerisEmailVerified')->middleware('cors');

Route::post('/uploadProductImageForAndroid','androidCameraController@uploadProductImageForAndroid')->middleware('cors');
Route::post('/emailconf','emailcontroller@userRegistration')->middleware('cors');

Route::post('/emailconf','emailcontroller@userRegistration')->middleware('cors');

Route::post('/verifyyawa','UserController@verifyyawa')->middleware('cors');



