<template>
  <div class="background-container">
    <Loading v-if="showLoading == true"></Loading>
    <myHeader></myHeader>
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="row" style="margin-top: 13%;">
            <div class="col-sm-12">
              <h1 style="font-weight: bold; color: white;">Find your ideal construction supply in one application</h1>
            </div>
            <div class="col-12" style="border-top: 1px solid #fd7e14; margin-top: 20px;">
              <p
                style="font-size:24px; margin-top:10px; color: white;"
              >Constructo is an android and web application that lets you find the best construction materials that fits your budget, standards, and efficiency accross different construction stores in Davao City.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-5">
          <div class="card" id="regcard">
            <!-- <div class="card-header" id="banner" style="background-color: #045f1f; color: white;"> <h3 class="display-5 text-center">Sign Up </h3></div> -->
            <div class="card-body">
              <form id="loginForm">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <input  type="text" class="form-control"  id="Firstname" v-model="user.firstname"  placeholder="First name..." >
                      <small v-if="errors.firstname" class="form-text" style="color: #e23f47; font-weight: bold;"> {{errors.firstname.toString()}} </small>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <input type="text" class="form-control" id="Lastname" v-model="user.lastname" placeholder="Last name..." >
                      <small v-if="errors.lastname" class="form-text" style="color: #e23f47; font-weight: bold;"> {{errors.lastname.toString()}} </small>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" id="email"  v-model="user.email" autocomplete="off" placeholder="Email Address...">
                  <small v-if="errors.email" class="form-text" style="color: #e23f47; font-weight: bold;"> {{errors.email.toString()}} </small>
                </div>
                <div class="form-group">
                  <input  type="number"  class="form-control"  id="Phonenumber"  v-model="user.phone"  placeholder="Phone Number..." >
                  <small id="Phonenumber" v-if="!errors.phone"  class="form-text text-muted" >Phone number must be exactly 11 digits.</small>
                  <small v-if="errors.phone" class="form-text" style="color: #e23f47; font-weight: bold;">  {{errors.phone.toString()}} </small>
                </div>
                <div class="form-group">
                  <input  type="password"  class="form-control" id="Password"  v-model="password" placeholder="Password..."  >
                    <small v-if="passwordMatch == false && !errors.password" class="form-text " style="color: #e23f47;"> Password Mismatch </small>
                    <small v-if="errors.password" class="form-text" style="color: #e23f47; font-weight: bold;">  {{errors.password.toString()}} </small>

                </div>
                <div class="form-group">
                  <input type="password" class="form-control" id="Confirmpassword"  placeholder="Confirm Password..."  v-model="confirmpassword" >
                  <small v-if="passwordMatch == true" id="Confirmpassword" class="form-text text-muted">Re-enter your password.</small>
                  <small v-if="passwordMatch == false" class="form-text " style="color: #e23f47; font-weight: bold;"> Password Mismatch </small>
                </div>
                <div class="form-group">
                  <input type="text"  class="form-control"  id="AddressField" ref="customaddress"  autocomplete="off"  placeholder="Enter Address..." >
                  <small id="Address" v-if="user.userType == 3 && !errors.address"   class="form-text text-muted" >Your address will be validated after your registration.</small>
                   <small id="Confirmpassword" v-if="errors.address" class="form-text" style="color: #e23f47; font-weight: bold;"> {{errors.address.toString()}}</small>
                </div>
                <div class="form-group text-center">
                  <div class="form-check form-check-inline">
                    <input type="radio" name="type"  value="2" id="2" v-model="user.userType" autocomplete="off" class="form-check-input" >
                    <label class="form-check-label" for="2">Customer</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="type" value="3" id="3"  v-model="user.userType" autocomplete="off" class="form-check-input">


                    <label class="form-check-label" for="3">Store Owner</label>
                  </div>
                  <small id="Confirmpassword" v-if="!errors.userType"  class="form-text text-muted" >Select what type of user you are.  </small>
                  <small id="Confirmpassword"  v-if="errors.userType" class="form-text" style="color:#e23f47; font-weight: bold; " > {{errors.userType.toString()}}</small>
                </div>
                <hr class="my-4">
                <button
                  type="button"
                  class="btn btn-block"
                  style="border-radius:0px; color:white; background-color: #fd7e14;"
                  v-on:click="Register()"
                  :disabled="!passwordMatch"
                >
                  <i class="fas fa-user-plus"></i> REGISTER
                </button>
                <hr class="my-4">
                <router-link
                  :to="{name : 'LoginPage'}"
                  class="btn form-control"
                  style="border-radius:0px; color:white; background-color: #fd7e14;"
                >
                  <i class="fas fa-sign-in-alt"></i> LOG IN.
                </router-link>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import myHeader from "./myHeader";
