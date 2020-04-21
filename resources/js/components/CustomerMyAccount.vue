<template>
<div>
 <myHeader propBrand="CONSTRUCTO"> </myHeader>
    <div class="container">
        <div class="card mt-3" v-for="customer in customers">
            <div class="card-header"> <span style="text-transform: capitalize;"><i class="fas fa-user" style="margin-right: 7px;"> </i> Hello, {{customer.firstname}} ! </span> </div>
            <div class="card-body">
                <div class="row"  v-if="customer.isblocked == 1">
                 <div class="col-12">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-danger">Unfortunatelly, you are banned from using our application because you did not apear at the arrival of the delivery</li>
                        <li class="list-group-item list-group-item-danger"> To unban your account please contact our administrator at admin@gmail.com </li>
                    </ul>
                 </div>
                </div>
              <div class="row mt-2">
                  <div class="col-4">
                      <div class="card">
                          <img class="card-img-top" style="width: 334px; height: 210.06px;" :src="'/uploads/productImages/'+customer.image" v-if="hasSelectedProfilePicture == false">
                           <img class="card-img-top" style="width: 334px; height: 210.06px;" :src="imageData" v-else>
                          <div class="card-body border-top">
                              <div class="row">
                                  <input class="form-control" type="file" accept="image/x-png,image/gif,image/jpeg" @change="preivewProfilePicture">
                              </div>
                              <div class="row mt-2">
                                  <div class="col-12"> <span style="text-transform: capitalize; font-size: 35px;"> {{customer.firstname}} {{customer.lastname}} </span> </div>
                              </div>
                              <div class="row">
                                  <div class="col-12">
                                  <div v-if="customer.isblocked == 0">
                                      <button class="btn" style="background-color: green; border-radius: 0px;" disabled> </button> <small  style="color: green;font-weight: bold;"> Active </small>
                                  </div>
                                  <div v-else>
                                       <button class="btn" style="background-color: red; border-radius: 0px;" disabled> </button> <small style="color: red; font-weight: bold;"> Banned </small>
                                   </div>
                                  </div>
                              </div>
                              <div class="row mt-3" v-if="customer.attempts != 0">
                                 <div class="col-6" > <p style="color: #e5b442;"> Failed Delivery :  </p> </div>
                                 <div class="col-6" > <p style="color: #ef5f5f;"> {{customer.attempts}} </p> </div>
                                 <div class="col-12">
                                    <small> <strong> Note : </strong>    Please make sure the date your items will be delivered or to be picked up, is a date wherein you are free. </small>
                                 </div>
                              </div>
                              <div class="row mt-4 border-top">
                                  <div class="col-12 mt-2">
                                      <span> {{customer.email}} </span>
                                      <button class="btn" style="border-radius: 0px; background-color:  #fd7e14;" v-if="customer.isVerified == 0" data-target="#potangina-modal" data-toggle="modal"> Verify Email</button>
                                      <span v-if="customer.isVerified == 1" style="color: green;"> Verified Email</span>
                                  </div>
                             </div>
                             <div class="row">
                                 <div class="col-12 mt-2">
                                      <span style="text-transform: capitalize;"> {{customer.address}} </span>
                                 </div>
                             </div>

                             <div class="row mt-5">
                                 <hr>
                                 <div class="col-12">
                                     <button class="btn form-control" style="border-radius:0px; background-color:  #fd7e14; color: white;" @click="saveChanges()"> <i class="fas fa-user-circle"> </i> Save Profile Picture </button>
                                 </div>
                             </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-8">
                    <customer-map style="width: 550px; height: 350px;" propName="CustomerMap" :propCustomerAddress="customer.address"> </customer-map>
                  </div>
              </div>
            </div>
        </div>
    </div>

     <div id="MessageButtonContainer" v-if="isMessageClicked == false">
         <button class="btn" style="border-radius: 0px; background-color: #fd7e14; color:white;" @click="viewMessageThreads()">
         <span style="font-size:20px;"> <i class="fas fa-envelope"></i> Messages  </span>
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
                                     {{messageHead.firstname}} {{messageHead.lastname}}</span>
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

      <div class="modal fade" id="potangina-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-12">  <input type="text" class="form-control" placeholder="Enter verification code here..." v-model="code"> </div>
              <div class="col-12 mt-2">  <button class="btn btn-block" style="border-radius: 0px; background-color: #fd7e14;" @click="sendCode();"> Send Code </button>
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
import CustomerMap from './CustomerMap';
export default {
    data : function(){
        return{
        customerId : localStorage.getItem('id'),
        customers : [],
        hasSelectedProfilePicture : false,
        profilePictureData : '',
        imageData : '',
        messageHeads: [],
        messages: [],
        msgId: 0,
        messageBody: "",
        isConvoClick: false,
        isMessageClicked: false,
        messageStoreId: 0,
        code : ''
        }
    },
    components : {
        'myHeader' : CustomerHeader,
        'customer-map' : CustomerMap

    },
    methods : {
        customerGetProfile : function(){
          let data = {customerId : this.customerId};
          axios.post('api/customerGetProfile',data).then(response=>{
             this.customers = response.data;
          })
        },
        preivewProfilePicture : function(event){
         var input = event.target;
          if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = (e) =>{
              this.hasSelectedProfilePicture = true;
              this.profilePictureData = input.files[0];
              this.imageData = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    },
    saveChanges : function(){
         var form = new FormData();
        let config = {
           'accept': 'application/json',
           'Accept-Language': 'en-US,en;q=0.8',
           'Content-Type':'multipart/form-data; boundary=${data._boundary}'
        }
        form.append('image',this.profilePictureData);
        form.append('userId',this.customerId);

        axios.post('api/saveCustomerProfile',form,config).then(reponse=>{

        });
    },
     sendCode : function(){
        let data = { code : this.code, userId : this.customerId};
        $('#potangina-modal').modal('hide');
           axios.post('api/verifyyawa',data).then(resp => {
                 if(resp.data == 1){
                     alert('email verified');
                 }else{
                     alert('wrong code');
                 }
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
        let data = {msgId : this.msgId};
           axios.post('api/CustomerViewConvo',data).then(resp => {
             this.messages = resp.data;
           });
    },
    getMessages: function(msgId, storeId) {
      this.isConvoClick = true;
      let data = { msgId: msgId, message: this.messageBody };
      this.msgId = msgId;
      this.messageStoreId = storeId;
      axios.post("api/CustomerViewConvo", data).then(response => {
        this.messages = response.data;
      });
    },
    getMessageHeads: function() {
      let data = { customerId: this.customerId };
      axios.post("api/customerGetMessageHeads", data).then(response => {
        this.messageHeads = response.data;
      });
    },
    viewMessageThreads: function() {
      this.isMessageClicked = true;
      this.getMessageHeads();
    },
    },
    mounted : function(){
      if(localStorage.getItem('id')){
         this.customerGetProfile();
      }else{
        this.$router.push('/');
      }
    }
}
</script>
<style scoped>
.card-header{
    background-color:rgb(65, 65, 66);
    color: white;
    font-size: 16px;
}
.container{
    margin-left: 20px !important;
    margin-right: 10px !important;
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
