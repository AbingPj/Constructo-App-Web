<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Events\StoreRegistrationEvent;

class UserController extends Controller
{
    public function CreateAccount(Request $request){
        $input=[
            'firstname' => $request->firstname,
            'lastname'=>$request->lastname,
            'email' =>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'password'=>$request->password,
            'userType'=>$request->userType
        ];
        $rules=[
           'firstname'=>'required',
           'lastname'=>'required',
           'email'=>'required|email',
           'phone'=>'required|digits:11|numeric',
           'password'=>'required|min: 8',
           'address'=>'required',
           'userType'=>'required'
        ];
        $validator = Validator::make($input,$rules);

        if($validator->fails()){
            return response()->json($validator->errors()->toArray());
        }else{
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999).$characters[rand(0, strlen($characters) - 1)];
            $stringtoken = str_shuffle($pin);

            DB::select('call createAccount(?,?,?,?,?,?,?,?)',array(
                $request->firstname,
                $request->lastname,
                $request->email,
                $request->phone,
                $request->password,
                $request->address,
                $request->userType,
                $stringtoken
            ));
            broadcast(new StoreRegistrationEvent('You have a new Registration Request'));
            return $stringtoken;
        }
    }
    public function LoginAccount(Request $request){
        $input=[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        $rules=[
            'email'=>'required|email',
            'password'=>'required'
        ];
      $validator = Validator::make($input, $rules);
        if($validator->fails()){
           return response()->json($validator->errors()->toArray());
        }else{
            return DB::table('user')
              ->join('usertype','user.userType','=','usertype.id')
              ->where('user.email','=',$request->email)

              ->where('user.password','=',$request->password)
              ->select('user.image as profile','user.id as id','user.firstname as firstname','user.lastname as lastname','user.email as email','user.phone as phone','user.userType as userType')
              ->get();
      }
    }
    public function getAdminAccount(Request $request){
         return DB::table('user')
        ->where('id','=',$request->id)
        ->get();
    }
    public function emailExist(Request $request){
        return DB::table('user')
        ->where('email','=',$request->email)
        ->get();
    }
    public function phoneExist(Request $request){
        return DB::table('user')
        ->where('phone','=',$request->phone)
        ->get();
    }
    public function adminAddingProductCategory(Request $request){
        DB::table('productcategory')
        ->insert([
            'category' => $request->category
        ]);
    }
    public function getUserType(Request $request){
      return  DB::table('user')
        ->where('id','=',$request->userId)
        ->select('userType')
        ->get();
    }
    public function verifyyawa(Request $request){
       return DB::table('user')
        ->where('emailToken','=',$request->code)
        ->where('id','=',$request->userId)
        ->update([
            'isVerified' => 1
        ]);
    }

}
