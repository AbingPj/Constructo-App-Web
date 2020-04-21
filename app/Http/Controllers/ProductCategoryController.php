<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductCategoryController extends Controller
{
    public function addProductCategory(Request $request){
        DB::table('productcategory')->insert([
         'category' => $request->category
        ]);
    }
    public function fetchProductCategory(Request $request){
        return DB::table('productcategory')->paginate(6);
    }
    public function updateProductCategory(Request $request){
       DB::table('productcategory')->where('id','=',$request->id)->update([ 'category' => $request->name]);
    }

}
