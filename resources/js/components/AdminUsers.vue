<template>
    <div>
          <admin-header> </admin-header>
          <loading v-if="showLoading == true"> </loading>
          <div class="container">
            <ul class="nav nav-tabs" role="tablist">
               <li class="nav-item"  @click="isStoreTab = false; isAdminTab = false;">
                   <a class="nav-link active" href="#customer" role="tab" data-toggle="tab"><i class="fas fa-user"></i>Customer <b> {{customers.length}} </b></a>
               </li>
               <li class="nav-item" @click="isStoreTab = true; isAdminTab = false;">
                   <a class="nav-link" href="#store" role="tab" data-toggle="tab" > <i class="fas fa-user-tie"> </i>Store <b>{{stores.length}}</b></a>
              </li>
              <li class="nav-item" @click="isAdminTab =  true;">
                   <a class="nav-link" href="#admin" role="tab" data-toggle="tab"><i class="fas fa-user-secret"></i>Administrator <b>{{admins.length}}</b></a>
              </li>
           </ul>
           <!-- Tab panes -->
              <div class="tab-content">
                <!-- Customer Tab -->
               <div role="tabpanel" class="tab-pane fade show active" id="customer">
                   <div class="row mt-2">
                       <div class="col-7">
                           <div class="input-group">
                              <input type="text" class="input-group-prepend form-control" style="border-radius: 0px" v-model="searchCustomer">
                                 <div class="input-group-prepend">
                                    <div class="input-group-text" style="color: white; background-color: #fd7e14;cursor: pointer;"> <i class="fas fa-search"> </i>  </div>
                                 </div>
                           </div>
                       </div>
                       <div class="col-5">
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-7">
                         <div class="row" v-if="customers.length != 0">
                           <div class="col-12">
                              <div class="row mt-2" style="curson: pointer;">
                                <div class="col"> <strong> Full  Name </strong></div>
                                <div class="col"> <strong> Address </strong></div>
                                <div class="col"> <strong> Email Address </strong></div>
                                <div class="col"> <strong> Phone Number </strong></div>
                                <div class="col"> <strong> Status</strong></div>
                             </div>

                          <div class="row mt-3 border-bottom" style="cursor: pointer;" v-for="customer in customers" v-if="searchCustomer == ''" @click="zoomMap(customer.address, customer.firstname);">
                                <div class="col" style="text-transform: capitalize;">{{customer.firstname}} {{customer.lastname}} </div>
                                <div class="col" style="text-transform: capitalize;">{{customer.address}} </div>
                                <div class="col">{{customer.email}} </div>
                                <div class="col">{{customer.phone}} </div>
                                <div class="col" v-if="customer.isblocked==1" style="color: red;"> Banned </div>
                               <div class="col" v-if="customer.isblocked==0" style="color: green;"> Active </div>
                           </div>

                           <div class="row mt-3 border-bottom" style="cursor: pointer;" v-for="customer in filteredCustomers" v-if="searchCustomer != ''" @click="zoomMap(customer.address, customer.firstname);">
                                <div class="col" style="text-transform: capitalize;">{{customer.firstname}} {{customer.lastname}} </div>
                                <div class="col" style="text-transform: capitalize;">{{customer.address}} </div>
                                <div class="col">{{customer.email}} </div>
                                <div class="col">{{customer.phone}} </div>
                                <div class="col" v-if="customer.isblocked==1" style="color: red;"> Banned </div>
                               <div class="col" v-if="customer.isblocked==0" style="color: green;"> Active </div>
                           </div>

                        </div>
                      </div>
                      <div class="row mt-3" v-if="customers.length == 0">
                          <div class="col-12 text-center"> No customers yet.</div>
                      </div>
                    </div>
                    <div class="col-3" v-if="customers.length != 0">
                       <google-map-customer :propMapname="'customermap'" :propAddressZoom="addressToZoom" ></google-map-customer>
                    </div>
                   </div>
               </div>
                <!-- Store Tab -->
               <div role="tabpanel" class="tab-pane fade" id="store">
                   <div class="row mt-2">
                       <div class="col-7">
                           <div class="input-group">
                              <input type="text" class="input-group-prepend form-control" style="border-radius: 0px" v-model="searchStoreOwner">
                                 <div class="input-group-prepend">
                                    <div class="input-group-text" style="color: white; background-color: #fd7e14;cursor: pointer;"> <i class="fas fa-search"> </i>  </div>
                                 </div>
                           </div>
                       </div>
                       <div class="col-5">
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-7">
                          <div class="row" v-if="stores.length != 0">
                               <div class="col-12">
                                   <div class="row mt-2">
                                   <div class="col"> <strong> Full  Name </strong></div>
                                   <div class="col"> <strong> Address </strong></div>
                                   <div class="col"> <strong> Email Address </strong></div>
                                   <div class="col"> <strong> Phone Number </strong></div>
                                   <div class="col"> </div>
                               </div>
                              <div class="row mt-3 border-bottom" style="cursor: pointer;" v-for="store in stores" v-if="stores.length != 0 && searchStoreOwner == ''" @click="zoomMap(store.address, store.firstname);">
                                    <div class="col" style="text-transform: capitalize;">{{store.firstname}} {{store.lastname}} </div>
                                    <div class="col" style="text-transform: capitalize;">{{store.address}} </div>
                                    <div class="col">{{store.email}} </div>
                                    <div class="col">{{store.phone}} </div>
                               </div>

                               <div class="row mt-3 border-bottom" style="cursor: pointer;" v-for="store in filteredStores" v-if="stores.length != 0 && searchStoreOwner !=''" @click="zoomMap(store.address, store.firstname);">
                                    <div class="col" style="text-transform: capitalize;">{{store.firstname}} {{store.lastname}} </div>
                                    <div class="col" style="text-transform: capitalize;">{{store.address}} </div>
                                    <div class="col">{{store.email}} </div>
                                    <div class="col">{{store.phone}} </div>

                               </div>

                            </div>
                        </div>
                        <div class="row mt-3" v-if="stores.length == 0">
                            <div class="col-12 text-center"> No stores yet.</div>
                        </div>
                    </div>
                    <div class="col-3" v-if="stores.length != 0">
                        <google-map-store :propMapname="'customermap'" :propIsViewStore="1"></google-map-store>
                    </div>
                </div>
               </div>
               <!-- Admin Tab -->
               <div role="tabpanel" class="tab-pane fade" id="admin">
                   <div class="row mt-2">
                       <div class="col-7">
                           <div class="input-group">
                              <input type="text" class="input-group-prepend form-control" style="border-radius: 0px" v-model="searchAdmin">
                                 <div class="input-group-prepend">
                                    <div class="input-group-text" style="color: white; background-color: #fd7e14;cursor: pointer;"> <i class="fas fa-search"> </i>  </div>
                                 </div>
                           </div>
                       </div>
                       <div class="col-5">
                       </div>
                   </div>
                   <div class="row mt-2">
                       <div class="col-8">
                          <div class="row mt-2 border border-primary">
                              <div class="col"> <strong> Full  Name </strong></div>
                              <div class="col"> <strong> Address </strong></div>
                              <div class="col"> <strong> Email Address </strong></div>
                              <div class="col"> <strong> Phone Number </strong></div>
                          </div>

                              <div class="row mt-3 border-bottom" v-for="admin in admins" v-if="searchAdmin == ''">
                              <div class="col" style="text-transform: capitalize;">{{admin.firstname}} {{admin.lastname}} </div>
                              <div class="col" style="text-transform: capitalize;">{{admin.address}} </div>
                              <div class="col">{{admin.email}} </div>
                              <div class="col">{{admin.phone}} </div>
                              </div>

                              <div class="row mt-3 border-bottom" v-for="admin in filteredAdmins" v-if="searchAdmin != ''">
                              <div class="col" style="text-transform: capitalize;">{{admin.firstname}} {{admin.lastname}} </div>
                              <div class="col" style="text-transform: capitalize;">{{admin.address}} </div>
                              <div class="col">{{admin.email}} </div>
                              <div class="col">{{admin.phone}} </div>
                              </div>
                        </div>
                       <div class="col-4">
                           <div class="card">
                               <div class="card-header" style="background-color:rgb(65, 65, 66);color:white;"> <span style="font-size: 20px;"><i class="fas fa-user-plus"> </i> Administrator Registration</span></div>
                           <div class="card-body">
                               <div class="row">
                                   <img class="circle">
                               </div>
                               <div class="row">
                               <div class=" col-6">
                                   <input type="text" placeholder="First Name" class="form-control" v-model="adminForm.firstname">
                               </div>
                               <div class="col-6">
                                   <input type="text" placeholder="Last Name" class="form-control"  v-model="adminForm.lastname">
                               </div>
                               </div>
                               <div class="row mt-2">
                                   <div class="col-12">
                                      <input type="email" placeholder="Email" class="form-control"  v-model="adminForm.email">
                                   </div>
                               </div>
                               <div class="row mt-2">
                                   <div class="col-12">
                                      <input type="number" placeholder="Phone Number" class="form-control"  v-model="adminForm.phone">
                                   </div>
                               </div>
                               <div class="row mt-2">
                                   <div class="col-12">
                                      <input type="number" placeholder="Address" class="form-control"  v-model="adminForm.address">
                                   </div>
                               </div>
                               <div class="row mt-2">
                                   <div class="col-12">
                                      <input type="password" placeholder="Password" class="form-control"  v-model="adminForm.password">
                                   </div>
                               </div>
                               <div class="row mt-2">
                                   <div class="col-12">
                                      <input type="password" placeholder="Confirm Password" class="form-control"  v-model="adminForm.reneterpassword">
                                   </div>
                               </div>
                                <div class="row mt-2">
                                   <div class="col-12">
                                      <button class="btn btn-success form-control" style="border-radius: 0px; background-color:#fd7e14;" @click="registerAdmin();"> <i class="fas fa-user-plus"> </i> Register </button>
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
</template>

