<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class AdminController extends Controller
{
    public function adminAddingProductCategory(Request $request){
        DB::table('productcategory')
        ->insert([
            'category' => $request->category
        ]);
    }
    public function getAdminProfile(Request $request){
        return DB::table('user')
        ->where('id','=',$request->id)
        ->get();
    }
    public function customerCount(Request $request){
        return DB::table('user')
        ->where('userType','=','2')
        ->get();
    }
    public function storeCount(Request $request){
        return DB::table('user')
        ->where('userType','=','3')
        ->get();
    }
    public function adminCount(Request $request){
        return DB::table('user')
        ->where('userType','=','1')
        ->get();
    }
    public function registerAdmin(Request $request){
        DB::table('user')
        ->insert([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'phone' => $request->phone,
            'userType' => 1
        ]);
    }
    public function getFeaturedProducts(Request $request){
        return DB::table('storeproduct')
        ->orderBy('price','desc')
        ->take(6)
        ->get();
    }
    public function getAllNumberOfTransaction(Request $request){
        return DB::table('customerorder')
        ->get();
    }
}
