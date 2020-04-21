    <template>
    <div>
         <myHeader propBrand="CONSTRUCTO"></myHeader>

         <Loading v-if="showLoading==true"></Loading>
         <div class="container">
            <div class="row" v-if="isSingleOrderSent == true">
                  <div class="col-12 text-center">
                        <p style="color: #479e48; font-size: 19px;"> <i class="fas fa-check-circle"> </i> Order Succesfully sent. </p>
                   </div>
            </div>
           <div class="row mt-3" >
               <div class="col">
                   <div class="card" style="border-bottom: 0px; border-left: 0px; border-right: 0px;">
                       <div class="card-header "> <i class="fas fa-shopping-cart"> </i> Shopping Cart  </div>
                       <div class="card-body">
                          <div class="card mt-2" v-for=" cart in carts" v-if="carts.length != 0">
                              <div class="card-header">
                                   <div class="row">
                                       <div class="col-6">
                                         <p style="text-transform:capitalize;margin-bottom:0px;"> {{cart.storename}} </p>
                                       </div>
                                       <div class="col-6">
                                           <p class="text-right" style="margin-bottom:0px;">{{cart.date_added}} </p>
                                       </div>
                                   </div>
                              </div>
                              <div class="card-body">
                                  <div class="row mt-2" v-for="cartItem in cartItems" v-if="cartItem.storeId == cart.storeId ">
                                      <div class="col-2">
                                          <img :src="'/uploads/productImages/'+cartItem.productimage" style="width: 90px; height: 90px;">
                                      </div>
                                      <div class="col-3">
                                         <span> {{cartItem.productname}} </span> <br>
                                         <b v-if="cartItem.productprice != 0"> ₱ {{cartItem.productprice}}</b>
                                         <b v-if="cartItem.productprice == 0"> Negotiable </b>
                                          <br>
                                       </div>
                                      <div class="col">
                                       <p style="font-weight: bold; color: #48a248;" v-if="cartItem.productprice != 0">  ₱ {{Number((cartItem.producttotalprice).toLocaleString(undefined,{maximumFractionDigits:2})).toFixed(2) }} </p>
                                       <p style="font-weight: bold; color: #48a248;" v-if="cartItem.productprice == 0">  Negotiable </p>
                                      </div>
                                      <div class="col">
                                          <button class="btn" type="button" style="border-radius: 0px;" :disabled="cartItem.productqty  <= 1" v-on:click="decrementQty(cartItem.productid,cartItem.productqty,cartItem.cartId)"> <i class="fas fa-minus"> </i> </button>
                                          {{cartItem.productqty}}
                                           <button class="btn" type="button" style="border-radius: 0px;" v-on:click="incrementQty(cartItem.productid,cartItem.productqty,cartItem.cartId)"><i class="fas fa-plus"> </i> </button>
                                      </div>
                                      <div class="col">
                                            <span style="color:#fd7e14;font-size:24px;" title="Remove product" v-on:click="removeProduct(cartItem.productid,cartItem.cartId)"> <i class="fas fa-trash"> </i> </span>
                                      </div>
                                  </div>
                              </div>
                               <div class="card-footer">
                           <div class="row">
                               <div class="col-12">
                                   <button class="btn form-control" style="border-radius: 0px;background-color: #fd7e14 !important;color: white;font-size: 16px;" v-on:click="launchCheckOutModal(cart.cartId)"> <i class="fas fa-paper-plane"> </i> Check Out </button>
                               </div>
                           </div>
                       </div>
                          </div>
                          <div class="row" v-if="carts.length == 0">
                              <div class="col-12">
                                  <p class="text-center" style="font-weight: 120; font-size: 17px;"> Cart Empty</p>
                              </div>
                          </div>
                       </div>

                   </div>
               </div>
           </div>
         </div>
<!-- Remove Product Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Do you wish to Remove this Product ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
           <div class="modal-footer justify-content-center">
         <button type="button" class="btn btn-danger" v-on:click="Logout()">Yes</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<!-- Checkout Product Modal -->
