<template>
  <div>
  <myHeader></myHeader>
   <div class="container mt-3">
       <div class="card">
           <div class="card-header"> <i class="fas fa-info-circle"> </i> My Account Information </div>
           <div class="card-body">
              <div class="row" v-for="data in adminProfile">
                  <div class="col-6">

                     <div class="card">
                         <img class="card-img-top" :src="'/uploads/productImages/' + data.image">
                         <div class="card-body mt-3">

                             <div class="row">
                                 <div class="col-12"> <input type="file"  class="form-control"> </div>
                                 </div>

                         </div>
                     </div>

                  </div>
                  <div class="col-6">
                       <div class="row mt-3">
                                 <div class="col-6" v-if="isNameEdit == false"> <p style="text-transform: capitalize;"> {{data.firstname}} {{data.lastname}} </p></div>
                                 <div class="col-6" v-if="isNameEdit == true"> <input v-model="data.firstname +' ' + data.lastname"> </div>

                                 <div class="col-6" v-if="isNameEdit == false"> <span @click="isNameEdit = true" style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;"> Edit </span> </div>
                                <div class="col-6" v-if="isNameEdit == true"> <span  style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;"> Save </span>  <span style="margin-left: 10px;color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;" @click="isNameEdit = false"> Cancel </span> </div>

                             </div>
                             <div class="row">
                                 <div class="col-6" v-if="isEmailEdit == false"> <p > {{data.email}} </p> </div>
                                 <div class="col-6" v-if="isEmailEdit == true"> <input v-model="data.email">  </div>

                                 <div class="col-6" v-if="isEmailEdit == false"><span style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;" @click="isEmailEdit = true"> Edit </span> </div>
                                  <div class="col-6" v-if="isEmailEdit == true"> <span  style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;"> Save </span>  <span style="margin-left: 10px;color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;" @click="isEmailEdit = false"> Cancel </span> </div>

                             </div>
                             <div class="row">
                                 <div class="col-6" v-if="isPhoneEdit == false"> <p style="text-transform: capitalize;"> {{data.phone}} </p> </div>
                                 <div class="col-6" v-if="isPhoneEdit == true"> <input v-model="data.phone" type="number">  </div>

                                 <div class="col-6" v-if="isPhoneEdit == false"><span style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;" @click="isPhoneEdit = true"> Edit </span> </div>
                             <div class="col-6" v-if="isPhoneEdit == true"> <span  style="color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;"> Save </span>  <span style="margin-left: 10px;color: rgb(253, 126, 20); cursor: pointer; text-decoration: underline;" @click="isPhoneEdit = false"> Cancel </span> </div>

                             </div>

                             <div class="row">
                                 <div class="col-12"> <button class="btn btn-block" style="border-radius: 0px;"> <i class="fas fa-user-lock"> </i> Change Password  </button> </div>
                             </div>
                  </div>
              </div>
           </div>
       </div>
   </div>
  </div>
</template>

<script>
 import myHeader from './adminHeader';
export default {
components :{
        'myHeader' : myHeader
    },
    data : function(){
        return{
          adminId : localStorage.getItem('id'),
          adminProfile : [],
          isNameEdit : false,
          isEmailEdit: false,
          isPhoneEdit: false,
        }
    },
    methods : {
        getAdminProfile : function(){
            let data = {id : this.adminId}
            axios.post('api/getAdminProfile',data).then( resp => {
              this.adminProfile = resp.data;
            })
        }
    },
    mounted : function(){
        this.getAdminProfile();
    }
}
</script>
