<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Events\StoreRequestNotificationEevent;


class RequestController extends Controller
{
    public function StoreRegistrationRequest(Request $request){
        DB::table('request')->insert([
          'userId' => $request->userId,
          'status' => '2',
          'type' => '1'
        ]);
    }
    public function fetchRequestwithFilter(Request $request){
       return DB::table('request')
        ->join('user','user.id','=','request.userId')
        ->join('usertype','user.userType','=','usertype.id')
        ->join('requesttype','requesttype.id','=','request.type')
        ->join('requeststatus','requeststatus.id','=','request.status')
        ->where('request.status','=','2')
        ->where('request.type','=',$request->requestType)
        ->select('request.id as id','requeststatus.status as status','requesttype.type as requestType','usertype.type as userType',DB::raw('CONCAT(user.firstname," ",user.lastname) as username'))->get();


    }
    public function fetchRequestAll(Request $request){
        return DB::table('request')
        ->join('user','user.id','=','request.userId')
        ->join('usertype','user.userType','=','usertype.id')
        ->join('requesttype','requesttype.id','=','request.type')
        ->join('requeststatus','requeststatus.id','=','request.status')
        ->where('request.status','=','2')
        ->select('request.id as id','requeststatus.status as status','requesttype.type as requestType','usertype.type as userType','requesttype.id as requestTypeId', DB::raw('CONCAT(user.firstname," ",user.lastname) as username'))->get();

    }
    public function viewSingleRequest(Request $request){
      return DB::table('request')
      ->join('user','user.id','=','request.userId')
      ->where('request.id','=',$request->id)
      ->select('request.categoryrequested as categoryrequest',DB::raw('CONCAT(user.firstname," ",user.lastname) as fullname'),'user.phone as phone','user.email as email','user.address as address','request.id as id')
      ->get();
    }
    public function acceptRequest(Request $request){

        DB::select('call acceptRequest(?,?,?)',array(
            $request->requestId,
            $request->requestType,
            $request->accomodatedBy
        ));
        if($request->requestType == 3){
            DB::table('productcategory')->insert(['category' => $request->category]);
        }
        $storeId = DB::table('request')->where('id','=',$request->requestId)->select('userId')->get();


        broadcast(new StoreRequestNotificationEevent($storeId));
    }
    public function rejectRequest(Request $request){
        DB::select('call rejectRequest(?,?,?)',array(
           $request->requestId,
           $request->requestConcern,
           $request->accomodatedBy
        ));
    }
    public function viewRejectedRequest(Request $request){
     return DB::table('request')
      ->join('user','user.id','=','request.userId')
      ->join('requestconcern','requestconcern.requestId','=','request.id')
      ->where('request.id','=',$request->requestId)
      ->select(DB::raw('CONCAT(user.firstname," ",user.lastname) as fullname'), 'user.phone as phone','user.email as email','user.address as address','request.id as id','requestconcern.concern as concern')
      ->get();
    }
    // need more edit dli pa final
    public function acceptSubscriptionRequest(Request $request){
        DB::table('storeaccess')->where('storeId','=',$requst->storeId)
        ->update(['expiryDate'=>DB::raw("DATE_ADD(expiryDate), INTERVAL 30 DAY")]);

        DB::table('user')->where('storeId','=',$request->storeId)
        ->update(['isBlocked' => '0']);
    }
    public function getValidatedRequest(Request $request){
        return DB::table('request')
        ->join('user','user.id','=','request.userId')
        ->join('usertype','user.userType','=','usertype.id')
        ->join('requesttype','requesttype.id','=','request.type')
        ->join('requeststatus','requeststatus.id','=','request.status')
        ->join('requestnotification','requestnotification.requestId','=','request.id')
        ->join('user as u1','u1.id','=','requestnotification.accomodatedBy')
        ->leftJoin('requestconcern','requestconcern.requestId','=','request.id')
        ->where('request.status','<>','2')
        ->select('request.id as id',
        'requeststatus.status as status',
        'requesttype.type as requestType',
        'usertype.type as userType',
        'requesttype.id as requestTypeId',
        'requestconcern.concern as concern',
        'requestnotification.dateaccomodated as accomodated_date',
         DB::raw('CONCAT(u1.firstname," ",u1.lastname) as adminname'),
         DB::raw('CONCAT(user.firstname," ",user.lastname) as username'))
         ->orderBy('accomodated_date','desc')
         ->paginate(6);

    }
     public function sendCategoryRequest(Request $request){
         DB::table('requestcategory');
     }
    }
