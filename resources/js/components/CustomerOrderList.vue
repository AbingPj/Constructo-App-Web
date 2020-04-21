<template>
 <div>
  <myHeader propBrand="CONSTRUCTO"> </myHeader>
  <Loading v-if="showLoading == true"> </Loading>

  <div class="container mt-3">


      <div class="card">
          <div class="card-header">
              <div class="row">
                 <div class="col-3"><i class="fas fa-clipboard-list"> </i> Order Overview </div>
                 <div class="col-9">
                 </div>
              </div>
          </div>
          <div class="card ml-4 mr-4 mt-3">
             <div class="card-header"> <i class="fas fa-filter"> </i> Filter Options </div>
             <div class="card-body">
                 <div class="row">
                     <div class="col-sm-4 form-group">
                         <label> Payment Term : </label>
                         <select class="form-control" v-model="orderFilterTerm" >
                             <option :value="1"> Cash Upon Pickup </option>
                             <option :value="2"> Cash On Delivery </option>
                             <option :value="3"> All </option>
                         </select>
                     </div>
                     <div class="col-sm-4 form-group" v-if="orderFilterTerm == 1">
                         <label> Order Status :</label>
                         <select class="form-control"  v-model="orderFilterCUPStatus">
                             <option :value="1"> Pending </option>
                             <option :value="3"> Fetched </option>
                             <option :value="4"> Cancelled </option>
                         </select>
                     </div>
                     <div class="col-sm-4 form-group" v-if="orderFilterTerm == 2">
                         <label> Order Status :</label>
                         <select class="form-control" v-model="orderFilterCODStatus">
                             <option :value="1"> Pending </option>
                             <option :value="2"> Delivered </option>
                             <option :value="4"> Cancelled </option>
                         </select>
                     </div>
                     <div class="col-sm-4 form-group" v-if="orderFilterTerm == 3">
                         <label> Order Status :</label>
                         <select class="form-control" v-model="orderFilterCODStatus">
                               <option :value="1"> Pending </option>
                               <option :value="2"> Delivered </option>
                               <option :value="3"> Fetched </option>
                               <option :value="4"> Cancelled </option>
                            </select>
                     </div>
                     <div class="col-sm-4 form-group">
                         <label> Sort Due Date: </label>
                         <select class="form-control" v-model="DateSortKey">
                              <option :value="'asc'"> Order due date by Asc </option>
                              <option :value="'desc'"> Order due date by Desc</option>
                         </select>
                     </div>
                 </div>
          </div>
      </div>

      <div class="card-body">
          <div class="card mt-2">
              <div class="card-header">
                 <div class="row">
                   <div class="col-1"> Order ID </div>
                   <div class="col-2"> Store </div>
                   <div class="col"> Date Ordered</div>
                   <div class="col"> Due Date</div>
                   <div class="col"> Payment Term</div>
                   <div class="col"> Status </div>
                </div>
              </div>
          </div>
        <div class="card mt-3" v-for="orderStore in orderStores" v-if="orderStores.length != 0" @click="viewSingleOrder(orderStore.orderId)">
          <div class="card-header" style="cursor: pointer;">
             <div class="row">
                 <div class="col-1"> <span style="font-size: 16px;">  #{{orderStore.orderId}} </span></div>
                 <div class="col" ><span style="font-size: 16px;"> <img :src="'uploads/productImages/'+orderStore.image" style="width: 40px; height: 40px; border-radius: 50%;">  {{orderStore.storename}} </span> </div>
                 <div class="col"> <span style="margin-right: 23px;"> {{orderStore.date_ordered}} </span> </div>
                 <div class="col"> <span style="margin-right: 23px;"> {{orderStore.due_date}} </span> </div>
                 <div class="col"> <span style="margin-right: 23px;"> {{orderStore.paymentTerm}}  </span> </div>
                 <div class="col">
                    <span style="margin-right: 23px;color: #e88e10; " v-if="orderStore.status == 'Pending'"> {{orderStore.status}} </span>
                    <span style="margin-right: 23px;color: #71c44a; " v-if="orderStore.status == 'Delivered' || orderStore.status == 'Fetched'"> {{orderStore.status}} </span>
                    <span style="margin-right: 23px;color: #ed3636; " v-if="orderStore.status == 'Cancelled'"> {{orderStore.status}} </span>
                 </div>
             </div>
          </div>
        </div>
        <div class="row mt-2 justify-content-center" v-if="orderStores.length != 0">
               <div class="col-5">
                    <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchStoreOrderPaginate(orderStoresPagination.prev_page_url)" :disabled="!orderStoresPagination.prev_page_url"> <i class="fas fa-chevron-circle-left"> </i> Prev</button>
               </div>
               <div class="col-2">
                    <span> page {{orderStoresPagination.current_page}} of {{orderStoresPagination.last_page}}</span>
               </div>
               <div class="col-5">
                    <button class="btn btn-block" style="border-radius:0px; background-color: #fd7e14; color :white;" @click="fetchStoreOrderPaginate (orderStoresPagination.next_page_url)" :disabled="!orderStoresPagination.next_page_url"> <i class="fas fa-chevron-circle-right"> </i> Next</button>
               </div>
            </div>
        <div class="row mt-4" v-if="orderStores.length == 0">
             <div class="col-12 text-center"> <p> Orders empty </p> </div>
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
                              <div class="list-group-item mt-2" style="background-color:#fd7e14; color:white; padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 0">
                                 <span>{{message.message}}</span>
                              </div>
                              <div class="list-group-item mt-2 text-right" style="padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 1">
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
 data : function(){
     return {
         showLoading : false,
         customerId : localStorage.getItem('id'),
         orderStores : [],
         orderStoresPagination : [],
         orderItems: [],
         paymentTerm: 1,
         CODstatusFilter: 1,
         CUPstatusFilter: 1,
         isMessageClicked : false,
         isConvoClick: false,
         messageBody : '',
         messageHeads: [],
         messages: [],
         unSeenMsg : 0,
         DateSortKey : 'asc',
         orderFilterTerm : 3,
         orderFilterCODStatus: 1,
         orderFilterCUPStatus: 1,
     }
 },
 watch : {
     DateSortKey: function(val){
      let data = {customerId : this.customerId , orderKey : val};
      this.showLoading = true;
         axios.post('api/filterCustomerOrderListByDueDate',data).then(resp => {
             this.showLoading = false;
             this.orderStores = resp.data.data;
             this.makeStoreOrderPaginate(resp.data);
         })
     },
     orderFilterTerm : function(val){
        if (val == 2){
            let data = {customerId : this.customerId ,term : 2, status : this.orderFilterCODStatus};
           axios.post('api/filterCustomerOrderList',data).then(response=>{
            this.orderStores = response.data.data;
             this.makeStoreOrderPaginate(response.data);
           });
        }
        if(val == 1){
            let data = {customerId : this.customerId ,term : 1, status : this.orderFilterCUPStatus};
           axios.post('api/filterCustomerOrderList',data).then(response=>{
             this.orderStores = response.data.data;
             this.makeStoreOrderPaginate(response.data);
           });
        }
        if(val == 3){
           this.getAllStoreOrder();
           this.getAllItemsOrder();
        }
     },
     orderFilterCODStatus : function(val){
         let data = {customerId : this.customerId, term : this.orderFilterTerm , status : val};
            axios.post('api/filterCustomerOrderList',data).then(response=>{
              this.orderStores = response.data.data;
             this.makeStoreOrderPaginate(response.data);
         });
     },
     orderFilterCUPStatus : function(val){
        let obj = {customerId : this.customerId, term : this.orderFilterTerm , status : val};
           axios.post('api/filterCustomerOrderList',obj).then(response=>{
             this.orderStores = response.data.data;
             this.makeStoreOrderPaginate(response.data);
        });
     }
 },
 methods : {
  sendReplyToStore : function(){
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
  viewSingleOrder: function(orderId){
    localStorage.setItem('customerSingleOrder',orderId);
    this.$router.push({ name : 'CustomerSingleOrder'});
  },
  getAllStoreOrder : function(){
    let data= {customerId: this.customerId}
      axios.post('api/getAllOrderStore',data).then(response=>{
         this.orderStores = response.data.data;
         this.makeStoreOrderPaginate(response.data);
      });
  },
  getAllItemsOrder : function(){
    let obj=  {customerId : this.customerId}
      axios.post('api/getAllOrderItems',obj).then(response => {
       this.orderItems = response.data;
      });
  },
  fetchStoreOrderPaginate : function(url){
      if (this.orderFilterTerm == 2){
        var data= {customerId: this.customerId , term : this.orderFilterTerm, status : this.orderFilterCODStatus};
      } else if (this.orderFilterTerm == 1) {
        var data = {customerId: this.customerId , term : this.orderFilterTerm, status : this.orderFilterCUPStatus};
      }
      let obj = {customerId : this.customerId};

     axios.post(url,obj).then(resp => {
        this.orderStores = resp.data.data;
        this.makeStoreOrderPaginate(resp.data);
     });
  },
  makeStoreOrderPaginate : function(data){
      let pagination = {
          current_page : data.current_page,
          last_page : data.last_page,
          prev_page_url : data.prev_page_url,
          next_page_url : data.next_page_url
      }
      this.orderStoresPagination = pagination;
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
       this.listenToCustomerMessage();
     this.checkCustomerValidity();
      setTimeout (()=>{
         this.customerUnseenMsg();
           setTimeout( ()=>{
              this.getAllStoreOrder();
              this.getAllItemsOrder();
           },1000)
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
