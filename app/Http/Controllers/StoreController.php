<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\ProductAvailabilityEvent;
use App\Events\StoreSubscriptionRequest;
use App\Events\BlockCustomerEvent;
use App\Events\ChatUnseenEvent;
use App\Events\ListenToStoreMsg;
use Validator;
class StoreController extends Controller
{
     public function getStoreProfile(Request $request){
        return DB::table('user')
        ->where('user.id','=',$request->storeId)
        ->select('user.isVerified as isVerified','user.image as image','user.firstname as firstname','user.lastname as lastname' ,'user.email as email' ,'user.phone as phone','user.address as address')
        ->get();
     }
     public function getProductCategories(Request $request){

        $first = DB::table('storeoffer')->where('storeId','=',$request->storeId);

        $second = DB::table('productcategory')
                  ->leftJoinSub($first,'first_query', function($join){
                    $join->on('first_query.productCategory','=','productcategory.id');
                  })->whereNull('first_query.productCategory')->get();



          return $second;
     }
     public function addProductCategory(Request $request){
         DB::table('storeOffer')
         ->insert([
             'storeId' => $request->storeId,
             'productCategory' => $request->productCategory
         ]);
     }
     public function loadAllProductCategories(Request $request){
        return DB::table('storeoffer')
        ->join('productcategory','productcategory.id','=','storeoffer.productCategory')
        ->where('storeoffer.storeId','=',$request->storeId)
        ->select('storeoffer.productCategory as id','productcategory.category as category')
        ->paginate(7);
     }
     public function loadAllProductCategoriesForIonic(Request $request){
        return DB::table('storeoffer')
        ->join('productcategory','productcategory.id','=','storeoffer.productCategory')
        ->where('storeoffer.storeId','=',$request->storeId)
        ->select('storeoffer.productCategory as id','productcategory.category as category')
        ->get();
     }
     public function removeProductCategory(Request $request){
         DB::table('storeoffer')
         ->where('productCategory','=',$request->categoryId)
         ->where('storeId','=',$request->storeId)
         ->delete();
     }
     public function registerProduct(Request $request){
       if($request->isFromAndroid == 1){
        $inputs = [
            'name' => $request->name,
            'productCategory' => $request->productCategory,
            'price' => $request->price,
            'imageFromAndroid' => $request->imageFromAndroid
         ];
         $rules =[
             'name' => 'required',
             'productCategory' => 'required|numeric',
             'price' => 'required|numeric',
             'imageFromAndroid' => 'required'
         ];
         if($validator->fails()){
            return response()->json($validator->errors()->toArray());
         }else{
            DB::table('storeproduct')
            ->insert([
                'storeId' => $request->storeId,
                'name' => $request->name,
                'image' => $request->imageFromAndroid,
                'productCategory' => $request->productCategory,
                'description'=> $request->description,
                'price'=>$request->price,
                'status' => '1'
            ]);
         }
        }else{
            $inputs = [
                'name' => $request->name,
                'productCategory' => $request->productCategory,
                'price' => $request->price,
                'image' => $request->image
             ];
             $rules =[
                 'name' => 'required',
                 'productCategory' => 'required|numeric',
                 'price' => 'required|numeric',
                 'image' => 'required'
             ];

            $validator= Validator::make($inputs,$rules);
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
                  $filenameToStore = "noimage.jpeg";
              }

            }
            DB::table('storeproduct')
            ->insert([
                'storeId' => $request->storeId,
                'name' => $request->name,
                'image' => $filenameToStore,
                'productCategory' => $request->productCategory,
                'description'=> $request->description,
                'price'=>$request->price,
                'status' => '1'
            ]);
            return $request->image;

        }
        $request->isFromAndroid = 0;
     }
     public function loadProducts(Request $request){
         return DB::table('storeproduct')
         ->join('user','user.id','=','storeproduct.storeId')
         ->where('user.id','=',$request->storeId)
         ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
         ->join('productstatus','productstatus.id','=','storeproduct.status')
         ->select('storeproduct.id as id','storeproduct.image as image','storeproduct.name as name','storeproduct.description as description','productcategory.category as category','storeproduct.price as price','productstatus.status as statusLabel')
         ->orderBy('storeproduct.status','desc')
         ->where('storeproduct.isViewable','=','1')
         ->paginate(7);
     }
     public function editAvailability(Request $request){
         DB::table('storeproduct')
         ->where('id','=',$request->productId)
         ->where('isViewable','=','1')
         ->update([
              'status' => $request->token
         ]);
         broadcast(new ProductAvailabilityEvent('hello'));
     }
     public function getStoreAccess(Request  $request){
        return DB::table('storeaccess')
        ->where('storeId','=',$request->storeId)
        ->select('isFreetrial',DB::raw("DATEDIFF(expiryDate, NOW()) as daysLeft"))
        ->get();
     }
     public function getProductThatHasCategory(Request $request){
        return  DB::table('storeproduct')
         ->where('productCategory','=',$request->categoryId)
         ->where('storeId','=',$request->storeId)
         ->select('id')->get();

     }
     public function removeProduct(Request $request){
         DB::table('storeproduct')
         ->where('id','=',$request->id)
         ->update([
             'isViewable' => '0'
         ]);
     }
     public function loadNotification(Request $request){
       return DB::table('requestnotification')
      ->join('request','request.id','=','requestnotification.requestId')
      ->join('user as u1','u1.id','=','request.userId')
      ->join('user as u2','u2.id','=','requestnotification.accomodatedBy')
      ->join('requeststatus','requeststatus.id','=','request.status')
      ->join('requesttype','requesttype.id','=','request.type')
      ->leftJoin('requestconcern','requestconcern.requestId','=','request.id')
      ->where('u1.id','=',$request->storeId)
      ->where('requestnotification.isViewable','=','1')
      ->select('request.categoryrequested as categoryrequest',DB::raw('CONCAT(u2.firstname," ",u2.lastname) as accomodatedBy'),DB::raw('DATE_FORMAT(requestnotification.dateaccomodated," %M %d %Y") as dateaccomodated'),'requeststatus.status as status','requesttype.type as type','request.id as requestId','requestconcern.concern as concern')
      ->orderBy('dateaccomodated','desc')
      ->paginate(7);
     }
     public function viewSingleProduct(Request $request){
        return  DB::table('storeproduct')
         ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
         ->where('storeproduct.id','=',$request->productId)
         ->select('storeproduct.id as id','storeproduct.image as image','storeproduct.name as name','storeproduct.description as description','storeproduct.price as price')
         ->get();
     }
     public function updateProduct(Request $request){
      if($request->isFromAndroid == 1){
          $inputs = [
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'description'=>$request->description,
            'category' => $request->category,
            'imageFromAndroid' => $request->imageFromAndroid
          ];
            $rules = [
             'name'=>'required',
             'category'=>'required',
             'price'=>'required',
             'description'=>'required',
             'category' => 'required|numeric',
             'imageFromAndroid' => 'required'
          ];
          $validator= Validator::make($inputs,$rules);
        if($validator->fails()){
            return response()->json($validator->errors()->toArray());
        }else{
            return  DB::table('storeproduct')
            ->where('id','=',$request->id)
            ->update([
              'name' => $request->name,
              'productCategory' =>$request->category,
              'price'=>$request->price,
              'description'=>$request->description,
              'image' => $request->imageFromAndroid
            ]);
        }
      }else{
        $inputs = [
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'description'=>$request->description,
            'productimage' => $request->image,
            'category' => $request->category
        ];
        $rules = [
          'name'=>'required',
          'category'=>'required',
          'price'=>'required',
          'description'=>'required',
          'productimage' => 'nullable|max:10250',
          'category' => 'required|numeric'
        ];

        $validator= Validator::make($inputs,$rules);
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
               $filenameToStore = "noimage.jpeg";
           }

            return  DB::table('storeproduct')
            ->where('id','=',$request->id)
            ->update([
              'name' => $request->name,
              'productCategory' =>$request->category,
              'price'=>$request->price,
              'description'=>$request->description,
              'image' => $filenameToStore
            ]);
         }
       }

     }
     public function saveProfilePicture(Request $request){
         if($request->isFromAndroid == 1){
               $rules = [
                   'profile' => 'required'
               ];
               $inputs = [
                   'profile' => $request->imageFromAndroid
               ];
               $validator = Validator::make($inputs, $rules);
              if($validator->fails()){
                 return response()->json($validator->errors()->toArray());
              }else{
                  DB::table('user')
                  ->where('id' ,'=', $request->storeId)
                  ->update([
                     'image' => $request->imageFromAndroid
                  ]);
              }
         }else{
            $rules = [
                'profile' => 'image|nullable|max:10250'
              ];
              $inputs = [
                'profile' => $request->profile
              ];

              $validator = Validator::make($inputs, $rules);
              if($validator->fails()){
                 return response()->json($validator->errors()->toArray());
              }else{
                if($request->hasFile('profilePicture')){
                  $profile = $request->file('profilePicture');
                  $filenameWithExt = $image->getClientOriginalName();
                  $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
                  $extension = $image->getClientOriginalExtension();
                  $filenameToStore = $filename.'_'.time().'.'.$extension;
                  $path = url('/uploads/productImages');
                  $image->move($path,$filenameToStore);
                }else{
                    $filenameToStore = "noimage.jpeg";
                }
                 DB::table('user')
                 ->where('id','=',$request->storeId)
                 ->update([
                   'image' => $filenameToStore
                 ]);
              }
         }
     }
     public function loadProfilePicture(Request $request){
       return DB::table('user')
       ->where('id','=',$request->storeId)
       ->select('image')
       ->get();
     }
     public function CUPisOffered(Request $request){
        DB::table('storepaymentterm')
        ->where('storeId','=',$request->storeId)
        ->where('paymentTerm','=','1')
        ->update(['isOffered'=>$request->isOffered]);
     }
     public function CODisOffered(Request $request){
        DB::table('storepaymentterm')
        ->where('storeId','=',$request->storeId)
        ->where('paymentTerm','=','2')
        ->update(['isOffered'=>$request->isOffered]);
     }
     public function loadPaymentTerms(Request $request){
       return DB::table('storepaymentterm')
         ->where('storeId','=',$request->storeId)
         ->select('paymentTerm','isOffered','note')
         ->get();

   }
   public function hideStore(Request $request){
    DB::table('user')->where('id','=',$request->storeId)->update(['isblocked'=>'1']);
   }
   public function sendSubscriptionRequest(Request $request){
     DB::table('request')->insert([
         'userId'=> $request->userId,
         'status'=>'2',
         'type' => '2'
         ]);
   }
   public function checkSendSubscriptionRequest(Request $request){
     return DB::table('request')
      ->where('status','=','2')
      ->where('type','=','2')
      ->where('userId','=',$request->storeId)
      ->get();

      broadcast( new StoreSubscriptionRequest('hello'));
   }
   public function removeNotification(Request $request){
       DB::table('requestnotification')
       ->where('requestnotification.requestId','=',$request->requestId)
       ->update([
           'isViewable'=>'0'
       ]);
   }
   public function getStoreIdFromCustomerOrder(Request $request){
       return DB::table('customercart')
       ->where('id','=',$request->cartId)
       ->select('storeId')
       ->get();
   }
   public function getAllOrders(Request $request){
      return DB::table('customerorder')
       ->join('user as u1','u1.id','=','customerorder.customerId')
       ->join('user as u2','u2.id','=','customerorder.storeId')
       ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
       ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
       ->where('customerorder.storeId','=',$request->storeId)
       ->select('u1.id as customerId','u1.address as customerAddress',
       'paymentterm.id as paymenttermid','u1.image as customerProfile',
       'u1.isblocked as customerisblocked',
       'u1.image as image','paymentterm.term as paymentterm','orderstatus.status as status',
       DB::raw('DATE_FORMAT(customerorder.date_deliver,"%b %d %Y") as datedeliver'),
       DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered'),
       'customerorder.id as orderId',
       DB::raw('CONCAT(u1.firstname," ",u1.lastname) as customerName'))
       ->paginate(7);
   }
   public function StoregetAllOrderItems(Request $request){

    return DB::table('customerorderitem')
    ->join('customerorder','customerorder.id','=','customerorderitem.orderId')
    ->join('storeproduct','storeproduct.id','=','customerorderitem.prodId')
    ->join('productcategory','productcategory.id','=','storeproduct.productCategory')
    ->join('user','user.id','=','customerorder.customerId')
    ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
    ->where('customerorder.id','=',$request->orderId)
    ->select('customerorderitem.orderId as orderId','customerorderitem.prodId as productid',
    'customerorderitem.totalPrice as producttotalprice',
    'customerorderitem.prodQty as productqty','customerorder.storeId as storeId',
    'storeproduct.name as productname',
    'orderstatus.status as orderstatuslabel','customerorder.orderstatus as orderstatus',
    'storeproduct.description as productdescription','storeproduct.image as productimage',
    'productcategory.category as productcategory','storeproduct.price as productprice'
    ,DB::raw('DATE_FORMAT(customerorder.date_deliver,"%b %d %Y %H:%i") as datedeliver'),
    'customerorder.paymentOption as paymentterm','customerorder.deliver_address as customaddress',
    'user.address as defaultaddress')

    ->get();
   }
   public function getDeclineOrderInfo(Request $request){
       return DB::table('cancelledorder')
       ->join('orderstatus_cancelled','orderstatus_cancelled.id','=','cancelledorder.cancellstatus')
       ->select('orderstatus_cancelled.status as status')
       ->where('cancelledorder.orderId','=',$request->orderId)
       ->get();
   }
   public function viewSingleOrder(Request $request){
       return DB::table('customerorder')
       ->where('customerorder.id','=',$request->orderId)
       ->where('token','=','1')
       ->where('orderstatus','=','1')
       ->join('user','user.id','=','customerorder.customerId')
       ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
       ->select('paymentterm.id as paymenttermid','paymentterm.term as paymentterm',DB::raw('CONCAT(user.firstname," ",user.lastname) as customerName'),'customerorder.id as orderId','customerorder.date_ordered as date_ordered','customerorder.paymentOption as paymentOption')
       ->get();
   }
   // para ni sya sa store na e cancell and order sa store
   public function cancellCustomerOrder(Request $request){
       DB::select('call cancellCustomerOrder(?,?)',array(
           $request->orderId,
           $request->cancellType
       ));
       if($request->cancellType == 1){
           broadcast(new BlockCustomerEvent($request->orderId));
       }
   }
   public function acceptCustomerOrder(Request $request){
       DB::table('customerorder')
       ->where('id','=',$request->orderId)
       ->update([
           'orderstatus' => $request->status
       ]);
   }
   public function getCustomerViaOrder(Request $request){
       return DB::table('customerorder')
       ->where('id','=',$request->orderId)
       ->select('customerId')
       ->get();
   }
   public function sendCategoryRequest(Request $request){
       DB::table('request')
       ->insert([
            'userId' => $request->storeId,
            'status' => $request->status,
            'type' => $request->type,
            'categoryrequested' => $request->category
       ]);
   }
   public function filterOrder(Request $request){
    return DB::table('customerorder')
    ->join('user as u1','u1.id','=','customerorder.customerId')
    ->join('user as u2','u2.id','=','customerorder.storeId')
    ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
    ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
    ->where('customerorder.storeId','=',$request->storeId)
    ->where('paymentterm.id','=',$request->term)
    ->where('orderstatus.id','=',$request->status)
    ->select('u1.image as image','u1.isblocked as customerisblocked','paymentterm.term as paymentterm','orderstatus.status as status',DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered'),'customerorder.id as orderId',DB::raw('CONCAT(u1.firstname," ",u1.lastname) as customerName'))
    ->get();
   }
   function storeGetMessageHeads(Request $request){
       return DB::table('messagehead')
       ->join('user','user.id','=','messagehead.customerId')
       ->where('messagehead.storeId','=',$request->storeId)
       ->select('messagehead.isStoreSeen as isSeen','user.id as customerId','messagehead.id as msgId','user.image as profilepic','user.firstname as firstname','user.lastname as lastname')
       ->get();
   }
   public function storeViewConvo(Request $request){

    if(!isset($request->isSeen)){
        DB::table('messagehead')
       ->where('id','=',$request->msgId)
       ->update([ 'isStoreSeen' => '1']);
       $request->isSeen = null;
    }
    return DB::table('messagethread')
       ->where('msgId','=',$request->msgId)
       ->get();


   }
   public function sendMessageToCustomer(Request $request){
     DB::table('messagehead')
     ->where('id','=',$request->msgId)
     ->update(['isCustomerSeen'  => '0']);

     DB::table('messagethread')
     ->insert([
         'msgId' => $request->msgId,
         'message' => $request->message,
         'isFromStore' => 1
     ]);
     broadcast(new ListenToStoreMsg($request->customerId ));

   }
   public function sendMessageToCustomerFromOrder(Request $request){
    $isConvoExist = DB::table('messagehead')
    ->where('storeId','=',$request->storeId)
    ->where('customerId','=',$request->customerId)->value('id');
    if($isConvoExist!=0){
        DB::table('messagethread')
        ->insert([
           'msgId' => $isConvoExist,
           'message' => $request->message,
           'isFromStore' => 1
        ]);
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
             'message' => $request->message,
             'isFromStore' => 1
          ]);
          DB::table('messagehead')
          ->where('id','=',$request->msgId)
          ->update(['isCustomerSeen'  => '0']);

      broadcast(new ListenToStoreMsg($request->customerId ));
    }
   }
   public function orderCustomerInfo(Request $request){
      return DB::table('customerorder')
       ->where('customerorder.id','=',$request->orderId)
       ->join('user','user.id','=','customerorder.customerId')
       ->select('user.image as image','user.firstname as firstname','user.lastname as lastname','user.phone as phone','user.email as email','user.address as address')
       ->get();
   }
   public function filterOrdersByDueDate(Request $request){
    return DB::table('customerorder')
    ->join('user as u1','u1.id','=','customerorder.customerId')
    ->join('user as u2','u2.id','=','customerorder.storeId')
    ->join('orderstatus','orderstatus.id','=','customerorder.orderstatus')
    ->join('paymentterm','paymentterm.id','=','customerorder.paymentOption')
    ->where('customerorder.storeId','=',$request->storeId)
    ->select('u1.id as customerId','u1.address as customerAddress','u1.image as image',
    'paymentterm.id as paymenttermid','u1.image as customerProfile','u1.isblocked as customerisblocked',
    'paymentterm.term as paymentterm','orderstatus.status as status','customerorder.date_deliver as datedeliver',
    DB::raw('DATE_FORMAT(customerorder.date_ordered,"%b %d %Y") as date_ordered'),'customerorder.id as orderId',
    DB::raw('CONCAT(u1.firstname," ",u1.lastname) as customerName'))
    ->orderBy('customerorder.date_deliver',$request->orderKey)
    ->get();
   }
   public function saveStoreProfile(Request $request){
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
   public function getTop3Buyers(Request $request){
    return DB::table('customerorderitem')
    ->join('storeproduct','storeproduct.id','=','customerorderitem.prodId')
    ->join('user as u1','u1.id','=','customerorderitem.storeId')
    ->join('user as u2','u2.id','=','customerorderitem.customerId')
    ->where('u1.id','=',$request->storeId)
    ->groupBy('customerorderitem.customerId')
    ->select( DB::raw('COUNT(customerorderitem.customerId) as totalBuyers'),'u2.firstname as firstname','u2.lastname as lastname','u2.address as address','u2.image as profilepic')
    ->get();
   }
   public function getTop3Products(Request $request){
    return DB::table('customerorderitem')
    ->join('storeproduct','storeproduct.id','=','customerorderitem.prodId')
    ->join('user','user.id','=','customerorderitem.storeId')
    ->where('user.id','=',$request->storeId)
    ->where('storeproduct.isViewable','=','1')
    ->groupBy('customerorderitem.prodId')
    ->select('storeproduct.image as image','storeproduct.name as name','storeproduct.description as description','storeproduct.price as price','customerorderitem.prodId', DB::raw('SUM(customerorderitem.prodQty) as totalQty'))
    ->get();
   }
   public function saveDeliveryNote(Request $request){
     DB::table('storepaymentterm')
     ->where('storeId','=',$request->storeId)
     ->where('paymentTerm','=','2')
     ->update([
         'note' => $request->note
     ]);
   }
   public function storeUnseenMsg(Request $request){
     return  DB::table('messagehead')
       ->where('storeId','=',$request->storeId)
       ->where('isStoreSeen','=','0')
       ->get();
   }

}

