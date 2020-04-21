<template>
 <div>
     <my-header> </my-header>
       <div class="container">
            <div class="card mt-3">
                <div class="card-header"> <span style="font-size:16px;"> <i class="fas fa-user"> </i> My Profile </span> </div>
                 <div class="card-body" v-for="profile in storeProfile">
                     <div class="row">
                         <div class="col-4">
                             <div class="card">
                                   <img class="card-img-top" style="width: 334px; height: 210.06px;" :src="'/uploads/productImages/'+profile.image" v-if="hasSelectedProfilePicture == false">
                                   <img class="card-img-top" style="width: 334px; height: 210.06px;" :src="imageData" v-else>
                                 <div class="card-body border-top">
                                     <div class="row">
                                        <div class="col-sm-12"> <input class="form-control" type="file" accept="image/x-png,image/gif,image/jpeg" @change="preivewProfilePicture"> </div>
                                     </div>
                                     <div class="row mt-2">
                                        <div class="col-sm-12"> <button style="background-color: #fd7e14; color: white; border-radius: 0px; font-size: 16px;" class="btn form-control" @click="saveCustomerProfile()"> <i class="fas fa-save"> </i> Save Profile Picture </button> </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-8">
                            <div class="row">
                               <div class="col-sm-12"> <i style="font-size: 16px;" class="fas fa-address-book"> </i> <span style="font-size:16px; text-transform: capitalize;"> {{storeProfile[0].firstname}} {{storeProfile[0].lastname}} </span> </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-sm-12">
                                    <span style="font-size:16px;" v-if="storeAccess === undefined || storeAccess.length == 0 || hasSendSubscriptionRequest.length != 0">
                                        <span style="font-size:16px;"> <i class="fas fa-hourglass-half"> </i> Wait for the request to be acccepted </span>
                                    </span>
                                    <span v-else style="font-size:16px;">
                                        <span style="font-size:16px;" v-if="storeAccess[storeAccess.length-1].daysLeft > 0 && storeAccess[0].isFreetrial == 2"> {{storeAccess[storeAccess.length-1].daysLeft}} days of Subscription</span>
                                        <span style="font-size:16px;" v-else-if="storeAccess[storeAccess.length-1].daysLeft < 0 || storeAccess[storeAccess.length-1].daysLeft < 0"> <i class="fas fa-sad-cry"> </i> Your access has been expired   <button class="btn" style="font-size: 16px; color: white; background-color: #fd7e14; border-radius: 0px;"  v-on:click="sendSubscriptionRequest()"> Send Subscription Request </button></span>
                                        <span style="font-size:16px;" v-else-if="storeAccess[storeAccess.length-1].isFreetrial == 1" > <i class="fas fa-smile-wink"> </i>  {{storeAccess[storeAccess.length-1].daysLeft}} days of Free Trial </span>
                                        <span style="font-size:16px;" v-else-if="storeAccess[storeAccess.length-1].isFreetrial == 2"> <i class="fas fa-smile-beam"> </i> {{storeAccess[storeAccess.length-1].daysLeft}} days of Subscription  </span>
                                    </span>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-sm-12"> <i class="fas fa-at"> </i> <span style="font-size:16px;"> {{storeProfile[0].email}} </span>
                             <button class="btn" style="border-radius: 0px; background-color: #fd7e14;" v-if="storeProfile[0].isVerified == 0" data-target="#potangina-modal" data-toggle="modal"> Verify Email </button>
                                 <span v-if="storeProfile[0] == 1" style="color: green;"> Email Verified </span>
                                 </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-sm-12"> <i class="fas fa-phone"> </i> <span style="font-size:16px;"> {{storeProfile[0].phone}} </span> </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-sm-12"> <i class="fas fa-map-marker-alt"> </i> <span style="font-size:16px;"> {{storeProfile[0].address}} </span> </div>
                            </div>
                        </div>
                     </div>
                                    </div>
                                </div>
       </div>
     <div id="MessageButtonContainer" v-if="isMessageClicked == false">
       <button class="btn " style="border-radius: 0px; background-color: #fd7e14; color:white;" @click="viewMessageThreads()"> <span style="font-size:20px;"> <i class="fas fa-envelope"> </i> Messages </span> <span v-if="unSeenMsg != 0" class="badge badge-danger"> {{unSeenMsg}} </span> </button>
     </div>
     <div id="MessageThreadContainer" v-if="isMessageClicked == true">
    <div class="card w-100">
      <div class="card-header">
          <div class="row">
              <div class="col-9"><i class="fas fa-envelope"> </i>  Messages</div>
              <div class="col-3 justify-content-end"><button type="button" @click="isMessageClicked=false;" class="close"  aria-label="Close"><span aria-hidden="true" style="color:white;">&times;</span></button>  </div>
              </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-5">
                   <div class="card">
                       <div class="card-header"> <span style="font-size:16px;"> <i class="fas fa-user"> </i> Customer  </span> </div>
                       <div class="card-body" style="overflow-y:scroll; height: 400px; padding : 5px 5px 5px 5px;">
                           <ul class="list-group" v-if="messageHeads.length != 0">
                               <li class="list-group-item mt-2" v-for="messageHead in messageHeads" style="text-transform:capitalize; cursor:pointer;" @click="viewConvo(messageHead.msgId, messageHead.customerId)"> <img :src="'/uploads/productImages/'+messageHead.profilepic" style="height:25px; width: 25px; border-radius:40px;">
                                <span v-if="messageHead.isSeen == 1 || localisSeen == 1"> {{messageHead.firstname}} {{messageHead.lastname}}  </span>
                                   <strong v-else> {{messageHead.firstname}} {{messageHead.lastname}}  </strong>
                               </li>
                           </ul>
                           <ul class="list-group" v-if="messageHeads.length == 0">
                               <li class="list-group-item text-center"> No messages </li>
                           </ul>
                       </div>
                   </div>
                </div>
                <div class="col-7" v-if="messageHeads.length != 0">
                     <div class="card">
                       <div class="card-header"> <span style="font-size:16px;"> <i class="fas fa-comments"> </i> Conversation  </span> </div>
                       <div class="card-body" style="overflow-y:scroll; height: 400px; padding : 5px 5px 5px 5px;">
                         <ul class="list-group" v-for="message in messages">
                            <div class="list-group-item mt-2"  v-if="message.isFromStore == 0" style="padding : 5px 5px 5px 15px;">
                                 <span> {{message.message}}  </span>
                            </div>
                            <div  class="list-group-item mt-2 text-right" style="background-color:#fd7e14; color:white; padding : 5px 5px 5px 15px;" v-if="message.isFromStore == 1">
                                <span> {{message.message}} </span>
                            </div>
                         </ul>
                       </div>
                       <div class="row p-2" v-if="isConvoClick == true">
                           <div class="col-sm-9">
                             <textarea v-model="messageBody" style="resize:none; border-radius: 0px;" class="form-control" placeholder="Type a message..."> </textarea>
                           </div>
                           <div class="col-sm-3">
                               <span style="color:#fd7e14; font-weight:bold;cursor:pointer;" @click="sendMessage()"> <i class="fas fa-paper-plane"> </i> Send </span>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--verify email modal -->
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
import myHeader from './myHeader';
export default {
   components :{
       'my-header' : myHeader
   },
   data: function(){
       return{
           storeProfile: [],
           code : '',
           storeId : localStorage.getItem('id'),
           storeAccess: [],
            hasSendSubscriptionRequest : [],
            isMessageClicked : false,
            hasSelectedProfilePicture : false,
            profilePictureData : '',
            imageData : '',
            messageCustomerId : 0,
            MsgId : 0,
            customerIdMessage : '',
            customerMessageBody : '',
            messageBody : '',
            messageHeads : [],
            messages : [],
            unSeenMsg : 0,
            localisSeen : 0
       }
   },
   methods : {
    storeUnseenMsg : function(){
      let data = {storeId : this.storeId};
        axios.post('api/storeUnseenMsg',data).then(resp => {
          this.unSeenMsg = resp.data.length;
          if(this.unSeenMsg != 0){
              this.localisSeen = 0;
          }
        });
    },
    listenToStoreMessage : function(){
        Echo.channel('listenToCustomerChannel').listen('ListenToCustomerMsg', message=>{
             console.log(message);
              if(message.storeId == this.storeId){
                     this.ViewUpdatedConvo();
               }
       });
    },
    sendCode : function(){
        let data = { code : this.code, userId : this.storeId};
        $('#potangina-modal').modal('hide');
           axios.post('api/verifyyawa',data).then(resp => {
                 if(resp.data == 1){
                     alert('email verified');
                 }else{
                     alert('wrong code');
                 }
           });
    },
    viewConvo : function(msgId, customerId){
        this.isConvoClick = true;
       let data = {msgId : msgId};
       this.messageCustomerId = customerId;
       this.localisSeen = 1;
       axios.post('api/storeViewConvo',data).then(response=>{
         this.MsgId = response.data[0].msgId;
         console.log(this.MsgId);
         this.messages = response.data;

          setTimeout( ()=>{
             this.storeUnseenMsg();
         },2000)
       });
    },
    fetchMessageHeads : function(){
        let data = {storeId : this.storeId};
        axios.post('api/storeGetMessageHeads',data).then(response=>{
           this.messageHeads = response.data;
        });
    },
    ViewUpdatedConvo : function(){
        this.isConvoClick = true;
       let data = {msgId : this.MsgId};
       axios.post('api/storeViewConvo',data).then(response=>{
         this.messages = response.data;
       });
            this.storeUnseenMsg();
    },
    sendMessage : function(){
       let data = {msgId: this.MsgId, message : this.messageBody, customerId : this.messageCustomerId};
        axios.post('api/sendMessageToCustomer',data).then(response=>{
           this.messageBody = "";
           this.ViewUpdatedConvo();
        });
    },
    getStoreProfile: function() {
      let app = this;
      let obj = { storeId: this.storeId };

      axios.post("api/getStoreAccount", obj).then(response => {
        app.storeProfile = response.data;
      });
    },
    getStoreAccess : function(){
      let obj = {storeId : this.storeId};
      let app = this;

      axios.post('api/getStoreAccess',obj).then(response=>{
        app.storeAccess = response.data;
        if(response.data.length == 0){
        app.storeAccess = undefined;
        }else{
       console.log(app.storeAccess[app.storeAccess.length-1].daysLeft);
        if(app.storeAccess[app.storeAccess.length-1].daysLeft < 0){
             app.hideStore();
        }
        }

      });
    },
    hideStore : function(){
      let data = {storeId : this.storeId};
        axios.post('api/hideStore',data).then(response => {
            console.log('you have been hided');
            console.log(response.data);
        })
    },
    checkSendSubscriptionRequest : function(){
       let obj = {storeId : this.storeId};
       axios.post('api/checkSendSubscriptionRequest',obj).then( (response)=>{
         this.hasSendSubscriptionRequest = response.data;

       });
    },
    viewMessageThreads : function(){
      this.isMessageClicked = true;
      this.fetchMessageHeads();
    },
    sendSubscriptionRequest : function(){
         let obj  = {userId : localStorage.getItem('id')};
        axios.post('api/sendSubscriptionRequest',obj);

        this.checkSendSubscriptionRequest();
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
    saveCustomerProfile : function(){
        var form = new FormData();
        let config = {
           'accept': 'application/json',
           'Accept-Language': 'en-US,en;q=0.8',
           'Content-Type':'multipart/form-data; boundary=${data._boundary}'
        }
        form.append('image',this.profilePictureData);
        form.append('userId',this.storeId);

        axios.post('api/saveStoreProfile',form,config).then(reponse=>{

        });
    }
   },
   mounted: function(){
       this.getStoreProfile();
       this.listenToStoreMessage();
       this.getStoreAccess();
       this.checkSendSubscriptionRequest();
   }
}
</script>
<style scoped>
#MessageButtonContainer {
  position: fixed;
  bottom: -4px;
  right: 10px;
}
#MessageThreadContainer {
  position: fixed;
  bottom: 10px;
  right: 20px;
  width: 600px;
}
.card-header{
    background-color: rgb(65, 65, 66);
    color : white;
}
</style>

