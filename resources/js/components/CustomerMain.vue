<template>
   <div style="margin-left : 1%;">
      <loading v-if="showLoading == true"></loading>
      <div class="row">
         <div class="col-12" style="margin : auto;">
            <div class="form-group" v-if="isSearchTriggered == false" style="margin-top : 15%;">
               <div class="row justify-content-center">
                  <div class="col-4">
                     <h1 style="letter-spacing: 1px; font-weight: bold;" class="h1 text-center">
                        <i class="fab fa-cuttlefish"></i>ONSTRUCTO
                     </h1>
                  </div>
                  <div class="col-8 border-left">
                     <h3
                        style="letter-spacing: 1px; font-weight : bold;"
                        >Find your ideal construction material and compare prices from different stores</h3>
                  </div>
               </div>
            </div>
            <div class="form-group" v-else style="margin-top : 1%;"></div>
            <div class="form-group">
               <div class="row">
                  <div class="col-9" style="padding-right : 0px !important;">
                     <input
                        class="form-control"
                        style="padding-right : 0px !important;"
                        type="text"
                        id="searchBar"
                        placeholder="e.g. Wrench"
                        v-model="searchedProduct.keyword"
                        >
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3" style="padding-left : 0px !important;">
                     <button
                        class="btn btnCustom btn-block"
                        style=" padding-left : 0px !important; background-color:#fd7e14 !important;"
                        v-on:click="searchProduct();"
                        >
                     <span style="letter-spacing : 1px;">
                     <b style="color :white; ">
                     <i class="fas fa-search"></i> Search
                     </b>
                     </span>
                     </button>
                     <button class="btn btnCustom btn-block" v-if="isSearchTriggered == false" v-on:click="viewMap();" style="background-color: rgb(65, 65, 66) !important; color: white; font-weight: bold;"> <i class="fas fa-map-marked-alt"> </i> View in Map </button>
                  </div>
               </div>
            </div>
            <div class="row">
               <div
                  class="col-4"
                  style=" border-color: rgb(65, 65, 66);border-style : solid;border-bottom-width: 0px; border-right-width: 0px; border-top-width: 0px;border-left-width: 15px;"
                  >
                  <div class="form-group">
                     <label class="form-label" for="priceFinder" style="letter-spacing : 1px;">Price :</label>
                     <span style="float: right; letter-spacing : 1.3px;">max: ₱{{priceFinder}}</span>
                     <input
                        type="range"
                        id="priceFinder"
                        class="form-control"
                        v-model="priceFinder"
                        min="1"
                        max="10000"
                        >
                  </div>
               </div>
               <div
                  class="col-4"
                  style=" border-color: rgb(65, 65, 66);border-style : solid;border-bottom-width: 0px; border-right-width: 0px; border-top-width: 0px;border-left-width: 15px;"
                  >
                  <div class="form-group">
                     <label class="form-label" style="letter-spacing : 1px;">Category :</label>
                     <select class="form-control" id="productCategory" v-model="searchedProduct.category">
                        <option
                           v-for="productcategory in productCategories"
                           v-bind:value="productcategory.id"
                           >{{productcategory.category}}</option>
                     </select>
                  </div>
               </div>
               <div
                  class="col-4"
                  style=" border-color: rgb(65, 65, 66);border-style : solid;border-bottom-width: 0px; border-right-width: 0px; border-top-width: 0px; border-left-width: 15px;"
                  >
                  <div class="form-group">
                     <label class="form-label" style="letter-spacing : 1px;">Payment Term :</label>
                     <select class="form-control" id="productCategory" v-model="searchedProduct.term">
                        <option v-bind:value="1">Cash Upon Pickup</option>
                        <option v-bind:value="2">Cash On Delivery</option>
                        <option v-bind:value="3">Both</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="row" v-if="isSearchTriggered == true && isViewMap == false" style="height : 55px;">
         <div class="col-2 text-center align-middle" style="background-image : url('/uploads/productImages/admin-map.png')">
            <button
               class="btn btn-default align-middle"
               v-on:click="viewMap();"
               style="margin-top: 5%; border-radius: 0px !important; border: black solid 1px; background-color: white; font-weight: bold;"
               >View Map</button>
         </div>
      </div>
      <div class="row" v-if="isSearchTriggered == true && isViewMap == true" style="height : 55px;">
         <div class="col-2 text-center align-middle">
            <button
               class="btn btn-default align-middle"
               v-on:click="backToList();"
               style="margin-top: 5%; border-radius: 0px !important; border: black solid 1px; background-color: white; font-weight: bold;"
               >Return to List</button>
         </div>
      </div>
      <!-- List of Searched Product -->
      <div class="justify-content-center align-center mt-4 mr-1" v-if="isSearchTriggered == true && isViewMap == false" style="margin-top : 5%; background-color: #f5f5f5;">
         <div >
             <div class="card mt-2" v-for="product in loadProducts" v-if="loadProducts.length != 0 && product.isblocked == 0 && product.isViewable == 1"  v-on:click="visitStore(product.id,product.storeId);">
                 <div class="card-body">
                        <img :src="'/uploads/productImages/'+product.image" style="float : left; width:120px !important; height:120px;">
                    <div class="row">
                        <div class="col-sm-4">
                            <span style="font-size: 16px; text-transform: capitalize; font-size: 20px; font-weight: bold;"> {{product.productname}}</span>
                        </div>
                        <div class="col-sm-4">
                            <span style="color: #fd7e14; font-size: 22px; font-weight: bold;" v-if="product.price != 0">₱ {{product.price}} </span>
                            <span style="color: #fd7e14; font-size: 22px; font-weight: bold;" v-if="product.price == 0"> Negotiable  </span>
                        </div>
                        <div class="col-sm-4">
                            <span style="font-size: 16px;"> {{product.productdescription}} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p style="text-transform: capitalize;"> {{product.storename}} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <p style="font-weight : 150; !important; font-size : 15px; letter-spacing : 1px; margin-bottom : 0px !important;">
                               <span v-if="product.isAvailable == 1" style="font-weight: bold; color: green;">In Stock</span>
                               <span v-else style="color: red; font-weight: bold;">Out of Stock</span>
                            </p>
                        </div>
                    </div>
                 </div>
             </div>
            <div class="row justify-content-center wrapper container" v-if="loadProducts.length == 0">
               <div class="card">
                  <div class="card-body">
                     <p>No product that matches your search</p>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!--List of search product  in google maps view  -->
      <div v-if="isSearchTriggered == true && isViewMap == true" class="row">
         <div class="col-6">
            <div
               class="card-body align-center border mt-1"
               v-for="product in loadProducts"
               v-if=" product.isblocked == 0 && product.isViewable == 1"
               style="cursor: pointer;"
               >
               <div class="row" @click="zoomStoreLocation(product.location, product.storename)">
                  <img
                     :src="'/uploads/productImages/'+product.image"
                     class="rounded border border-dark"
                     style="margin-left : 1%; width:90px !important; height:90px;"
                     >
                  <div class="col ml-2">
                     <h3
                        style="font-weight : bold !important; letter-spacing : 1px; font-size : 20px; text-transform: capitalize;"
                        >{{product.productname}}</h3>
                     <p
                        style="font-weight : 100 !important; font-size : 15px; letter-spacing : 1px; margin-bottom : 0px !important;"
                        >{{product.storename}}</p>
                     <p
                        style="font-weight : 150 !important; font-size : 15px; letter-spacing : 1px; margin-bottom : 0px !important;"
                        >
                        <span v-if="product.isAvailable == 1" style="color: green;">In Stock</span>
                        <span v-else style="color: red;">Out of Stock</span>
                     </p>
                     <p
                        style="font-weight : 100 !important; font-size : 15px; letter-spacing : 1px; margin-bottom : 0px !important;"
                        >{{product.category}}</p>
                     <div style="margin-left: 0; display: block;">
                        <span style="text-algin: left;">
                           <ul style="padding-left : 20px !important;">
                              <li
                                 style="font-weight : 100 !important; font-size : 15px;"
                                 >{{product.productdescription}}</li>
                           </ul>
                        </span>
                     </div>
                  </div>
                  <div class="col-5">
                     <p style="color: #fd7e14; font-size: 22px; font-weight: bold;" v-if="product.price != 0">₱ {{product.price}}</p>
                     <p style="color: #fd7e14; font-size: 22px; font-weight: bold;" v-if="product.price == 0"> Negotiable </p>
                  </div>
               </div>
               <div class="row">
                   <div class="col-12"> <button class="btn btn-full" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="visitStore(product.id,product.storeId);"> View Product </button> </div>
               </div>
            </div>
         </div>

         <div class="col-6">
            <google-map style="width: 140%;" :propSearchKeyword="searchedProduct.keyword" :propCategory="searchedProduct.category" :propPrice="priceFinder"></google-map>
         </div>
      </div>
      <div class="row mt-2 justify-content-center" v-if="loadProductsPagination.total != 0 && isSearchTriggered == true">
               <div class="col-5">
                    <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductsPaginate(loadProductsPagination.prev_page_url)" :disabled="!loadProductsPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
               </div>
               <div class="col-2">
                    <span> page {{loadProductsPagination.current_page}} of {{loadProductsPagination.last_page}}</span>
               </div>
               <div class="col-5">
                    <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchProductsPaginate (loadProductsPagination.next_page_url)" :disabled="!loadProductsPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
               </div>
            </div>

      <div class="row mt-3">
         <div class="col-sm-4">
             <div class="card" style="border-radius: 0px;">
                 <div class="card-header h5" style="color: rgb(253, 126, 20);"> <i class="fas fa-expand-arrows-alt"> </i>  Versatile Application </div>
                 <div class="card-body">
                    <p class="text-justify"> Constructo is your versatille construction supply ordering application because it has all the different construction supply category that you may need along the road. </p>
                 </div>
             </div>
         </div>
         <div class="col-sm-4">
             <div class="card">
                 <div class="card-header h5" style="color: rgb(253, 126, 20);"> <i class="fas fa-piggy-bank"> </i> Cheaper Products </div>
                 <div class="card-body">
                      <p class="text-justify"> Constructo application is open for all stores, small or big ,enterprise or solo proprietor everyone is welcome. So customers can purchase cheaper price than the leading stores in Davao City. </p>
                 </div>
             </div>
         </div>
         <div class="col-sm-4">
             <div class="card">
                 <div class="card-header h5" style="color: rgb(253, 126, 20);"> <i class="fas fa-percentage"> </i> No Mark-ups </div>
                 <div class="card-body">
                      <p class="text-justify"> Every price in each product that you see in Constructo has no markup price, because Constructo company has a more profitabble way of maintaining the application.  </p>
                 </div>
             </div>
         </div>
      </div>
      <div
         class="modal fade"
         id="searchEmpty"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true"
         >
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5
                     class="modal-title"
                     id="exampleModalLabel"
                     >Please type in the product you want to search</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-footer justify-content-center">
                  <button
                     type="button"
                     class="btn btn-success"
                     style="border-radius: 0px; background-color: #fd7e14;"
                     data-dismiss="modal"
                     >Got It!</button>
               </div>
            </div>
         </div>
      </div>
      <!-- BlockCustomer Modal -->
      <div
         class="modal fade"
         id="blockedModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true"
         >
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <p class="modal-title" id="exampleModalLabel">You have</p>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="card">
                     <div class="card-body">
                        <p class="text-justify">You have been
                           <strong>Banned</strong>
                        </p>
                     </div>
                  </div>
               </div>
               <div class="modal-footer justify-content-center">
                  <button
                     type="button"
                     class="btn"
                     style="border-radius: 0px; background-color: #fd7e14; color: white; "
                     data-dismiss="modal"
                     >Got It!</button>
               </div>
            </div>
         </div>
      </div>

      <!--verify email modal -->


    <div class="modal fade" id="orderNotif" tabindex="-1" role="dialog" style="border-radius: 0px !important;">
      <div class="modal-dialog" role="document" style="border-radius: 0px !important;">
        <div class="modal-content" style="border-radius: 0px;">
          <div class="modal-header">
              <h5 class="modal-title" style="font-weight: bold; color: rgb(253, 126, 20);">Important Reminder</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body text-justify">
             <p style="font-size: 20px;"> You have <strong> {{ordersDueCount}} </strong> <span v-if="ordersDueCount == 1"> order </span> <span v-if="ordersDueCount != 1"> orders </span> that has the due date of today. </p>
             <strong style="font-size: 14px;"> Stores only open until 5:00 PM </strong>
          </div>
          <div class="modal-footer">
              <div class="row">
                  <div class="col-12">
                    <button type="button" data-dismiss="modal" class="btn btn-primary form-control" style="border-radius: 0px;  background-color: rgb(65, 65, 66);">Got it</button>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

      <div id="MessageButtonContainer" v-if="isMessageClicked == false">
         <button class="btn" style="border-radius: 0px; background-color: #fd7e14; color:white;" @click="viewMessageThreads()">
         <span style="font-size:20px;"> <i class="fas fa-envelope"></i> Messages <span class="badge badge-danger" v-if="unSeenMsg !=0"> {{unSeenMsg}} </span>  </span>
         </button>
      </div>
      <div id="MessageThreadContainer" v-if="isMessageClicked == true">
         <div class="card w-100">
            <div class="card-header">
               <div class="row">
                  <div class="col-9">
                     <i class="fas fa-envelope"></i> Messages  </div>
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
                           <i class="fas fa-store"></i> Stores  </div>
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
                           <i class="fas fa-comments"></i> Conversation </div>
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
import GoogleMap from "./GoogleMap";
import Loading from "./Loading";

