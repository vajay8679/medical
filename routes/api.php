<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormObjectTypeGroupController;
use App\Http\Controllers\FormObjectTypesController;
use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MyProductController
;

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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('form-object-types-list', [FormObjectTypesController::class,'getList']);
    Route::get('form-object_type-groups-list', [FormObjectTypeGroupController::class,'getList']);
    Route::get('form-data', [FormFieldController::class,'getForm']);
    Route::apiResource('form-object_types', FormObjectTypesController::class);
    Route::apiResource('form-object_type-groups', FormObjectTypeGroupController::class);
    Route::apiResource('form-fields', FormFieldController::class);
    Route::apiResource('lead-form', LeadController::class);
    Route::get('leadstatus/{id}', [LeadController::class,'getLead']);
    Route::apiResource('quote-form', QuoteController::class);
    Route::post('convert-lead', [LeadController::class, 'convertStatuesUpdate']);
    Route::apiResource('employee-form',EmployeeController::class);
    Route::apiResource('product-category-form',ProductCategoryController::class);
    Route::apiResource('product-form',ProductController::class);
    Route::apiResource('order-form',OrderController::class);
    Route::get('product-category-list', [ProductCategoryController::class,'getProductCate']);
    Route::get('product-category-list-all', [ProductCategoryController::class,'getProductCateAll']);
    Route::apiResource('my-product',MyProductController::class);
    Route::post('my-product-features', [MyProductController::class, 'features']);
    Route::post('my-product-upload-gallery', [MyProductController::class, 'uploadGallery']);
    Route::post('my-product-faq', [MyProductController::class, 'productFAQ']);
    Route::post('my-product-sidebar',[MyProductController::class, 'ProductSideBar']);
    Route::get('my-product-get-sidebar/{id}',[MyProductController::class, 'getproductSideBar']);
    Route::delete('delete-my-product-sidebar/{id}',[MyProductController::class, 'deleteMyProductSidebar']);
    Route::post('my-product-multi-feat',[MyProductController::class, 'Multifeatures']);
    Route::post('my-product-add-new-multi-feat',[MyProductController::class, 'AddNewMultiFeature']);
    Route::get('my-product-getmulti-features',[MyProductController::class, 'getMultifeatures']);
    Route::post('my-product-assing-features',[MyProductController::class, 'productAssingFeatures']);
    Route::post('my-product-youtube-link',[MyProductController::class, 'productyoutubelink']);
    Route::get('my-product-get-youtube/{id}',[MyProductController::class, 'getyoutubelink']);
    Route::get('get-my-product-faq/{id}', [MyProductController::class, 'getProductFAQ']);
    Route::delete('delete-my-product-faq/{id}', [MyProductController::class, 'deleteMyProductFaq']);
    Route::get('get-my-product-assign-feature/{id}', [MyProductController::class, 'getMultifeaturesProductAssign']);
    Route::get('get-my-product-slider-single/{id}/{type}', [MyProductController::class, 'getuploadGallery']);
    Route::get('get-my-product-feature/{id}', [MyProductController::class, 'getFeatures']);
    Route::delete('delete-my-product-feature/{id}', [MyProductController::class, 'deleteMyProductFeature']);
    Route::get('my-product-get-specification/{id}', [MyProductController::class, 'getProductSpecification']);
    Route::post('my-product-specification', [MyProductController::class, 'productSpecification']);
    Route::get('my-product-get-sequnece',[MyProductController::class, 'getProductSequence']);
    Route::post('my-product-sequence',[MyProductController::class, 'productSequnece']);
    Route::get('my-product-get-feature-compare/{id}', [MyProductController::class, 'getproductfeaturecompares']);
    Route::post('my-product-compares-features',[MyProductController::class, 'productFeaturesCompares']);
    Route::get('get-my-product-preview/{id}', [MyProductController::class, 'getProductPreview']);
    Route::post('my-product-image-delete',[MyProductController::class, 'deleteGalleryImages']);
    Route::post('my-product-review', [MyProductController::class, 'productReview']);
    Route::get('my-product-get-review/{id}', [MyProductController::class, 'getProductReview']);
    Route::delete('my-product-delete-review/{id}', [MyProductController::class, 'deleteProductReview']);
    Route::get('my-product-get-single-review/{id}', [MyProductController::class, 'getSingleProductReview']);
    Route::post('my-product-update-review/{id}', [MyProductController::class, 'updateProductReview']);
    Route::delete('my-product-delete-addnewfeature/{id}',[MyProductController::class,'deleteAddNewFeature']);
    Route::delete('my-product-delete-youtubelink/{id}',[MyProductController::class,'deleteProductLink']);
    Route::delete('delete-specification-field/{id}', [MyProductController::class, 'deleteMySpecification']);
    Route::delete('delete-specification-fields/{id}', [MyProductController::class, 'deleteMySubSpecification']);









    // Route::group(['middleware' => 'role:admin'], function () {
            

    // });

    // Route::group(['middleware' => 'role:user'], function () {

    // });
});
