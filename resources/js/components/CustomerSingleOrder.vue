<template>
  <div>
      <my-header propBrand="onstructo"> </my-header>
      <div class="container mt-2">
          <div class="card"  v-for="orderDetail in orderDetails">
              <div class="card-header"> <img :src="'uploads/productImages/'+orderDetail.image" style="width: 40px; height: 40px; border-radius: 50%;"> Store Information </div>
              <div class="card-body">
                 <div class="row p-1">
                     <div class="col-sm-12"> <i style="font-size: 16px;" class="fas fa-address-book"> </i> <span style="font-size: 16px; text-transform: capitalize;"> {{orderDetail.firstname}} {{orderDetail.lastname}}</span>  </div>
                     <div class="col-sm-12"> <i style="font-size: 16px;" class="fas fa-at"> </i> <span style="font-size: 16px;"> {{orderDetail.email}}</span>  </div>
                     <div class="col-sm-12"> <i style="font-size: 16px;" class="fas fa-phone"> </i> <span style="font-size: 16px;"> {{orderDetail.phone}}</span> </div>
                     <div class="col-sm-12"> <i style="font-size: 16px;" class="fas fa-map-marker"> </i> <span style="font-size: 16px;"> {{orderDetail.address}} </span></div>
                 </div>
              </div>
          </div>
          <div class="card mt-2">
              <div class="card-header"><i class="fas fa-boxes"> </i> Ordered Items</div>
              <div class="card-body">
                 <div class="row mt-2" v-for="orderItem in orderItems">
                  <div class="col-sm-1" style="margin-right: 3px; float: left;"><img :src="'/uploads/productImages/'+orderItem.productimage"  width="60" height="60" >
               </div>
                <div class="col-sm-3 ml-2"> <span style="font-size: 16px; font-weight: 500;"> {{orderItem.productname}} <br>  {{orderItem.productdescription}} </span> </div>
                <div class="col-sm-2"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  {{orderItem.productqty}} pieces</span></div>
                 <div class="col-sm-2" v-if="orderItem.productprice != 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  ₱{{orderItem.productprice}} each </span></div>
                  <div class="col-sm-2" v-if="orderItem.productprice == 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);"> Negotiable </span> </div>
                 <div class="col-sm-3" v-if="orderItem.producttotalprice != 0 && orderItem.productprice != 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  ₱{{orderItem.producttotalprice}} </span></div>
                  <div class="col-sm-3" v-if="orderItem.producttotalprice == 0 || orderItem.productprice == 0"> <span style="font-size: 17px; font-weight: bold; color: rgb(253, 126, 20);">  Negotiable </span></div>
             </div>
              </div>
          </div>
          <div class="card mt-2">
              <div class="card-header"> <i class="fas fa-info-circle"> </i> Order Information</div>
              <div class="card-body mt-2" v-for="(orderItem, index) in orderItems" v-if="index == orderItems.length -1">
             <div class="row p-1">
               <div class="col-3" style="font-size: 16px;"> About to be <span v-if="orderItem.paymentterm == 2" style="font-size: 16px;">  Delivered </span>  <span style="font-size: 16px;" v-if="orderItem.paymentterm == 1"> Picked up </span> on : </div>
               <div class="col-9">
                   <span style="font-size: 16px;color: #fd7e14;"> {{orderItem.datedeliver}} <span v-if="isAM == true"> AM </span> <span v-if="isAM == false"> PM </span></span>
               </div>
             </div>
             <div class="row p-1" v-if="orderItem.paymentterm != 1">
                 <div class="col-3" style="font-size: 16px;"> To be <span v-if="orderItem.paymentterm == 2" style="font-size: 16px;">  Delivered at : </span> </div>
                 <div class="col-9"> <span v-if="orderItem.customaddress != null" style="text-transform: capitalize;color: #fd7e14;"> {{orderItem.customaddress}} </span> <span style="text-transform: capitalize;color: #fd7e14;" v-if="orderItem.customaddress == null"> {{orderItem.defaultaddress}} </span></div>
             </div>
              <div class="row p-1 justify-content-center">
                  <div class="col-3"> <span style="font-size: 16px;"> Total Amount :  </span> </div>
               <div class="col-9">
                   <span style="font-size: 19px; color:#fd7e14; font-weight: bold; " v-if="orderTotalAmount != 0 && orderItem.productprice != 0"> ₱{{parseFloat(orderTotalAmount)}} </span>
                   <span style="font-size: 19px; color:#fd7e14; font-weight: bold; " v-if="orderTotalAmount == 0 || orderItem.productprice == 0"> Negotiable </span>
               </div>

             </div>
             <div class="row" v-if="orderItem.orderstatus == 1">
                 <div class="col-12"> <button disabled class="btn form-control" style="border-radius: 0px; background-color: #fd7e14; color: white; font-size: 16px;"> <i class="fas fa-clock"> </i> Pending </button> </div>
             </div>
             <div class="row mt-2" v-else>
                   <div class="col-12">
                     <button v-if="orderItem.orderstatus == 2 || orderItem.orderstatus == 3" type="button" class="btn form-control" disabled style="border-radius: 0px; background-color: rgb(113, 196, 74); color: white; font-size: 16px;"> <i class="fas fa-info-circle"> </i> {{orderItem.orderstatuslabel}}  </button>
                     <button v-else-if="orderItem.orderstatus != 3 || orderItem.orderstatus != 2" type="button" class="btn form-control" disabled style="border-radius: 0px; background-color:#f44242;color:white; font-size: 16px;"> <i class="fas fa-sad-tear"> </i> {{orderItem.orderstatuslabel}} with a concern of {{orderItem.ordercancelledstatus}} </button>
                   </div>
               </div>
         </div>
          </div>
      </div>
  </div>
</template>
<script>
import CustomerHeader from './CustomerHeader';
export default {
    components: {
        'myHeader' : CustomerHeader
    },
  data : function(){
      return{
        orderId : localStorage.getItem('customerSingleOrder'),
        orderDetails : [],
        orderItems: [],
        orderTotalAmount : 0,
          isAM : false,
      }
  },
  methods : {
      loadStoreDetail : function(){
       let data = {orderId : this.orderId};
        axios.post('api/orderStoreInfo',data).then(response =>{
        this.orderDetails = response.data;
        });
      },
      loadOrderedItems : function(){
       let data = {orderId : this.orderId};
         axios.post('api/getCustomerSingleOrderItems',data).then(response => {
             this.orderItems = response.data;

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
         });
      }
  },
  mounted : function(){
    this.loadStoreDetail();
    this.loadOrderedItems();
  }
}
</script>
<style scoped>
.card-header{
    background-color:rgb(65, 65, 66);
    color: white;
    font-size: 16px;
}
</style>