<div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
          <div class="card" v-for="checkOut in checkOuts" v-if="isCheckOutClicked == false && checkOuts.length != 0">
              <div class="card-header text-center" style="font-weight: bold; font-size: 16px; text-transform: capitalize; letter-spacing: 1px;"> {{checkOut.storename}} </div>
              <div class="card-body">
              <div class="row  justify-content-center">
                  <div class="col-6 text-center">
                      <b style="color: green;"> Subtotal </b>
                  </div>
                  <div class="col-6 text-center">
                      <b style="color: green;" v-if="checkOut.totalPrice !=0 "> ₱ {{checkOut.totalPrice}}</b>
                      <b style="color: green;" v-if="checkOut.totalPrice ==0 "> Negotiable </b>
                  </div>
              </div>
              <div class="row mt-3 justify-content-center">
                   <div class="col-6 text-center">
                      <b style="color: green; margin-top: 10px;"> Payment Option: </b>
                  </div>
                 <div class="col-6 text-center">
                     <select class="form-control" style="border-radius: 0px;" v-model="selectedPaymentOption">
                       <option v-if="checkOutPaymentTerm[0].isOffered == 1" v-bind:value="1"> Cash Upon Pickup </option>
                       <option v-if="checkOutPaymentTerm[1].isOffered == 1" v-bind:value="2"> Cash On Delivery </option>
                     </select>
                  </div>
              </div>
              <div class="row mt-3 border-top" v-if="selectedPaymentOption == 2">
                  <div class="col-12 text-justify mt-3">
                     <p><strong> NOTE : </strong> Be sure you are present during the arrival of the goods, or else you will be <strong> BANNED AUTOMATICALLY. </strong><br>
                       </p>
                  </div>
                  <div class="col-12">
                      <p>By clicking the <strong> Check Out </strong> button it's hereby concluded that you <strong> accepted this policy </strong> </p>
                  </div>
              </div>
              <hr>
              <div class="row mt-1" v-if="selectedPaymentOption == 2">
                  <div class="col-4">
                      <label v-if="selectedPaymentOption == 1" style="font-size: 16px;"> Your location to pick up: </label>
                      <label v-if="selectedPaymentOption == 2" style="font-size: 16px;"> To be Delivered at: </label>
                  </div>
                  <div class="col-8">
                      <div class="form-group">
                              <input type="checkbox" class="form-check" style="float: left; margin-top: 3px; background-color: #fd7e14; color: white;" v-model="isUseDefaultAddress"> <label>  Use Default Address</label>
                      <input type="text" class="form-control" style="border-radius: 0px;"  autocomplete="off" ref="customaddress" id="addressToDeliver" v-if="!isUseDefaultAddress" v-model="addressDeliver">
                      </div>
                  </div>
              </div>
              <div class="row mt-1">
                  <div class="col-4">
                       <label v-if="selectedPaymentOption == 1" style="font-size: 16px;"> To be Pickup on: </label>
                      <label v-if="selectedPaymentOption == 2" style="font-size: 16px;"> To be Delivered on: </label>
                  </div>
                  <div class="col-8">
                      <div class="form-group">
                         <div class="input-group date">
                           <datetime  type="datetime" zone="local" use12-hour value-zone="UTC+8"  v-model="dateToBeDeliver"> </datetime>
                         </div>
                      </div>
                  </div>
                  <div class="row" v-if="showErrorMsgOfInvalidDate == false">
                       <div class="col-12"> <p style="font-size: 14px;color: red;"> <strong> Note :</strong> Please select a date before the date today.  </p> </div>
                  </div>
              </div>
              <hr>
              <div class="row mt-3">
                  <div class="col-6">

                   <button type="button" style="border-radius: 0px; background-color: #fd7e14; color: white; cursor: no-drop;" class="btn form-control" disabled v-on:click="checkOutFunction(checkOut.cartId);" v-if="isCheckOutFormComplete == false"> <i class="fas fa-clipboard-list"> </i> Check Out</button>
                  <button type="button" style="border-radius: 0px; background-color: #fd7e14; color: white;" class="btn form-control" :disabled="showErrorMsgOfInvalidDate == false" v-on:click="checkOutFunction(checkOut.cartId);" v-if="isCheckOutFormComplete == true"> <i class="fas fa-clipboard-list"> </i> Check Out</button>
                  </div>
                  <div class="col-6">
                       <button type="button" style="border-radius: 0px; background-color: #fd7e14; color: white;"  class="btn form-control" data-dismiss="modal"><i class="fas fa-times-circle"> </i> Cancel</button>
                  </div>
              </div>
          </div>
          </div>
          <div class="card" v-else-if="isCheckOutClicked == true">
              <div class="card-body"> <p class="text-center"> Sending your order, Please wait a moment.... </p> </div>
          </div>
          <div class="card" v-else-if="checkOuts.length == 0">
              <div class="card-body">
                  <p class="text-center"> Cart is Empty, Try adding one It's Always Free </p>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- Order Send Message -->
