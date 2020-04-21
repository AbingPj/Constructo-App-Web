<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Events\CustomerCheckOutEvent;
use App\Events\ChatUnseenEvent;
use App\Events\ListenToCustomerMsg;
use App\Events\SingleOrderBroadcaster;
class CustomerController extends Controller
{
    public function sendSingleOrder(Request $request){
       DB::select("call checkOutSingleOrder(?,?,?,?,?,?,?)",array(
           $request->customerId,
           $request->storeId,
           $request->paymentOption,
           $request->prodId,
           $request->prodQty,
           $request->dateTimeDeliver,
           $request->deliverAddress
       ));
       broadcast(new SingleOrderBroadcaster($request->storeId));
    }
    public function getCustomerName(Request $request){
      return DB::table('user')
        ->where('id','=',$request->customerId)
        ->select('firstname','lastname','image')
        ->get();
    }
    public function getCustomerProductCategories(Request $request){
        return DB::table('productcategory')
        ->get();
    }
    public function customersearchProduct(Request $request){
        $inputs = ['keyword'=> $request->keyword];
        $rules = ['keyword'=>'nullable'];
        $validator =  Validator::make($inputs, $rules);

        if($validator->fails()){
            return response()->json($validator->errors()->toArray());
        }else{

            $first = DB::table('storeproduct')
            ->join('user','user.id','=','storeproduct.storeId')
            ->where('storeproduct.isViewable','=','1')
            ->where('user.isblocked','=','0')
            ->select('storeproduct.id as productId');

            if($request->category == 0){ // if category is all

                if($request->term == 2){ //if cash upon pickup or term == 1
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->join('storepaymentterm','storepaymentterm.storeId','=','storeproduct.storeId')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->where('storepaymentterm.paymentTerm','=',1)
                    ->where('storepaymentterm.isOffered','=',1)
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(5);
                }else if($request->term == 1){ //if cash on delivery or term == 2
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->join('storepaymentterm','storepaymentterm.storeId','=','storeproduct.storeId')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->where('storepaymentterm.paymentTerm','=',2)
                    ->where('storepaymentterm.isOffered','=',1)
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(5);

                }else{
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(5);
                }


            }else{ // if user specify's a specific category


                if($request->term == 2){
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->join('storepaymentterm','storepaymentterm.storeId','=','user.id')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->where('storepaymentterm.paymentTerm','=',2)
                    ->where('storepaymentterm.isOffered','=',1)
                    ->where('productcategory.id','=',$request->category)
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(7);
                 //if cash on delivery or term == 1
                }else if($request->term == 1){
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->join('storepaymentterm','storepaymentterm.storeId','=','user.id')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->where('storepaymentterm.paymentTerm','=',1)
                    ->where('storepaymentterm.isOffered','=',1)
                    ->where('productcategory.id','=',$request->category)
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(7);

                }else{
                    return DB::table('storeproduct')
                    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
                    ->join('user','user.id','=','storeproduct.storeId')
                    ->where('storeproduct.name','like','%'.$request->keyword.'%')
                    ->where('productcategory.id','=',$request->category)
                    ->whereRaw('storeproduct.price <  ?',[$request->minprice])
                    ->select('user.address as location','user.isblocked as isblocked','storeproduct.isViewable as isViewable','user.id as storeId','storeproduct.id as id','productcategory.category as category','storeproduct.status as isAvailable','storeproduct.image as image','storeproduct.name as productname','storeproduct.price as price',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'storeproduct.description as productdescription')
                    ->paginate(7);
                }
            }

        }
    }
    public function customerfetchSingleProduct(Request $request){
        return DB::table('storeproduct')
        ->join('user','user.id','=','storeproduct.storeId')
        ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
        ->join('storepaymentterm','storepaymentterm.storeId','=','storeproduct.storeId')
        ->where('storeproduct.id','=',$request->id)
        ->select('user.email as storeemail','user.id as storeId','storeproduct.price as productprice','storeproduct.id as productid','user.id as storeId','storeproduct.status as status',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'user.address as location','productcategory.category as category','storeproduct.description as description','storeproduct.image as image',DB::raw('CONCAT (user.firstname," ",user.lastname) as fulllname'), 'storeproduct.name as name','storeproduct.description as description','storeproduct.price as price')
        ->distinct()
        ->get('name');
    }
    public function getMoreProducts(Request $request){
        return DB::table('storeproduct')
        ->join('user','user.id','=','storeproduct.storeId')
        ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
        ->where('user.id','=',$request->storeId)
        ->select('user.id as storeId','storeproduct.status as status',DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'user.address as location','productcategory.category as category','storeproduct.id as id','storeproduct.description as description','storeproduct.image as image',DB::raw('CONCAT (user.firstname," ",user.lastname) as fulllname'), 'storeproduct.name as name','storeproduct.description as description','storeproduct.price as price')
        ->take(6)
        ->get();
    }
    public function saveToCart(Request $request){
        DB::select('call addToCart(?,?,?,?,?)',array(
            $request->customerId,
            $request->storeId,
            0,
            $request->prodId,
            $request->qty,
        ));
    }
    public function getAllCartStore(Request $request){
      return DB::table('customercart')
      ->join('user as u1','u1.id','=','customercart.customerId')
      ->join('user as u2','u2.id','=','customercart.storeId')
      ->where('u1.id','=',$request->customerId)
      ->where('customercart.paymentOption','=','0')
      ->select('u2.image as storeimage',DB::raw('CONCAT(u2.firstname," ",u2.lastname) as storename'),'u2.id as storeId','customercart.id as cartId',DB::raw('DATE_FORMAT(customercart.date_added,"%b %d %Y") as date_added'))
      ->distinct()
      ->get('storename');

    }
    public function getAllCartItems(Request $request){
       return DB::table('cartitems')
       ->join('customercart','customercart.id','=','cartitems.cartId')
       ->join('storeproduct','storeproduct.id','=','cartitems.prodId')
       ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
       ->join('user','user.id','=','customercart.customerId')
       ->where('user.id','=',$request->customerId)
       ->where('customercart.paymentOption','=','0')
       ->select('cartitems.cartId as cartId','cartitems.prodId as productid','cartitems.totalPrice as producttotalprice','cartitems.Qty as productqty','customercart.storeId as storeId','storeproduct.name as productname','storeproduct.image as productimage','productcategory.category as productcategory','storeproduct.price as productprice')
       ->get();
    }
    public function updateCartProductQty(Request $request){
       DB::select('call updateCartProductQty(?,?,?,?)',array(
          $request->isIncrement,
          $request->productId,
          $request->productQty,
          $request->cartId
       ));

    }
    public function getPaymentTermForCheckOut(Request $request){
      return DB::table('storepaymentterm')
      ->where('storeId','=',$request->storeId)
      ->select('isOffered','note')
      ->get();
    }
    public function removeCartProduct(Request $request){
        DB::select('call deleteCartProduct(?,?)',array(
            $request->cartId,
            $request->prodId
        ));
    }
    public function viewCheckOutCart(Request $request){
      return DB::table('customercart')
      ->join('cartitems','cartitems.cartId','=','customercart.id')
      ->join('user','user.id','=','customercart.storeId')
      ->where('customercart.id','=',$request->cartId)
      ->select('customercart.id as cartId',DB::raw('SUM(cartitems.totalPrice) as totalPrice'),DB::raw('CONCAT(user.firstname," ",user.lastname) as storename'),'user.id as storeId')
      ->groupBy('customercart.id','user.firstname','user.lastname','user.id')
      ->get();
    }
    public function cartCheckOut(Request $request){
        DB::select('call checkOutCart(?,?,?,?)',array(
            $request->cartId,
            $request->paymentOption,
            $request->deliverDate,
            $request->deliverAddress
        ));
        broadcast(new CustomerCheckOutEvent($request->cartId));
    }
    public function getAllOrderStore(Request $request){
        return DB::table('customerorder')
      ->join('user as u1','u1.id','=','customerorder.customerId')
      ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
      ->join('user as u2','u2.id','=','customerorder.storeId')
      ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
      ->where('u1.id','=',$request->customerId)
      ->select('u2.image as image','customerorder.id as orderId','orderstatus.status as status','paymentterm.term as paymentTerm',DB::raw('CONCAT(u2.firstname," ",u2.lastname) as storename'),'u2.id as storeId','customerorder.id as orderId',DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered') ,DB::raw('DATE_FORMAT(customerorder.date_deliver,"%b %d %Y") as due_date') )
      ->distinct()
      ->orderBy('date_ordered','desc')
      ->paginate(7);
    }
    public function getCustomerSingleOrderItems(Request $request){
      return DB::table('customerorderitem')
      ->join('customerorder','customerorder.id','=','customerorderitem.orderId')
      ->join('storeproduct','storeproduct.id','=','customerorderitem.prodId')
      ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
      ->join('user','user.id','=','customerorder.storeId')
      ->join('orderstatus_cancelled','orderstatus_cancelled.id','=','customerorder.orderstatus')
      ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
      ->where('customerorder.id','=',$request->orderId)
      ->select('customerorderitem.orderId as orderId','customerorderitem.prodId as productid','customerorderitem.totalPrice as producttotalprice',
      'orderstatus_cancelled.status as ordercancelledstatus','customerorderitem.prodQty as productqty','customerorder.storeId as storeId','storeproduct.name as productname',
      'orderstatus.status as orderstatuslabel','customerorder.orderstatus as orderstatus','storeproduct.description as productdescription','storeproduct.image as productimage','productcategory.category as productcategory','storeproduct.price as productprice'
      ,DB::raw('DATE_FORMAT(customerorder.date_deliver,"%b %d %Y %H:%i") as datedeliver'),'customerorder.paymentOption as paymentterm','customerorder.deliver_address as customaddress','user.address as defaultaddress')
      ->get();
    }
    public function orderStoreInfo(Request $request){
        return DB::table('customerorder')
        ->where('customerorder.id','=',$request->orderId)
        ->join('user','user.id','=','customerorder.storeId')
        ->select('user.image as image','user.firstname as firstname','user.lastname as lastname','user.phone as phone','user.email as email','user.address as address')
        ->get();
    }
    public function getAllOrderItems(Request $request){
        return DB::table('customerorderitem')
        ->join('customerorder','customerorder.id','=','customerorderitem.orderId')
        ->join('storeproduct','storeproduct.id','=','customerorderitem.prodId')
        ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
        ->join('user','user.id','=','customerorder.customerId')
        ->where('user.id','=',$request->customerId)
        ->select('customerorderitem.orderId as orderId','customerorderitem.prodId as productid','customerorderitem.totalPrice as producttotalprice','customerorderitem.prodQty as productqty','customerorder.storeId as storeId','storeproduct.name as productname','storeproduct.image as productimage','productcategory.category as productcategory','storeproduct.price as productprice')
        ->get();
    }
    public function checkCustomerValidity(Request $request){
        return DB::table('user')
        ->where('id','=',$request->customerId)
        ->where('isblocked','=','1')
        ->select('id')
        ->get();
    }
    public function customerGetProfile(Request $request){
        return DB::table('user')
        ->where('id','=',$request->customerId)
        ->get();
    }
    public function saveCustomerProfile(Request $request){

        if ($request->isFromAndroid == 1) {
            $inputs = ['image'=>$request->imageFromAndroid];
            $rules = ['image'=>'required'];

            $validator = Validator::make($inputs, $rules);

            if ($validator->fails()) {
              return response()->json($validator->errors()->toArray());
            } else {
                return DB::table('user')
                ->where('id','=',$request->userId)
                ->update([
                   'image' => $request->imageFromAndroid
                ]);
            }

        } else {
            $inputs = ['image'=>$request->image];
            $rules = ['image'=>'nullable'];

            $validator = Validator::make($inputs, $rules);

            if($validator->fails()){
              return response()->json($validator->errors()->toArray());
            }else{
              if($request->hasFile('image')){
                  $image = $request->file('image');
                  $filenameWithExt = $image->getClientOriginalName();
                  $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
                  $extension = $image->getClientOriginalExtension();

                  $filenameToStore = $filename.'_'.time().'.'.$extension;
                  $path = public_path('/uploads/productImages');
                  $image->move($path,$filenameToStore);

             }else{
                 $filenameToStore = "noprofilepic.jpg";
             }
            return DB::table('user')
             ->where('id','=',$request->userId)
             ->update([
                'image' => $filenameToStore
             ]);
            }
        }
        $request->isFromAndroid = 0;


    }
    public function customerGetMessageHeads(Request $request){
        return DB::table('messagehead')
        ->join('user','user.id','=','messagehead.storeId')
        ->where('messagehead.customerId','=',$request->customerId)
        ->select('messagehead.isCustomerSeen as isSeen','user.id as storeId','messagehead.id as msgId','user.image as profilepic','user.firstname as firstname','user.lastname as lastname')
        ->get();
    }
    public function sendMessageToStore(Request $request){
        $isConvoExist = DB::table('messagehead')
        ->where('storeId','=',$request->storeId)
        ->where('customerId','=',$request->customerId)->value('id');
        if($isConvoExist!=0){
            DB::table('messagethread')
            ->insert([
               'msgId' => $isConvoExist,
               'message' => $request->message
            ]);
            broadcast(new ChatUnseenEvent($request->storeId));
            broadcast(new ListenToCustomerMsg($request->storeId));
        }else{
            DB::table('messagehead')
            ->insert([
                'customerId' => $request->customerId,
                'storeId' => $request->storeId
            ]);

             $isConvoExist = DB::table('messagehead')
              ->where('storeId','=',$request->storeId)
              ->where('customerId','=',$request->customerId)->value('id');

              DB::table('messagethread')
              ->insert([
                 'msgId' => $isConvoExist,
                 'message' => $request->message
              ]);
              DB::table('messagehead')
              ->where('id','=',$request->msgId)
              ->update([ 'isStoreSeen' => '0' ]);

              broadcast(new ChatUnseenEvent($request->storeId));
              broadcast(new ListenToCustomerMsg($request->storeId));
        }
    }
    public function CustomerViewConvo(Request $request){
        if(!isset($request->isSeen)){
            DB::table('messagehead')
            ->where('id','=',$request->msgId)
            ->update([ 'isCustomerSeen' => '1' ]);
            $request->isSeen = null;
        }

        return DB::table('messagethread')
        ->where('msgId','=',$request->msgId)
        ->orderBy('dateSended','asc')
        ->get();
    }
    public function sendReplyToStore(Request $request){
        DB::table('messagehead')
        ->where('id','=',$request->msgId)
        ->update([ 'isStoreSeen' => '0' ]);

        DB::table('messagethread')
        ->insert([
          'msgId' => $request->msgId,
          'message' => $request->message
        ]);
        broadcast(new ChatUnseenEvent($request->storeId));
        broadcast(new ListenToCustomerMsg($request->storeId));
    }
    public function customerUnseenMsg(Request $request){
        return  DB::table('messagehead')
       ->where('customerId','=',$request->customerId)
       ->where('isCustomerSeen','=','0')
       ->get();
    }
    public function filterCustomerOrderList(Request $request){
     return DB::table('customerorder')
    ->join('user as u1','u1.id','=','customerorder.customerId')
    ->join('user as u2','u2.id','=','customerorder.storeId')
    ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
    ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
    ->where('customerorder.customerId','=',$request->customerId)
    ->where('paymentterm.id','=',$request->term)
    ->where('orderstatus.id','=',$request->status)
    ->select('u1.image as image','u1.isblocked as customerisblocked','paymentterm.term as paymentTerm','orderstatus.status as status',
    DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered'),
    DB::raw('DATE_FORMAT(customerorder.date_deliver,"%b %d %Y") as due_date'),'customerorder.id as orderId',DB::raw('CONCAT(u2.firstname," ",u2.lastname) as storename'))
    ->paginate(7);
    }
    public function filterCustomerOrderListByDueDate(Request $request){
        return DB::table('customerorder')
        ->join('user as u1','u1.id','=','customerorder.customerId')
        ->join('user as u2','u2.id','=','customerorder.storeId')
        ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
        ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
        ->where('customerorder.customerId','=',$request->customerId)
        ->select('u1.id as customerId','customerorder.id as orderId', 'u1.address as customerAddress','u1.image as image',
        'paymentterm.id as paymenttermid','u1.image as customerProfile','u1.isblocked as customerisblocked',
        'paymentterm.term as paymentTerm','orderstatus.status as status','customerorder.date_deliver as datedeliver',
        DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered'),
        DB::raw('DATE_FORMAT(customerorder.date_deliver, "%b %d %Y") as due_date'),'customerorder.id as orderId',
        DB::raw('CONCAT(u2.firstname," ",u2.lastname) as storename','u2.id as storeid'))
        ->orderBy('customerorder.date_deliver',$request->orderKey)
        ->paginate(7);
    }

    public function getOrderDueToday(Request $request){
      return DB::table('customerorder')
      ->where('customerId','=',$request->customerId)
       ->select(DB::raw('DATE_FORMAT(customerorder.date_deliver, "%Y-%m-%d") as due_date'), 'orderstatus as status')->get();
    }
    public function CustomerisEmailVerified(Request $request){
        return DB::table('user')
        ->where('id','=',$request->customerId)
        ->get();
    }

}
