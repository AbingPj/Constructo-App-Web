<template>
 <div>
 <myHeader></myHeader>
 <loading v-if="showLoading == true"> </loading>
 <div class="container mt-2">
     <div class="card">
         <div class="card-header"> <span style="font-size: 17px;">Order #{{orderId}} </span> </div>
     </div>
     <!-- Customer Information -->
     <div class="card mt-2" v-for="data in orderCustomerInfo">
         <div class="card-header"> <span style="font-size: 16px;"> <img :src="'uploads/productImages/'+data.image" style="width: 40px; height: 40px; border-radius: 50%;">  Customer Information </span> </div>
         <div class="card-body" >
             <div class="row p-2" >
                 <div class="col-sm-12"> <span style="font-size: 16px; text-transform: capitalize;"> <i class="fas fa-address-book"> </i> {{data.firstname}} {{data.lastname}} </span>
                   </div>
                   <div class="col-sm-12 mt-2">
                             <span style="font-size: 16px;"> <i class="fas fa-at"> </i> {{data.email}}  </span>
                   </div>
                   <div class="col-sm-12 mt-2">
                             <span style="font-size: 16px;"> <i class="fas fa-phone"> </i> {{data.phone}}  </span>
                   </div>

                   <div class="col-sm-12 mt-2">
                             <span style="font-size: 16px;"> <i class="fas fa-map-marker"> </i> {{data.address}} </span>
                   </div>
              </div>
          </div>
     </div>
    <!-- Ordered Itesm -->
     <div class="card mt-2">
         <div class="card-header"> <span style="font-size: 16px;"><i class="fas fa-boxes"> </i> Ordered Items </span></div>
         <div class="card-body" >
             <div class="row" v-for="orderItem in orderItems">
                  <div class="col-sm-1" style="margin-right: 3px; float: left;"><img :src="'/uploads/productImages/'+orderItem.productimage"  width="60" height="60" >
               </div>
                <div class="col-sm-3 ml-2"> <span style="font-size: 16px; font-weight: 500;"> {{orderItem.productname}} <br>  {{orderItem.productdescription}} </span> </div>
                <div class="col-sm-2"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  {{orderItem.productqty}} pieces</span></div>
                 <div class="col-sm-2" v-if="orderItem.productprice != 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  ₱{{orderItem.productprice}} each </span></div>
                 <div class="col-sm-2" v-if="orderItem.productprice == 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  Negotiable </span></div>
                 <div class="col-sm-3" v-if="orderItem.producttotalprice != 0 && orderItem.productprice != 0 "> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  ₱{{orderItem.producttotalprice}} </span></div>
                 <div class="col-sm-3" v-if="orderItem.producttotalprice == 0 || orderItem.productprice == 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  Negotiable </span></div>
             </div>
            </div>
     </div>
     <!-- Ordered Info -->
     <div class="card mt-2">
         <div class="card-header"> <span style="font-size: 16px;"><i class="fas fa-info-circle"> </i> Order Information </span></div>
         <div class="card-body" v-for="( orderItem, index  )  in orderItems"   v-if="index  == orderItems.length-1">
             <div class="row p-1">
               <div class="col-3" style="font-size: 16px;"> About to be <span v-if="orderItem.paymentterm == 2" style="font-size: 16px;">  Delivered </span>  <span style="font-size: 16px;" v-if="orderItem.paymentterm == 1"> Picked up </span> on : </div>
               <div class="col-9">
                   <span style="font-size: 16px;color: #fd7e14;"> {{orderItem.datedeliver}} <span v-if="isAM == true"> AM   </span> <span v-if="isAM == false">PM  </span></span>
               </div>
             </div>
             <div class="row p-1">
                 <div class="col-3" style="font-size: 16px;"> To be <span v-if="orderItem.paymentterm == 2" style="font-size: 16px;">  Delivered </span>  <span v-if="orderItem.paymentterm == 1" style="font-size: 16px;"> Picked up </span> at : </div>
                 <div class="col-9"> <span v-if="orderItem.customaddress != null" style="text-transform: capitalize;color: #fd7e14;"> {{orderItem.customaddress}} </span> <span style="text-transform: capitalize;color: #fd7e14;" v-if="orderItem.customaddress == null"> {{orderItem.defaultaddress}} </span></div>
             </div>
              <div class="row p-1 justify-content-center">
                  <div class="col-3"> <span style="font-size: 16px;"> Total Amount :  </span> </div>
               <div class="col-9">
                   <span style="font-size: 19px; color:#fd7e14; font-weight: bold; " v-if="orderTotalAmount != 0 && orderItem.productprice != 0">₱{{parseFloat(orderTotalAmount)}} </span>
                   <span style="font-size: 19px; color:#fd7e14; font-weight: bold; " v-else> Negotiable </span>
               </div>

             </div>
             <div v-if="isManagable == true">
                 <div class="row" v-if="orderItem.orderstatus == 1">
                     <div class="col-6"> <button class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white; font-size: 16px;" @click="acceptOrder(orderItem.paymentterm)"> <i class="fas fa-thumbs-up"> </i> Accept Order </button> </div>
                     <div class="col-6"> <button class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white; font-size: 16px;" data-toggle="modal" data-target="#declineOrder"> <i class="fas fa-thumbs-down"> </i> Decline Order </button> </div>
                 </div>
             </div>
             <div class="row" v-if="isManagable == false">
                <div class="col-12">
                    <button class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white; font-size: 16px;" disabled> You can only manage this order on {{orderItem.datedeliver}} <span v-if="isAM == true"> AM  </span> <span v-if="isAM == false"> PM </span> </button>
                </div>
             </div>
             <div class="row mt-2" v-if="orderItem.orderstatus != 1">
                   <div class="col-12">
                     <button v-if="orderItem.orderstatus == 2 || orderItem.orderstatus == 3" type="button" class="btn form-control" disabled style="border-radius: 0px; background-color:#fd7e14;color:white; font-size: 16px;"> <i class="fas fa-info-circle"> </i> {{orderItem.orderstatuslabel}}  </button>
                     <button v-else-if="orderItem.orderstatus != 3 || orderItem.orderstatus != 2" type="button" class="btn form-control" disabled style="border-radius: 0px; background-color:#f44242;color:white; font-size: 16px;"> <i class="fas fa-sad-tear"> </i> {{orderItem.orderstatuslabel}} with a concern of
                      <span v-if="declineInfo.length !=0 "> {{declineInfo[0].status}} </span>
                      </button>
                   </div>
               </div>
         </div>
     </div>
 </div>
 <!-- Declining order modal -->
 <div class="modal fade" id="declineOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="card">
                <div class="card-body" v-for="(orderItem, index) in orderItems"  v-if="index  == orderItems.length-1">
                    <div class="row mt-2 border-top">
                         <div class="col-3 mt-3">
                           <p> Reason :</p>
                         </div>
                         <div class="col-9 mt-3">
                             <select class="form-control" style="border-radius: 0px;font-size: 13px;" v-model="manageOrderCODcancelstatus" v-if="orderItem.paymentterm == 2">
                                 <option :value="1"> The customer did not show up during arrival. </option>
                                 <option :value="2"> Either the delivery truck or the delivery personel is not available. </option>
                             </select>
                             <select class="form-control" style="border-radius: 0px;font-size: 13px;" v-model="manageOrderCUPcancelstatus" v-if="orderItem.paymentterm == 1">
                                 <option :value="3"> The customer did not pick up the item before the due date has passed. </option>
                                 <option :value="4"> There are no available item. </option>
                             </select>
                         </div>
                     </div>
                     <div class="row mt-2" >
                   <div class="col-6">
                       <button type="button" class="btn form-control" style="border-radius: 0px; background-color:#fd7e14;color:white;" @click="declineOrder(orderItem.paymentterm)"><i class="fas fa-save"> </i> Save</button>
                   </div>
                   <div class="col-6">
                       <button type="button" class="btn form-control" style="border-radius: 0px; background-color:#fd7e14;color:white;" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Close</button>
                   </div>
               </div>

                </div>
            </div>
        </div>
    </div>
  </div>
