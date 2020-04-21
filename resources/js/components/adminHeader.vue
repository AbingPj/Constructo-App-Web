<template>
    <div>
    <nav class="navbar navbar-expand-lg" id="navbar">
  <a class="navbar-brand text-center" href="/#/WelcomeAdmin">
        <img :src="'/uploads/corousel/logonavbarfinalna.png'" width="50" height="50"> <span style="color: #fd7e14; font-size: 16px; font-weight: 500;">CONSTRUCTO</span>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

 <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
  <ul class="nav " v-for="admin in admins" v-if="showNavOptions == true">
         <li class="nav-item dropdown">
             <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color : white; cursor : pointer;">
        <img class="circle" style="border-radius: 50%;" src="/uploads/productImages/noprofilepic.jpg" width="40px;" height="40px;">  <span style="text-transform: capitalize; color: white;"> {{admin.firstname}} {{admin.lastname}} </span>
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
           <router-link class="dropdown-item text-left" :to="{ name : 'AdminUsers'}" style="cursor : pointer;"> <i class="fas fa-users"> </i> Users </router-link>
          <div class="dropdown-divider"></div>
           <router-link class="dropdown-item text-left" :to="{ name : 'AdminMyAccount'}" style="cursor : pointer;"> <i class="fas fa-user"> </i> My Account </router-link>
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
             <div class="row">
                 <div class="col-6">
                    <button class="btn form-control"  style="border-radius: 0px; color: white; background-color: #fd7e14 !important;" v-on:click="Logout()"> <i class="fas fa-sign-out-alt"> </i> Yes </button>
                 </div>
                 <div class="col-6">
                         <button class="btn form-control"  style="border-radius: 0px; color: white; background-color: #fd7e14 !important;"  data-dismiss="modal"><i class="fas fa-times-circle"> </i> No </button> <br>
                 </div>
              </div>

      </div>

    </div>
  </div>
</div>
</div>
</template>
<script>
export default {
    data : function (){
        return{
          adminId : localStorage.getItem('id'),
          admins: [],
          showNavOptions : true
        }
    },
    methods : {
        Logout : function(){
            this.showNavOptions = false;
            $('#logoutModal').modal('hide');
      localStorage.removeItem('id');
      this.$router.push('/');
        },
        getAdminProfile : function(){
            let data = {id : this.adminId}
             axios.post('api/getAdminProfile',data).then(response=>{
                 this.admins = response.data;
             });
        }
    },
    mounted: function(){
        this.getAdminProfile();
    }
}
</script>
<style>
#navbar{
    background-color: rgb(65, 65, 66);
}
</style>