<div class="modal fade" id="ordersentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center"> Order Sent </p>
        <div class="row">
            <div class="col-12">
                 <button class="btn btn-success form-control" style="border-radius: 0px;" data-dismiss="modal"> Got It </button>
            </div>
        </div>
      </div>

    </div>
  </div>
</div>
<div id="MessageButtonContainer" v-if="isMessageClicked == false">
         <button class="btn" style="border-radius: 0px; background-color: #fd7e14; color:white;" @click="viewMessageThreads()">
         <span style="font-size:20px;"> <i class="fas fa-envelope"></i> Messages <span class="badge badge-danger" v-if="unSeenMsg != 0"> {{unSeenMsg}} </span>  </span>
         </button>
</div>
<div id="MessageThreadContainer" v-if="isMessageClicked == true">
         <div class="card w-100">
            <div class="card-header">
               <div class="row">
                  <div class="col-9">
                     <i class="fas fa-envelope"></i> Messages
                  </div>
                  <div class="col-3 justify-content-end">
                     <button type="button" @click="isMessageClicked=false;" class="close" aria-label="Close">
                     <span aria-hidden="true" style="color:white;">&times;</span>
                     </button>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-5 border-right">
                     <div class="card">
                        <div class="card-header">
                           <i class="fas fa-store"></i> Stores
                        </div>
                        <div class="card-body" style="overflow-y:scroll; height: 400px; padding : 5px 5px 5px 5px;">
                           <ul class="list-group" v-if="messageHeads.length != 0">
                              <li class="list-group-item mt-2" v-for="messageHead in messageHeads" style="padding : 5px 5px 5px 15px;cursor:pointer;" @click="getMessages(messageHead.msgId,messageHead.storeId)">
                                 <img style="height:25px; width: 25px; border-radius:40px;" :src="'/uploads/productImages/'+messageHead.profilepic">
                                 <span style="text-transform: capitalize; font-size:12px;">
                                      <span v-if="messageHead.isSeen == 1 || localisSeen == 1"> {{messageHead.firstname}} {{messageHead.lastname}} </span>
                                    <strong v-else> {{messageHead.firstname}} {{messageHead.lastname}} </strong>
                                 </span>
                              </li>
                           </ul>
                           <ul class="list-group" v-if="messageHeads.length == 0">
                              <li class="list-group-item">No messages.</li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-7" v-if="messageHeads.length != 0">
                     <div class="card">
                        <div class="card-header">
                           <i class="fas fa-comments"></i> Conversation
                        </div>
                        <div class="card-body" style="overflow-y:scroll; height: 400px;">
                           <ul class="list-group" v-for="message in messages">
                              <div class="list-group-item mt-2 text-right" style="background-color:#fd7e14; color:white; padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 0">
                                 <span>{{message.message}}</span>
                              </div>
                              <div class="list-group-item mt-2 text-left" style="padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 1">
                                 <span>{{message.message}}</span>
                              </div>
                           </ul>
                        </div>
                        <div class="row p-2" v-if="isConvoClick == true">
                           <div class="col-sm-9">
                              <textarea  v-model="messageBody" style="resize:none; border-radius: 0px;"  class="form-control" placeholder="Type a message..."></textarea>
                           </div>
                           <div class="col-sm-3">
                              <span style="color:#fd7e14; font-weight:bold;cursor:pointer;" @click="sendReplyToStore()"> <i class="fas fa-paper-plane"></i> Send </span>
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
import CustomerHeader from './CustomerHeader';
import Loading from './Loading';

