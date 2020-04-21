<template>
  <div class="background-container">
    <myHeader></myHeader>
    <Loading v-if="showLoading == true"></Loading>
    <div class="container">
      <div class="row">
        <div class="col-6">
          <div class="row" style="margin-top: 13%;">
              <div class="col-sm-12">
                    <h1 style="font-weight: bold; color: white;">Find your ideal construction supply in one application</h1>
              </div>
              <div class="col-12" style="border-top: 1px solid #fd7e14; margin-top: 20px;">
                <p style="font-size:24px; margin-top:10px; color: white;"> Constructo is an android and web application that lets you find the best construction materials that fits your budget, standards, and efficiency accross different construction stores in Davao City. </p>
              </div>
          </div>
        </div>
        <div class="col-sm-1"> </div>
        <div class="col-sm-5 justify-content-right">
          <div class="card" id="logincard">

            <div class="card-body">
              <form id="loginForm">
                <ul class="alert alert-danger" v-for="(error, index) in errors" v-if="!accountFound ">
                  <li v-if="accountFounderror && !accountFound ">
                    <small>{{error.toString()}}</small>
                  </li>
                  <li v-else-if="!accountFounderror && !accountFound  ">
                    <small>{{error.toString()}}</small>
                  </li>
                </ul>
                <div class="form-group">
                  <input type="text" class="form-control" id="emailLogin"  style="border-radius:0px;" v-model="user.email" placeholder="Email...">
                </div>
                <div class="form-group">
                  <input type="password" style="border-radius:0px;" placeholder="Password..." class="form-control" id="passwordLogin" v-model="user.password" @keyup.enter="Login()">
                  <small> <router-link to="/"> Forgot Pasword? </router-link></small>
                </div>
                <button type="button" style="border-radius : 0px !important; background-color: #fd7e14; color: white;" class="btn btn-block" id="LoginButton" v-on:click="Login()" v-if="showLoading == false">
                  <i class="fas fa-door-open"></i> LOG IN
                </button>
                <hr class="my-4" style="background-color: #fd7e14;">
                 <div class="row">
                     <div class="col-12">
                         <router-link :to="{ name: 'CreateAccount'}" class="btn form-control" style="border-radius:0px; background-color:#fd7e14; color:white;"> <i class="fab fa-wpforms"> </i> SIGN UP</router-link>
                     </div>
                 </div>
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
      errors: new Object(),
      accountFounderror: true,
      accountFound: false,
      user: {
        email: "",
        password: ""
      },
      showLoading: false
    };
  },
  methods: {
    Login: function() {
      let app = this;
      this.showLoading = true;

      axios.post("api/LoginAccount", this.user).then(response => {
        this.showLoading = false;
        app.errors = response.data;
        if (jQuery.isEmptyObject(app.errors)) {
          app.accountFounderror = false;
          app.errors = {
            Account: ["Account Not Found"]
          };
          app.accountFound = false;
        } else {
          app.accountFounderror = true;

          if (response.data[0].userType == 1) {
            app.accountFound = true;
            localStorage.setItem("id", response.data[0].id);
            app.$router.push({ name: "AdminPage" });
          } else if (response.data[0].userType == 2) {
            app.accountFound = true;
            localStorage.setItem("id", response.data[0].id);
            app.$router.push({ name: "CustomerDashboard" });
          } else if (response.data[0].userType == 3) {
            localStorage.setItem("id", response.data[0].id);
            app.$router.push({ name: "StoreDashboard" });
          }
        }
      });
    }
  },
  mounted: function() {
    let app = this;
    if (localStorage.getItem("id")) {
      let data = { userId: localStorage.getItem("id") };
      axios.post("api/getUserType", data).then(response => {
        if (response.data[0].userType == 1) {
          app.$router.push({ name: "AdminPage" });
        } else if (response.data[0].userType == 2) {
          app.$router.push({ name: "CustomerMain" });
        } else if (response.data[0].userType == 3) {
          app.$router.push({ name: "StoreDashboard" });
        }
      });
    }
  }
};
</script>
<style>
#navbar {
  box-shadow: 1px 1px 1px 1px #cccccc;
}

#logincard {
  margin-top: 80px;
  right: 0;
  box-shadow: 1px 1px 1px 1px #cccccc;
}
#LoginButton {
  margin-top: 20px;
}
#forgoutyourpassword-link {
  margin-top: 15px;
}
hr {
  margin-top: 40px;
}
#overlay {
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 2;
  cursor: pointer;
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


