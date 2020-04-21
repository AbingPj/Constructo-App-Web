<template>
<div>
    <nav class="navbar navbar-expand-lg" id="navbar">
         <span class="navbar-brand mb-0 h5"> <router-link v-if="propBrand!=''" :to="{name : 'CustomerDashboard'}" style="text-decoration:none; color: white;" class="h5"> <span style="color: #fd7e14; font-size: 16px; font-weight: 500;"><img :src="'/uploads/corousel/logonavbarfinalna.png'" width="50" height="50">{{propBrand}} </span> </router-link></span>
  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

    <ul class="navbar-nav justify-content-end" v-if="showNavOptions == true">
         <!-- <li class="nav-item mt-1" style="font-size: 22px;cursor: pointer; color:white;">
              <a class="nav-link"><i class="fas fa-bell"> </i></a>
         </li> -->
         <li class="nav-item dropdown ml-3">

        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color : white; cursor : pointer;">
        <img class="circle" style="border-radius: 50%;" :src='"/uploads/productImages/"+customerName.image' width="40px;" height="40px;">  <span style="text-transform: capitalize;color:white;"> {{customerName.firstname}} {{customerName.lastname}} </span>
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">

          <router-link class="dropdown-item text-left" :to="{ name : 'CustomerCart'}" style="cursor : pointer;"><i class="fas fa-shopping-cart"> </i> My Cart</router-link>
          <div class="dropdown-divider"></div>
           <router-link class="dropdown-item text-left" :to="{ name : 'CustomerOrderList'}" style="cursor : pointer;"><i class="fas fa-clipboard-list"> </i> My Orders</router-link>
           <div class="dropdown-divider"></div>
           <router-link class="dropdown-item text-left" :to="{ name : 'CustomerMyAccount'}" style="cursor : pointer;"><i class="fas fa-user"> </i> My Account</router-link>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt"> </i> Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Do you wish to Logout ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             <div class="justify-content-center text-center">
                  <button class="btn " style="background-color:#fd7e14; color:white; border-radius:0px" v-on:click="Logout()"> Yes </button>
                  <button class="btn " style="background-color:#fd7e14; color:white; border-radius:0px" data-dismiss="modal"> No </button> <br>
              </div>

      </div>

    </div>
  </div>
</div>



</div>
</template>
<script>
export default {
    props: {
      propBrand: [String]
    },
    data : function(){
        return{
           customerId : localStorage.getItem('id'),
           customerName : {
               firstname : '',
               lastname : '',
               image: ""
           },
           showNavOptions : true
        }
    },
    methods : {
        getCustomer : function(){
          let obj = {customerId : this.customerId};
          axios.post('api/getCustomerName',obj).then(response =>{
            this.customerName.firstname  = response.data[0].firstname;
             this.customerName.lastname  = response.data[0].lastname;
             this.customerName.image = response.data[0].image;
          });
        },
        Logout : function(){
            $('#logoutModal').modal('hide');
           this.showNavOptions = false;
          localStorage.setItem('id',0);
          this.$router.push('/');
        },

    },
    mounted : function(){
     if(this.customerId == 0){
       this.$router.push({name : 'LoginPage'})
     }else{
      this.getCustomer();
     }
    }
}
</script>
<style scoped>
#navbar{
    background-color:rgb(65, 65, 66);
    color : white;
}
</style>

