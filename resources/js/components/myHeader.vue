<template>
  <div>
    <nav class="navbar navbar-expand-lg" id="navbar" style="padding-top: 0px; padding-bottom: 0px;">
      <a class="navbar-brand text-center" href="/#/WelcomeBackStore">
        <img :src="'/uploads/corousel/logonavbarfinalna.png'" width="50" height="50"> <span style="color: #fd7e14; font-size: 16px; font-weight: 500;">CONSTRUCTO</span>
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="nav" v-for="store in stores">
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              style="color : white; cursor : pointer;"
            >
              <img
                class="circle"
                style="border-radius: 50%;"
                :src="'/uploads/productImages/'+store.image"
                width="40px;"
                height="40px;"
              >
              <span
                style="text-transform: capitalize;color:white;"
              >{{store.firstname}} {{store.lastname}}</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <router-link
                class="dropdown-item text-left"
                :to="{ name : 'StoreAccount'}"
                style="cursor : pointer;"
              >
                <i class="fas fa-user"></i> My Account
              </router-link>
              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Logout Modal -->
    <div
      class="modal fade"
      id="logoutModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
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
              <button
                class="btn btn-danger"
                style="background-color: #fd7e14; border-radius:0px;"
                v-on:click="Logout()"
              >Yes</button>
              <button
                class="btn btn-success"
                style="background-color: #045f1f; border-radius:0px;"
                data-dismiss="modal"
              >No</button>
              <br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      showNavOptions: true
    };
  },
  data: function() {
    return {
      storeId: localStorage.getItem("id"),
      stores: []
    };
  },
  methods: {
    Logout: function() {
      $("#logoutModal").modal("hide");
      localStorage.removeItem("id");
      this.$router.push("/");
    },
    isDisplayNavOptions: function() {
      if (
        localStorage.getItem("id") == 0 ||
        localStorage.getItem("id") == "0" ||
        !localStorage.getItem("id")
      ) {
        this.showNavOptions = false;
      } else {
        this.showNavOptions = true;
      }
    },
    getStoreProfile: function() {
      let data = { storeId: this.storeId };
      axios.post("api/getStoreAccount", data).then(response => {
        this.stores = response.data;
      });
    }
  },
  mounted: function() {
    this.isDisplayNavOptions();
    this.getStoreProfile();
  }
};
</script>
<style>
#navbar {
  box-shadow: 1px 1px 1px 1px #cccccc;
  background-color: rgb(65, 65, 66) !important;
}
</style>


