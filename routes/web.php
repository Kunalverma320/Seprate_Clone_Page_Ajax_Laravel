<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
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


Route::get('/',[HomeController::class,'index'])->name('home');

//product
Route::get('/product/add',[ProductsController::class,'index'])->name('add.product');
Route::get('/products/fetch', [ProductsController::class, 'fetch'])->name('product.fetch');
Route::post('/products/submit', [ProductsController::class, 'store'])->name('product.submit');
Route::get('/products/edit', [ProductsController::class, 'edit'])->name('product.edit');
Route::post('/products/update', [ProductsController::class, 'update'])->name('product.update');
Route::post('/products/delete', [ProductsController::class, 'destroy'])->name('product.delete');


//category
Route::get('/category/add',[CategoryController::class,'index'])->name('add.category');
Route::post('/store-category', [CategoryController::class, 'storeCategory'])->name('category.store');
Route::get('/fatch-category', [CategoryController::class, 'fatchCategory'])->name('category.fetch');
Route::get('/categories/edit', [CategoryController::class, 'editCategory'])->name('category.edit');
Route::post('/categories/update', [CategoryController::class, 'updateCategory'])->name('category.update');
Route::post('/categories/delete', [CategoryController::class, 'deleteCategory'])->name('category.delete');

//category Excel
Route::post('/excel-category', [CategoryController::class, 'ExcelCategory'])->name('excel.category');


//product Excel
Route::post('/product-import', [CategoryController::class, 'Productimport'])->name('import.product');