export default {
 components: {
     'myHeader' : CustomerHeader,
     'Loading' : Loading
 },
 data: function(){
     return{
        customerId : localStorage.getItem('id'),
        carts : [],
        cartItems : [],
        showLoading : false,
        cartpaymentterm : 0,
        checkOuts : [],
        checkOutPaymentTerm : [],
        selectedPaymentOption : 1,
        isCheckOutClicked : false,
        isPolicyAccepted : false,
        isUseDefaultAddress : true,
        dateToBeDeliver : new Date(),
        dateDeliver : '',
        addressDeliver: '',
        isCheckOutFormComplete : false,
        messageHeads: [],
        messages: [],
        msgId: 0,
        messageBody: "",
        isConvoClick: false,
        messageStoreId: 0,
        isMessageClicked: false,
        showErrorMsgOfInvalidDate: true,
        unSeenMsg : 0,
        localisSeen : 0,
        isSingleOrderSent: false
    }
 },
 watch : {
     dateToBeDeliver: function(time){
      let myDate = new Date(time);
        let hours = myDate.getUTCHours() -4;
          if(hours <= 0){
             let temphours = Math.abs(hours);
              hours = 24 - temphours;
         }
         let minutes = myDate.getUTCMinutes();
         let month = myDate.getUTCMonth() + 1;
        this.dateDeliver = myDate.getUTCFullYear()+"-"+month+"-"+myDate.getUTCDate()+" "+hours+":"+myDate.getUTCMinutes();

     },
     isUseDefaultAddress : function(val){
       //    alert("checkbox is cheked "+val)
        if(this.dateDeliver === "NaN-NaN-NaN NaN:NaN" || (this.addressDeliver === '' && this.isUseDefaultAddress == false)){
              this.isCheckOutFormComplete = false;
            //  alert('1 true')
         }else{
            //  alert('1 false')
             this.isCheckOutFormComplete = true;
         }
     },
     dateDeliver: function(val){


        //  alert("date deliver"+ this.addressDeliver);
         if(this.dateDeliver === "NaN-NaN-NaN NaN:NaN" || (this.addressDeliver === '' && this.isUseDefaultAddress == false)){
              this.isCheckOutFormComplete = false;
            //  alert('1 true')
            this.compare_dates(val);

         }else{
            //  alert('1 false')
             this.isCheckOutFormComplete = true;
             this.compare_dates(val);

         }
     },
     addressDeliver : function(val){
        //  alert("date delver = "+this.dateDeliver+" address delvier ="+this.addressDeliver);
       if(this.dateDeliver === "NaN-NaN-NaN NaN:NaN" || (this.addressDeliver === '' && this.isUseDefaultAddress == false)){
           this.isCheckOutFormComplete = false;

            //  alert('2 true')
         }else{
            //  alert('2 false')
             this.isCheckOutFormComplete = true;
         }
     }

 },
 methods: {
  customerUnseenMsg : function(){
       let data = {customerId : this.customerId};
        axios.post('api/customerUnseenMsg',data).then(resp => {
          this.unSeenMsg = resp.data.length;
          if(this.unSeenMsg != 0){
              this.localisSeen = 0;
          }
        });
  },
  checkCustomerValidity: function(){
            let data = {customerId : localStorage.getItem('id')};
           axios.post('api/checkCustomerValidity',data).then(response=>{
              if(response.data[0].length != 0){
                 this.$router.push('/MyAccount');
              }
           });
  },
  getAllCartStore: function(){
   let obj=  {customerId : this.customerId}
   axios.post('api/getAllCartStore',obj).then(response => {
     this.carts = response.data;
   });
  },
  getAllCartItems: function(){
     let obj=  {customerId : this.customerId}
      axios.post('api/getAllCartItems',obj).then(response => {
       this.cartItems = response.data;
      });
  },
  incrementQty : function(prodId,prodQty,cartId){
      this.showLoading = true;
    let data = {isIncrement : 1, productId : prodId, productQty : prodQty, cartId : cartId};
    axios.post('api/updateCartProductQty',data).then(response=>{
       setTimeout(()=>{
           this.showLoading = false;
       this.getAllCartStore();
       this.getAllCartItems();
       },500);
    }, (error)=>{
         setTimeout(()=>{
           this.showLoading = false;
       this.getAllCartStore();
       this.getAllCartItems();
       },500);
    });
  },
  decrementQty : function(prodId,prodQty,cartId){
      this.showLoading = true;
    let data = {isIncrement : 0, productId : prodId, productQty : prodQty, cartId : cartId};
     axios.post('api/updateCartProductQty',data).then(response=>{
       setTimeout(()=>{
           this.showLoading = false;
       this.getAllCartStore();
       this.getAllCartItems();
       },500);
    }, (error)=>{
        setTimeout(()=>{
           this.showLoading = false;
       this.getAllCartStore();
       this.getAllCartItems();
       },500);
    });
 },
 compare_dates : function(selectedDate){
           var date_str = new Date();
           var date_str_minutes = date_str.getUTCMinutes();
           var date_str_month = date_str.getUTCMonth() + 1;
           var date_str_day = date_str.getUTCDate();
           var date_str_hours = date_str.getUTCHours() - 4;
           var date_str_year = date_str.getUTCFullYear();
              if (date_str_hours <= 0 ){
                 let temp_hours = Math.abs(date_str_hours);
                 date_str_hours = 24 - temp_hours;
              }

            var date_now = new Date(date_str);
            var date_param = new Date(selectedDate);

               if(date_now >= date_param){
                 this.showErrorMsgOfInvalidDate = false;
               }else{
                   this.showErrorMsgOfInvalidDate = true;
               }




        },
 removeProduct : function(prodId,cartId){
     this.showLoading = true;
  let data = {prodId :prodId ,cartId : cartId};
    axios.post('api/removeCartProduct',data).then(response=>{
        setTimeout(()=>{
           this.showLoading = false;
           this.getAllCartStore();
           this.getAllCartItems();
        },1500);
    }, (error)=>{
         setTimeout(()=>{
           this.showLoading = false;
           this.getAllCartStore();
           this.getAllCartItems();
        },1500);
    });
 },
 launchCheckOutModal: function(cartId){
     this.showLoading = true;
    this.isCheckOutClicked = false
     setTimeout(()=>{
       this.showLoading = false;
       $('#checkOutModal').modal('show');
        let data = {cartId : cartId};
          axios.post('api/viewCheckOutCart',data).then(response=>{
             this.checkOuts = response.data;
             this.getPaymentTermForCheckOut(this.checkOuts[0].storeId);
          });
     },200);
 },
 getPaymentTermForCheckOut : function(storeId){
   let data = {storeId : storeId};
    axios.post('api/getPaymentTermForCheckOut',data).then(response=>{
       this.checkOutPaymentTerm = response.data;
       if(this.checkOutPaymentTerm[0].isOffered == 1 && this.checkOutPaymentTerm[0].isOffered == 1 ){
             this.selectedPaymentOption = 1;
       }else if(this.checkOutPaymentTerm[0].isOffered == 0 && this.checkOutPaymentTerm[0].isOffered == 1 ){
             this.selectedPaymentOption = 1;
       }else{
             this.selectedPaymentOption = 2;
       }
       console.log(response.data);
    });
 },
 checkOutFunction: function(cartId){
     this.isCheckOutClicked = true;
   let data = {cartId : cartId, paymentOption : this.selectedPaymentOption, deliverDate : this.dateDeliver, deliverAddress : this.addressDeliver};
    axios.post('api/cartCheckOut',data).then(response=>{
       $('#checkOutModal').modal('hide');
       this.showLoading = true;

       setTimeout(()=>{
          this.showLoading = false;
          this.getAllCartStore();
          this.getAllCartItems();
          this.isSingleOrderSent  = true;
           setTimeout(()=>{
               this.isSingleOrderSent = false;
           },2000);
       },800);
    }, error =>{
       $('#checkOutModal').modal('hide');
        this.showLoading = true;

          setTimeout(()=>{
          this.showLoading = false;
          this.getAllCartStore();
          this.getAllCartItems();
           this.isSingleOrderSent  = true;
           setTimeout(()=>{
               this.isSingleOrderSent = false;
           },2000);
        },800);
    })
 },
 viewMessageThreads: function() {
      this.isMessageClicked = true;
      this.getMessageHeads();
 },
 getMessageHeads: function() {
      let data = { customerId: this.customerId };
      axios.post("api/customerGetMessageHeads", data).then(response => {
        this.messageHeads = response.data;

        setTimeout( ()=>{
            this.customerUnseenMsg();
         }, 2000);
      });
 },
 getMessages: function(msgId, storeId) {
      this.isConvoClick = true;
      let data = { msgId: msgId, message: this.messageBody };
      this.msgId = msgId;
      this.messageStoreId = storeId;
      axios.post("api/CustomerViewConvo", data).then(response => {
        this.messages = response.data;
        this.getMessageHeads();
         setTimeout( ()=>{
            this.customerUnseenMsg();
         }, 2000);
      });
 },
 sendReplyToStore: function() {
      let data = {
        msgId: this.msgId,
        message: this.messageBody,
        storeId: this.messageStoreId
      };
      axios.post("api/sendReplyToStore", data).then(response => {
        this.messageBody = "";
        this.viewUpdatedConvo();
      });
 },
 viewUpdatedConvo : function(){
        let data = {msgId : this.msgId, isSeen: 1};
           axios.post('api/CustomerViewConvo',data).then(resp => {
             this.messages = resp.data;
           });
           setTimeout(()=>{
              this.getMessageHeads();
              this.customerUnseenMsg();
           });
 },
 checkPaymentTerms : function(){

 },
 listenToCustomerMessage: function(){
        Echo.channel('listenToStoreChannel').listen('ListenToStoreMsg', message=>{
             console.log(message);
               if(message.customerId == this.customerId){
                     this.viewUpdatedConvo();
          }
       });
    }

 },
 mounted : function(){
     var cityBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(7.055664, 125.55957),
      new google.maps.LatLng(7.055664, 125.55957)
    );
   console.log(this.address);
    this.customaddress = new google.maps.places.Autocomplete(this.$refs.customaddress, {
      types: ["geocode"],
      componentRestrictions: { country: "ph" },
      bounds: cityBounds
    });

 this.checkCustomerValidity();
 this.listenToCustomerMessage();
   setTimeout ( ()=>{
     this.customerUnseenMsg();
       setTimeout( ()=>{
         this.getAllCartStore();
         this.getAllCartItems();
       },1000);
   },1000);
 }
}
</script>
<style scoped>
.card-header{
    background-color:rgb(65, 65, 66);
    color: white;
    font-size: 16px;
}
.vdatetime-popup__header{
    background-color: rgb(253, 126, 20);
}
#MessageButtonContainer {
  position: fixed;
  bottom: -4px;
  right: 10px;
  z-index: 100;
}
#MessageThreadContainer {
  position: fixed;
  bottom: 10px;
  right: 20px;
  width: 600px;
  z-index: 100;
}
</style>