</div>
 </div>

</template>

<script>
 import myHeader from './myHeader';
 import Loading from './Loading';
export default {
  components: {
      'myHeader' : myHeader,
      'loading' : Loading
  },
  data : function(){
      return{
          orderId : localStorage.getItem('orderId'),
          orderCustomerInfo : [],
          orderItems: [],
          orderTotalAmount : 0,
          manageOrderCUPstatus : 3,
          manageOrderCODstatus : 2,
          manageOrderCUPcancelstatus: 3,
          manageOrderCODcancelstatus : 1,
          isAM : false,
          showLoading : false,
          orderInfo : [],
          declineInfo : [],
          date_now : new Date(),
          isManagable : true
      }
  },
  methods : {
      loadCustomerInfo : function(){
        let data = {orderId : this.orderId};
         axios.post('api/orderCustomerInfo',data).then(response=>{
           this.orderCustomerInfo = response.data;
         });
      },
      loadOrderedItems : function(){
        let data = {orderId : this.orderId};
        axios.post('api/StoregetAllOrderItems',data).then(response=>{
            this.isOrderManagable(response.data[0].datedeliver);
          this.orderItems = response.data;
          this.orderInfo = response.data[0];
           console.log(this.orderItems);
         for(let i= 0; i < this.orderItems.length ; i++){
           this.orderTotalAmount = parseFloat(this.orderTotalAmount) + parseFloat(this.orderItems[i].producttotalprice);
         }
         var fullDate = this.orderItems[0].datedeliver.slice(0,12);
         var tempMinutes = this.orderItems[0].datedeliver.slice(14,17);
         var tempTime = parseFloat(this.orderItems[0].datedeliver.slice(12,17));
         if(tempTime > 12){
             this.isAM = false;
           let temp = tempTime - 12;
           this.orderItems[0].datedeliver= fullDate+""+temp+""+tempMinutes;
          }else{
              this.isAM = true;
          }
            })
      },
      acceptOrder: function(paymentterm){
          this.showLoading = true;
          //is CUP
          if(paymentterm == 1){
             let data ={orderId : this.orderId,status: 3};
             axios.post('api/acceptCustomerOrder',data).then(response=>{
              this.showLoading = false;
              this.loadOrderedItems();
             });
          //is COD
          }else if(paymentterm == 2){
             let data ={orderId : this.orderId,status: 2};
             axios.post('api/acceptCustomerOrder',data).then(response=>{
              this.showLoading = false;
              this.loadOrderedItems();
             });
          }
      },
      declineOrder: function(paymentterm){
       this.showLoading = true;
        if(paymentterm == 1){
           let data = {orderId : this.orderId , cancellType : this.manageOrderCUPcancelstatus};
              axios.post('api/cancellCustomerOrder',data).then(response=>{
                  this.loadOrderedItems();
                 this.showLoading = false;
              }, error => {
                  this.loadOrderedItems();
                 this.showLoading = false;
              });
        }else if(paymentterm == 2){
             let data = {orderId : this.orderId , cancellType : this.manageOrderCODcancelstatus };
              axios.post('api/cancellCustomerOrder',data).then(response=>{
                  this.loadOrderedItems();
                 this.showLoading = false;
              }, error => {
                  this.loadOrderedItems();
                 this.showLoading = false;
              });
        }
      },
      declineReasonInfo : function(){
        let data = {orderId : this.orderId};
         axios.post('api/getDeclineOrderInfo',data).then(resp =>{
           this.declineInfo = resp.data;
         });
      },
      isOrderManagable : function(date){
        var order_date = new Date(date);
        var date_now = new Date();

       console.log("mao ni ang order date "+order_date );
       console.log("mao ni ang date now "+date_now);

        if(order_date <= date_now){
            this.isManagable = true;
        }else if(order_date > date_now){
            this.isManagable = false;
        }
      }
  },
  mounted : function(){
      this.loadCustomerInfo();
      this.loadOrderedItems();
      this.declineReasonInfo();

  }
}
</script>
<style scoped>
.card-header{
    background-color: rgb(65, 65, 66);
    color : white;
}
</style>