<script>
import adminHeader from './adminHeader';
import Loading from './Loading';
import AdminGoogleMapCustomer from './AdminGoogleMapCustomer';
import AdminGoogleMapStore from './AdminGoogleMapStore';
export default {
    components : {
        'admin-header' : adminHeader,
        'loading': Loading,
        'google-map-customer' : AdminGoogleMapCustomer,
        'google-map-store' : AdminGoogleMapStore
    },
    data : function(){
        return{
          customers: [],
          stores: [],
          admins: [],
          adminForm: {
              firstname : '',
              lastname : '',
              email :'',
              password: '',
              reneterpassword: '',
              phone: ''
          },
          showLoading : false,
          isStoreTab : false,
          isAdminTab : false,
          addressToZoom: '',
          searchStoreOwner: '',
          searchCustomer : '',
          searchAdmin : ''
        }
    },
    computed : {
      filteredStores : function(){
       return this.stores.filter(store => {
         return store.firstname.toLowerCase().includes(this.searchStoreOwner.toLowerCase());
       });
      },
      filteredCustomers : function(){
       return this.customers.filter(customer => {
         return customer.firstname.toLowerCase().includes(this.searchCustomer.toLowerCase());
       });
      },
      filteredAdmins : function(){
          return this.admins.filter(admin => {
           return admin.firstname.toLowerCase().includes(this.searchAdmin.toLowerCase());
          });
      }
    },
    methods : {
        zoomMap : function(val,username){
            var data = {address : val, username : username}
            this.$eventBus.$emit('zoom-map', data);
        },
        customerCountfunction : function(){
           axios.get('api/customerCount').then(response=>{
             this.customers = response.data;
           });
        },
        storeCountfunction : function(){
           axios.get('api/storeCount').then(response=>{
             this.stores = response.data;
           });

        },adminCountfunction : function(){
           axios.get('api/adminCount').then(response=>{
             this.admins = response.data;
           });
        },
        registerAdmin: function(){
            this.showLoading = true;
           axios.post('api/registerAdmin',this.adminForm).then(response=>{
               setTimeout(()=>{
                   this.showLoading = false;
                this.adminCountfunction();
               },2000);
           });

        }

    },
    mounted: function(){
        this.customerCountfunction();
        this.storeCountfunction();
        this.adminCountfunction();
    }
}
</script>
<style>
.nav-link{
    color: black !important;
}
.nav-link.active {
  background-color: rgb(65, 65, 66) !important;
  color: white !important;
}
i{
    margin-right: 10px;
}
</style>