export default {
  components: {
    "google-map": GoogleMap,
    loading: Loading
  },
  data: function() {
    return {
      priceFinder: 2000,
      customerId: localStorage.getItem("id"),
      isMessageClicked: false,
      showLoading: false,
      productCategories: [],
      searchedProduct: {
        keyword: "",
        category: 1,
        term: 1
      },
      isSearchTriggered: false,
      loadProducts: [],
      loadProductsPagination : [],
      dummy: 1,
      isViewMap: false,
      BlockedByStoreName: "",
      messageHeads: [],
      messages: [],
      msgId: 0,
      messageBody: "",
      isConvoClick: false,
      messageStoreId: 0,
      unSeenMsg : 0,
      localisSeen : 0,
      ordersDue : [],
      ordersDueCount : 0,
      isEmailVerified : true
    };
  },
  methods: {
    getOrderDueToday : function(){
        var date_now = new Date();
        var date_month = date_now.getMonth() + 1;
        var date_day = date_now.getDate();
        var date_year = date_now.getFullYear();
        var real_date_now = date_year+'-0'+date_month+'-'+date_day;
        let data = { customerId : this.customerId};

      axios.post('api/getOrderDueToday',data).then(resp => {
          this.ordersDue = resp.data;
           for(let x= 0 ; x < resp.data.length; x++){
               console.log(resp.data[x].status);
               console.log(real_date_now);
              if(real_date_now === resp.data[x].due_date && resp.data[x].status == 1){
                  this.ordersDueCount = this.ordersDueCount + 1;
              }
           }
           if(this.ordersDueCount != 0){
           $('#orderNotif').modal('show');
           }

      });
    },
    customerUnseenMsg : function(){
       let data = {customerId : this.customerId};
        axios.post('api/customerUnseenMsg',data).then(resp => {
          this.unSeenMsg = resp.data.length;
          if(this.unSeenMsg != 0){
              this.localisSeen = 0;
          }
        });
    },
    loadFeaturedProducts: function() {
      axios.get("api/getFeaturedProducts").then(response => {
        this.featuredProducts = response.data;
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
        let data = {msgId : this.msgId, isSeen : 1};
           axios.post('api/CustomerViewConvo',data).then(resp => {
             this.messages = resp.data;
           });
           setTimeout(()=>{
               this.getMessageHeads();
              this.customerUnseenMsg();
           },1000);

    },
    getMessages: function(msgId, storeId) {

      this.isConvoClick = true;
         this.localisSeen = 0;
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
    getMessageHeads: function() {
      let data = { customerId: this.customerId };
      axios.post("api/customerGetMessageHeads", data).then(response => {
        this.messageHeads = response.data;

        setTimeout( ()=>{
            this.customerUnseenMsg();
         }, 2000);
      });
    },
    viewMessageThreads: function() {
      this.isMessageClicked = true;
      this.getMessageHeads();
    },
    checkCustomerValidity: function() {
      let data = { customerId: localStorage.getItem("id") };
      axios.post("api/checkCustomerValidity", data).then(response => {
        if (response.data[0].length != 0) {
          this.$router.push("/MyAccount");
        }
      });
    },
    getCustomerViaOrder(orderId) {
      let data = { orderId: orderId };
      axios.post("api/getCustomerViaOrder", data).then(response => {
        if (localStorage.getItem("id") == response.data[0].customerId) {
          $("#blockedModal").modal("show");
        }
      });
    },
    listenToBeBlocked: function() {
      Echo.channel("BlockCustomerChannel").listen(
        "BlockCustomerEvent",
        message => {
          this.getCustomerViaOrder(message.orderId[0]);
        }
      );
    },
    loadCategories: function() {
      let app = this;
      axios.get("api/getCustomerProductCategories").then(response => {
        app.productCategories = response.data;
        app.productCategories.push({ id: 0, category: "All" });
      });
    },
    searchProduct: function() {
      this.showLoading = true;
        setTimeout(() => {
          $("#searchEmpty").modal("hide");
        }, 4000);

        let obj = {
          keyword: this.searchedProduct.keyword,
          category: this.searchedProduct.category,
          term: this.searchedProduct.term,
          minprice: this.priceFinder
        };
        this.isSearchTriggered = true;
        axios.post("api/customersearchProduct", obj).then(response => {
          this.loadProducts = response.data.data;
          this.makeProductsPaginate(response.data);
          this.showLoading = false;
        });

    },
    fetchProductsPaginate : function(url){
        let obj = {
          keyword: this.searchedProduct.keyword,
          category: this.searchedProduct.category,
          term: this.searchedProduct.term,
          minprice: this.priceFinder
        };
      axios.post(url,obj).then(resp =>{
       this.loadProducts= resp.data.data
       this.makeProductsPaginate(resp.data);
      });
    },
    makeProductsPaginate : function(data){
       let pagination = {
           current_page : data.current_page,
           last_page : data.last_page,
           next_page_url : data.next_page_url,
           prev_page_url : data.prev_page_url
       }
       this.loadProductsPagination = pagination;
    },
    viewMap: function() {
      this.isViewMap = true;
      this.isSearchTriggered = true;

      let obj = {
          keyword: this.searchedProduct.keyword,
          category: this.searchedProduct.category,
          term: this.searchedProduct.term,
          minprice: this.priceFinder
        };
        this.isSearchTriggered = true;
        axios.post("api/customersearchProduct", obj).then(response => {
          this.loadProducts = response.data.data;
          this.makeProductsPaginate(response.data);
          this.showLoading = false;
        });

    },
    zoomStoreLocation : function(location, storename){
        let data = {location : location, storename : storename};
        this.$eventBus.$emit('searchData',data);
    },
    backToList: function() {
      this.isViewMap = false;
      this.searchProduct();
    },
    refreshAvailability: function() {
      Echo.channel("productAvailabilityChannel").listen(
        "ProductAvailabilityEvent",
        message => {
          this.searchProduct();
        }
      );
    },
    visitStore: function(id,storeid) {
        localStorage.setItem('SearchedStoreId',storeid);
      localStorage.setItem("customerSearchedProduct", id);
      this.$router.push({ name: "CustomerStoreVisitPage" });
    },
    listenToCustomerMessage: function(){
        Echo.channel('listenToStoreChannel').listen('ListenToStoreMsg', message=>{
             console.log(message);
               if(message.customerId == this.customerId){
                     this.viewUpdatedConvo();
          }
       });
    },
    checkEmailIsVerified : function(){
        let data = { 'customerId' : this.customerId};
        var arry = [];
         axios.post('api/CustomerisEmailVerified',data).then(resp => {
             arry = resp.data;
             console.log(" is verified is "+arry[0].isVerified);
             if(arry[0].isVerified === 0){
                this.isEmailVerified = false;
                 this.$router.push({name : 'CustomerMyAccount'});
             }else if(arry[0].isVerified ==1){
                this.isEmailVerified = true;
             }
              console.log(" is verified is "+this.isEmailVerified);
         });
    },

  },
  mounted: function() {
    //

        this.checkEmailIsVerified();
    if(this.isEmailVerified == false){

    }else if(this.isEmailVerified == true){

    this.refreshAvailability();
    this.listenToBeBlocked();
    this.listenToCustomerMessage();
    this.checkCustomerValidity();
    this.getOrderDueToday();

    setTimeout( ()=> {
    this.loadFeaturedProducts();
    this.loadCategories();
      setTimeout (()=>{
         this.customerUnseenMsg();
      },2000);
    },2000);
    }

  }
};
</script>
<style scoped>
#searchBar {
  border-radius: 0px !important;
}
.btnCustom {
  border-radius: 0px !important;
  background-color: #3b71c6 !important;
}
#productCategory {
  border-radius: 0px !important;
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
.card-header {
  background-color: rgb(65, 65, 66);
  color: white;
  border-radius: 0px;
}
.card {
    border-radius: 0px;
}
.card-body {
    border-radius: 0px;
}


</style>

