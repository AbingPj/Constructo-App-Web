<template>
<div>
    <myheader> </myheader>
    <Loading v-if="showLoading==true"></Loading>
 <div class="container">
     <div class="row" v-if="isSingleOrderSent == true">
         <div class="col-12 text-center">
            <p style="color: #479e48; font-size: 19px;"> <i class="fas fa-check-circle"> </i> Order Succesfully sent. </p>
         </div>
     </div>
       <div class="row mt-3">
     <div class="col">
      <div class="card">
          <div class="card-header" style="color:white; background-color: rgb(65, 65, 66);font-size:16px"><i class="fas fa-box"> </i> Prouct Information </div>
          <div class="card-body" v-for="singleProduct in profile">
              <div class="row">
                <div class="col-sm-4">
                   <img :src="'/uploads/productImages/'+singleProduct.image" style="width: 189px;height: 267px;">
                </div>
                <div class="col">
                    <h3 style="text-transform: capitalize;"> {{singleProduct.name+"  "+singleProduct.description}} </h3>
                    <p style="margin-bottom: 0px !important;">{{singleProduct.category}} </p>
                    <p v-if="singleProduct.status == 1" style="color: green;  font-size: 19px; font-weight: bold;"> In Stock </p> <p v-else style="color: red; font-size: 19px; font-weight: bold;"> Out Of Stock </p>
                      <div class="row border-top">
                          <div class="col">
                                   <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price != 0"> ₱{{singleProduct.price}}</p>
                                    <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price == 0"> Negotiable </p>
                            <div class="row mt-3">
                                <div class="col-2">
                                     <p style="color: gray;margin-top: 9% !important;"> Quantity</p>
                                </div>
                                    <button class="btn" style="background-color:#fd7e14;border-radius:0px;" :disabled="prodQty == 1" v-on:click="prodQty -= 1;"> <i class="fas fa-minus"> </i> </button>
                                    <div style="margin-top: 2% !important; margin-left: 3%;margin-right: 3%;">
                                           <span> {{prodQty}} </span>
                                    </div>
                                    <button class="btn" style="background-color:#fd7e14;border-radius:0px;" :disabled="prodQty == 1000" v-on:click="prodQty += 1;"><i class="fas fa-plus"> </i> </button>

                            </div>
                            <div class="row mt-4">
                                <div class="col-sm">
                                    <button class="btn form-control" style="border-radius: 0px; background-color: #fd7e14 !important;color: white;" v-on:click="checkOutModal(profile.storeId);" :disabled="singleProduct.status != 1"><i class="fas fa-clipboard-list"> </i> Order Now </button>
                                </div>
                                <div class="col-sm">
                                    <button class="btn form-control" style="border-radius: 0px;background-color: #fd7e14 !important;color: white;" v-on:click="addToCart();" :disabled="singleProduct.status != 1"> <i class="fas fa-cart-plus"> </i> Add To Cart </button>
                                </div>
                                <div class="col-sm">
                                         <button class="btn form-control" style="border-radius: 0px;background-color: #fd7e14 !important;color: white;" data-target="#messageModal" data-toggle="modal"> <i class="fas fa-comment"> </i> Chat Now </button>
                                </div>
                            </div>
                          </div>

                      </div>
                </div>
             </div>

          </div>
      </div>
      <div class="card mt-3">
          <div class="card-header"><i class="fas fa-store"> </i> Store Information </div>
          <div class="card-body" v-for="singleProduct in profile">
              <div class="row">
                  <div class="col-3" style="text-transform:capitalize;"><i class="fas fa-store"> </i> {{singleProduct.storename}} </div>
                  <div class="col"><i class="fas fa-map-marker"> </i> {{singleProduct.location}} </div>
                  <div class="col-3"><i class="fas fa-mail-bulk"> </i> {{singleProduct.storeemail}} </div>
              </div>

          </div>
      </div>
      <!-- More products from this store -->
      <div class="card mt-3">
          <div class="card-header"><i class="fas fa-boxes"> </i> More Products </div>
          <div class="card-body" style="background-color: #f5f5f5;">
              <div class="row">
                  <div class="col-sm-2 mlt-3 ml-3" v-for="moreProduct in moreProducts" style="background-color: white;" v-if="moreProduct.id != searchedProductId">
                      <img :src="'/uploads/productImages/'+moreProduct.image" style="width: 100%; height: 120px;">
                      <div class="row mt-2">
                          <div class="col-sm-12"> <span style="font-size:16px;"> {{moreProduct.name}} </span> </div>
                          <div class="col-sm-12"> <span style="font-size:13px;" v-if="moreProduct.description.length <= 30"> {{ moreProduct.description}} </span> </div>
                          <div class="col-sm-12"> <span style="font-size:13px;" v-if="moreProduct.description.length >= 31"> {{ moreProduct.description.slice(0,30) }}... </span> </div>
                          <div class="col-sm-12"> <span style="font-sieze: 21px; font-weight: bold; color: #fd7e14;"> ₱{{moreProduct.price}} </span></div>
                      </div>
                  </div>
              </div>
             <button class="btn" style="background-color:#fd7e14; color: white; margin-top: 10px;border-radius:0px;"> <i class="fas fa-arrow-alt-circle-right"> </i> More Product </button>
            </div>
      </div>
     </div>
  </div>
  <!-- <div class="row mt-3">
      <div class="col">
          <div class="card">
              <div class="card-header">More From This Store</div>
              <div class="card-body">
                  <div class="row ml-3">
                      <div class="form-group">
                          <input type="text" class="form-control" style="border-radius: 0px!important;" placeholder="Enter Keyword Here">
                      </div>
                       <div class="form-group">
                          <button class="btn" style="border-radius: 0px!important;background-color: #3b71c6 !important;color: white;">Search </button>
                      </div>
                  </div>
                  <div class="row mt-2 ml-3" v-for="moreProduct in moreProducts">
                      <div class="card  w-75">
                          <div class="card-body">
                              <div class="row">
                                <div class="col-5">
                                    <img :src="'/uploads/productImages/'+moreProduct.image" style="height: 90px; width: 90px;">
                                </div>
                                <div class="col-7">
                                     <h3 class="h3"> {{moreProduct.name}} </h3>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div> -->
 </div>
  <!-- Add to cart modal-->
 <div class="modal fade" id="addCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">  Cart </h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   </div>
                   <div class="modal-body " v-for="singleProduct in profile">
          <div class="row justify-content-center">
              <div class="col-8">
                  <div class="row">
                       <div class="col-4">
                          <img :src="'/uploads/productImages/'+singleProduct.image" style="height: 70px; width: 70px; float-right;">
                       </div>
                       <div class="col">
                          <h3 style="font-size: 15px;font-weight: bold;"> {{singleProduct.name+"  "+singleProduct.description}} </h3>
                           <p style="margin-bottom: 0px !important;">{{singleProduct.category}} </p>
                         <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price != 0"> ₱{{singleProduct.price.toLocaleString(undefined, {maximumFractionDigits:2}) }}</p>
                         <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price == 0"> Negotiable </p>
                       </div>
                  </div>
          </div>
          </div>
          <div class="row">
              <div class="col">
              <div class="card">
                  <div class="card-header text-center">Add To Cart</div>
                  <div class="card-body">
                      <div class="row justify-content-center">
                          <div class="col-7">
                              <div class="row justify-content-center">
                                  <div class="col">
                                     <p> Quantity:</p>
                                  </div>
                                  <div class="col-7">
                                     <p>{{prodQty}}</p>
                                  </div>
                              </div>
                              <div class="row justify-content-center">
                                  <div class="col">
                                     <p> Extended:</p>
                                  </div>
                                  <div class="col-7">
                                     <p v-if="singleProduct.price != 0">₱ {{Number((prodQty * singleProduct.price)).toFixed(2) }}</p>
                                     <p v-if="singleProduct.price == 0"> Negotiable </p>
                                  </div>
                              </div>
                              <div class="row justify-content-center">
                                 <div class="col">
                                     <p style="font-weight: bold; color: #48a248;"> Subtotal:</p>
                                 </div>
                                 <div class="col-7">
                                     <p style="font-weight: bold; color: #48a248;" v-if="singleProduct.price != 0">₱ {{Number((prodQty * singleProduct.price)  ).toFixed(2) }}</p>
                                     <p style="font-weight: bold; color: #48a248;" v-if="singleProduct.price == 0"> Negotiable </p>
                                 </div>
                             </div>

                          </div>
                      </div>
                      <div class="row form-group">
                                  <div class="col-12">
                                       <button type="button" class="btn form-control" style="border-radius: 0px!important;background-color: #fd7e14 !important;color: white;" v-on:click="saveCart();" :disabled="addToCartChecker"><i class="fas fa-plus-circle"> </i> Add To Cart</button>
                                  </div>
                                </div>
                  </div>
              </div>
          </div>
                    </div>
          </div>
               </div>
            </div>
       </div>
 <!-- Check out modal-->
 <div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered w-50" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order this item ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body " v-for="singleProduct in profile">
          <div class="row justify-content-center">
              <div class="col-8">
                  <div class="row">
                       <div class="col-4">
                          <img :src="'/uploads/productImages/'+singleProduct.image" style="height: 70px; width: 70px; float-right;">
                       </div>
                       <div class="col">
                          <h3 style="font-size: 15px;font-weight: bold;"> {{singleProduct.name+"  "+singleProduct.description}} </h3>
                           <p style="margin-bottom: 0px !important;">{{singleProduct.category}} </p>
                           <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price != 0"> ₱{{singleProduct.price}}</p>
                           <p style="color: #48a248; font-size: 22px; font-weight: bold;margin-top: 2%;" v-if="singleProduct.price == 0"> Negotiable     </p>
                       </div>
                  </div>
          </div>
          </div>
          <div class="row">
              <div class="col">
              <div class="card">
                  <div class="card-header text-left">Check Out</div>
                  <div class="card-body">
                      <div class="row" v-if="selectedPaymentTerm == 2" style="padding: 20px;">
                             <p><strong> NOTE : </strong> Be sure you are present during the arrival of the goods, or else you will be <strong> BANNED AUTOMATICALLY. </strong><br> By clicking the <strong> Check Out </strong> button it's hereby concluded that you <strong> accepted this policy </strong> </p>
                      </div>
                      <div class="row" v-if="paymentTerms[1].note != '' && selectedPaymentTerm == 2">

                          <p style="font-size: 17px;"> <strong> DELVIERY NOTE: </strong> {{paymentTerms[1].note}} </p>
                      </div>
                      <div class="row">
                          <div class="col-7">
                              <div class="row">
                                  <div class="col">
                                     <p> Quantity:</p>
                                  </div>
                                  <div class="col-7">
                                     <p>{{prodQty}}</p>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col">
                                     <p> Extended:</p>
                                  </div>
                                  <div class="col-7">
                                     <p v-if="singleProduct.price != 0">₱ {{ Number(prodQty * singleProduct.price).toFixed(2) }}</p>
                                     <p v-if="singleProduct.price == 0"> Negotialbe </p>
                                  </div>
                              </div>
                              <div class="row">
                                 <div class="col">
                                     <p style="font-weight: bold; color: #48a248;"> Subtotal:</p>
                                 </div>
                                 <div class="col-7">
                                      <p style="font-weight: bold; color: #48a248;" v-if="singleProduct.price != 0">₱ {{Number(prodQty * singleProduct.price).toFixed(2) }}</p>
                                      <p style="font-weight: bold; color: #48a248;" v-if="singleProduct.price == 0"> Negotiable </p>
                                 </div>
                             </div>
                          </div>
                          <div class="col border-left">
                              <div class="form-group">
                              <select class="form-control" v-model="selectedPaymentTerm" style="border-radius: 0px; font-size: 11px;">
                                  <option v-if="paymentTerms[0].isOffered== 1" :value="1"> Cash Upon Pick-Up </option>
                                  <option  v-if="paymentTerms[1].isOffered== 1" :value="2"> Cash On Delivery </option>
                              </select>
                              </div>


                           </div>
                      </div>
                      <hr>
                      <div class="row mt-3">
                          <div class="col-12">
                            <div class="form-group">
                               <div class="input-group date">
                                   <label class="col-6"> Date to be <span v-if="selectedPaymentTerm == 1"> Picked Up </span > <span v-if="selectedPaymentTerm == 2"> Delivered </span> </label>
                                 <datetime :disabled="!selectedPaymentTerm"  type="datetime" zone="local" use12-hour value-zone="UTC+8"  v-model="dateToBeDeliver"> </datetime>
                               </div>
                            </div>
                            <div class="row" v-if="showErrorMsgOfInvalidDate == false">
                                <div class="col-12"> <p style="font-size: 14px;color: red;"> <strong> Note :</strong> Please select a date before the date today.  </p> </div>
                            </div>
                          </div>
                          <div class="col-12">
                                <div class="form-group" v-if="selectedPaymentTerm == 2">
                                  <input type="checkbox" class="form-check" style="float: left; margin-top: 3px; background-color: #fd7e14; color: white;" v-model="isUseDefaultAddress"> <label>  Use Default Address </label>
                                  <input type="text" class="form-control" placeholder="Enter custom deliver location" style="border-radius: 0px;"  autocomplete="off" ref="customaddress" id="addressToDeliver" v-if="!isUseDefaultAddress" v-model="addressDeliver">
                              </div>
                          </div>
                          <div class="col-12">
                              <div class="form-group">
                                   <button v-if="showErrorMsgOfInvalidDate == false" type="button" class="btn form-control" style="border-radius: 0px!important;background-color: #3b71c6 !important;color: white;" disabled> Send Order </button>
                                   <button  v-if="showErrorMsgOfInvalidDate == true" type="button" class="btn form-control" style="border-radius: 0px!important;background-color: #3b71c6 !important;color: white;" @click="sendSingleOrder()" :disabled="!dateToBeDeliver"> Send Order</button>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>
          </div>
                    </div>
          </div>

      </div>
    </div>
   </div>
    <!-- SendMessage Modal -->
        <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                   <div class="modal-header">
                       <p class="modal-title" id="exampleModalLabel"><i class="fas fa-pen"> </i> Compose message </p>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   </div>
                   <div class="modal-body">
                       <div class="card">
                           <div class="card-body" v-for="single in profile">
                               <div class="row">
                                   <div class="col-sm-12">
                                         To: <span style="margin-left: 10px;">   {{single.storename}}</span>
                                   </div>
                                   <div class="col-sm-12 mt-2">
                                        <textarea class="form-control" style="border-radius:0px;" placeholder="Message" v-model="message.body"> </textarea>
                                   </div>
                                   <div class="col-sm-12 mt-2">
                                         <button class="form-control btn" style="background-color:#fd7e14;color:white;border-radius:0px;" @click="sendMessageToStore(single.storeId)"> <i class="fas fa-share"> </i> Send Message </button>
                                   </div>
                               </div>
                          </div>
                       </div>
                   </div>
               </div>
            </div>
       </div>
         <!-- Sended-->
        <div class="modal fade" id="sentMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                       <div class="row">
                           <div class="col-12 text-center">
                               <i class="fas fa-smile"> </i> Message Succesfully Sent
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
    components : {
        'myheader' : CustomerHeader,
        'Loading' : Loading
    },
    data : function(){
        return{
           profile : [],
           searchedProductId : localStorage.getItem('customerSearchedProduct'),
           prodQty : 1,
           moreProducts : [],
           extended: 0,
           addToCartChecker : false,
           showLoading : false,
           paymentTerms: {
               0 : {
                   isOffered : 0,
                   note: ''
                   },
               1 : {
                   isOffered : 0,
                   note: ''
                   }
           },
           selectedPaymentTerm : 0,
           isSingleOrderSent : false,
           customerId : localStorage.getItem('id'),
           message: {
               to : '',
               body: '',
               from : ''
           },
           isUseDefaultAddress : true,
           dateToBeDeliver : new Date(),
           addressDeliver:  '',
           dateDeliver : '',
           showErrorMsgOfInvalidDate : true,
           ErrorMsgOfInvalidDate : 'Please select a date before the date today',
           singleOrderisSent : false,
        }
    },
    watch : {
       dateToBeDeliver : function(time){
             let myDate = new Date(time);
        let hours = myDate.getHours();
        // console.log(myDate);
        // console.log(new Date());
        console.log(hours);
          if(hours <= 0){
             let temphours = Math.abs(hours);
              hours = 24 - temphours;
         }
         let minutes = myDate.getUTCMinutes();
         let month = myDate.getUTCMonth() + 1;
         this.compare_dates(time);
           if(this.showErrorMsgOfInvalidDate == true){
               this.dateDeliver = myDate.getUTCFullYear()+"-"+month+"-"+myDate.getUTCDate()+" "+hours+":"+myDate.getUTCMinutes();
           }else{
               this.dateDeliver = null;
           }
           console.log(this.dateDeliver);

       }
    },
    methods :{
        sendMessageToStore : function(to){
            this.showLoading = true;
            this.message.from = this.customerId;
            $('#messageModal').modal('hide');
            let data = {customerId : this.customerId, message : this.message.body, storeId : to }
            axios.post('api/sendMessageToStore',data).then(response=>{
              console.log(response.data);
              this.showLoading = false;
              $('#sentMessageModal').modal('show');
            });

        },
        sendSingleOrder :function(){
            this.isSingleOrderSent = true;
            let data = {
             customerId : localStorage.getItem('id'),
             storeId : this.profile[0].storeId,
             paymentOption : this.selectedPaymentTerm,
             prodId : this.profile[0].productid,
             prodQty : this.prodQty,
             dateTimeDeliver : this.dateDeliver,
             deliverAddresss : this.addressDeliver
             };

             axios.post('api/sendSingleOrder',data).then( response=>{
                $('#checkOutModal').modal('hide');
                   setTimeout( ()=>{
                 this.isSingleOrderSent = false;
             },2000);
             },(error)=>{
                 $('#checkOutModal').modal('hide');
                    setTimeout( ()=>{
                 this.isSingleOrderSent = false;
             },2000);
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
      customerfetchSingleProduct : function(){
        let obj = {id : localStorage.getItem('customerSearchedProduct')};
        axios.post('api/customerfetchSingleProduct',obj).then(response =>{
          this.profile = response.data;
          localStorage.setItem('SearchedStoreId',response.data[0].storeId);
          this.getPaymentTermsForCheckOut(response.data[0].storeId);
        });
      },
      refreshAvailability: function(){
             Echo.channel('productAvailabilityChannel').listen('ProductAvailabilityEvent', message =>{
                this.customerfetchSingleProduct();
            });
      },
      getMoreProducts : function(){
       let obj = {storeId : localStorage.getItem('SearchedStoreId')};
         axios.post('api/getMoreProducts',obj).then(response=>{
         this.moreProducts = response.data;
         });
      },
      checkOutModal: function(storeId){

       $('#checkOutModal').modal('show');

      },
      addToCart : function(){
          $('#addCartModal').modal('show');
      },
      saveCart : function(){
          this.addToCartChecker = true;
          this.showLoading = true;
        let data = {
            customerId : localStorage.getItem('id'),
            storeId : this.profile[0].storeId,
            prodId  : this.profile[0].productid,
            qty : this.prodQty
        }
        axios.post('api/saveToCart',data).then(response=>{
            this.prodQty =1;
             $('#addCartModal').modal('hide');
             setTimeout(()=>{
                   this.showLoading = false;
             },2000);

             this.$router.push({name: 'CustomerCart'});
        }, error=>{
            this.prodQty =1;
             $('#addCartModal').modal('hide');
             setTimeout(()=>{
                   this.showLoading = false;
             },2000);

             this.$router.push({name: 'CustomerCart'});
        });
      },
      getPaymentTermsForCheckOut: function(storeId){
          let data = {storeId : storeId};
          axios.post('api/getPaymentTermForCheckOut',data).then(response=>{
            this.paymentTerms = response.data;
            console.log(this.paymentTerms);
          });
      }
    },
    mounted : function(){
      this.customerfetchSingleProduct();
      this.refreshAvailability();
      this.getMoreProducts();

    }

}
</script>
<style>
.card-header{
    background-color:  rgb(65, 65, 66);
    color : white;
    font-size: 16px;
}
</style>

