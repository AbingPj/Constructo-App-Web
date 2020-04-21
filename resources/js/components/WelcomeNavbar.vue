<template>
  <div>
    <nav class="navbar navbar-expand-lg" id="navbar" style="padding-top: 0px; padding-bottom: 0px;">
      <a class="navbar-brand">
        <img :src="'/uploads/corousel/logonavbarfinalna.png'" width="50" height="50">
        <span style="color: #fd7e14; font-size: 16px; font-weight: 500;">CONSTRUCTO</span>
      </a>

      <div class="collapse navbar-collapse justify-content-end">
          <div style="border-right : 1px solid #fd7e14;">
              <router-link :to="{name : 'LoginPage'}" class="nav-link"> <span style="color: #fd7e14; font-size: 16px; font-weight: 500;">LOG IN</span> </router-link>
          </div>
              <router-link :to="{name : 'CreateAccount'}" class="nav-link" ><span style="color: #fd7e14; font-size: 16px; font-weight: 500;"> SIGN UP</span> </router-link>
      </div>
    </nav>
  </div>
</template>

<script>
export default {};
</script>
<style>
#navbar {
  box-shadow: 1px 1px 1px 1px #cccccc;
  background-color: rgb(65, 65, 66) !important;
}
</style>