import Loading from "./LoadingWhite";

export default {
  components: {
    myHeader: myHeader,
    Loading: Loading
  },

  data: function() {
    return {
      user: {
        firstname: "",
        lastname: "",
        email: "",
        phone: null,
        address: "",
        password: "",
        userType: null,
      },
      password: '',
      address: "",
      errors: [],
      showLoading: false,
      emailExistObject: false,
      phoneExistObject: false,
      confirmpassword : '',
      passwordMatch : true
    };
  },
  watch:  {
      confirmpassword : function(val){
         if(val != this.password){
            this.passwordMatch = false
         }else{
             this.passwordMatch = true;
         }
      },
      password : function(val){
        if(val != this.confirmpassword){
            this.passwordMatch = false;
        }else{
            this.passwordMatch = true;
        }
      }
  },
  methods: {
    GotoAdmin: function() {
      event.preventDefault();
      this.$router.push({ name: "AdminPage" });
    },
    Register: function() {
      let obj = { email: this.user.email };
      let app = this;
      this.showLoading = true;
      setTimeout(() => {
        axios.post("api/emailExist", obj).then(response => {
          if (response.data.length == 0) {
            let obj = { phone: this.user.phone };

            setTimeout(() => {
              axios.post("api/phoneExist", obj).then(response => {
                console.log(response.data.length);
                if (response.data.length == 0) {
                  app.phoneExistObject = false;
                  //    this.user.userType = $("input[type='radio']:checked").val();
                  console.log(this.user.userType);
                  this.showLoading = true;
                  this.user.address = document.getElementById(
                    "AddressField"
                  ).value;
                  let newUser = {
                      'firstname' : this.user.firstname,
                      'lastname' : this.user.lastname,
                      'email' : this.user.email,
                      'phone' : this.user.phone,
                      'address' : this.user.address,
                      'userType' : this.user.userType,
                      'password' : this.password
                  }
                  axios.post("api/CreateAccount", newUser).then(
                    resp => {
                      this.showLoading = false;
                      app.errors = resp.data;

                     let object = { 'name' : this.user.firstname, 'token' : resp.data , 'email' : this.user.email};

                      axios.post('api/emailconf',object).then(resp => {
                            if (resp.data.length == 0) {
                            app.$router.push({name : 'LoginPage'});
                            }
                      });


                    },
                    error => {
                      this.showLoading = false;
                      app.errors = resp.data;

let object = { 'name' : this.user.firstname, 'token' : resp.data, 'email' : this.user.email};

                      axios.post('api/emailconf',object).then(resp => {
                            if (resp.data.length == 0) {
                            app.$router.push({name : 'LoginPage'});
                            }
                      });

                      if (resp.data.length == 0) {
                        app.$router.push({name : 'LoginPage'});
                      }
                    }
                  );
                } else {
                  this.errors = {
                    email: "Phone Number Already Exist"
                  };
                  this.showLoading = false;
                }
              });
            }, 2000);
          } else {
            this.errors = {
              email: "Email Already Exist"
            };
            this.showLoading = false;
          }
        });
      }, 2000);
    },

    addressAutoComplete: function() {
      autocomp = new google.maps.places.Autocomplete(
        document.getElementById("AddressField"),
        { types: ["geocode"] }
      );
    }
  },
  mounted: function() {
    var cityBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(7.055664, 125.55957),
      new google.maps.LatLng(7.055664, 125.55957)
    );
   console.log(" this is cstom addres"+this.customaddress);
    this.customaddress = new google.maps.places.Autocomplete(this.$refs.customaddress, {
      types: ["geocode"],
      componentRestrictions: { country: "ph" },
      bounds: cityBounds
    });
  }
};
</script>
<style scoped>
#navbar {
  box-shadow: 1px 1px 1px 1px #cccccc;
}
#regcard {
  margin-top: 20px;
  right: 0;
  box-shadow: 1px 1px 1px 1px #cccccc;
}
#userType {
  width: 80%;
}
hr {
  margin-top: 30px;
}
.progress {
  height: 30px;
}
.background-container{
    background-image: url('/uploads/corousel/background.jpg');
    height: 800px;
    opacity: 1;
}
</style>


