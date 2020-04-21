<template>
  <div class="background-container">
    <my-navbar></my-navbar>
    <div class="container">
      <div class="row">
          <!-- carousel -->
        <!-- <div class="col-12 mt-3 justify-content-center" style="border-radius: 10px; width: 100%;">
          <div id="carouselExampleControls" class="carousel slide justify-content-center" data-ride="carousel" >
            <div class="carousel-inner justify-content-center" style="width: 100%;">
              <div class="carousel-item active col">
                <img
                  class="d-block mx-auto"
                  :src="'/uploads/corousel/slide1.png'"
                  style="float:center;width: 100%; height: 400px;"
                  alt="First slide"
                >
              </div>
              <div class="carousel-item">
                <img
                  class="d-block"
                  :src="'/uploads/corousel/slide2.png'"
                  style="float:center; width: 100%; height: 400px;"
                  alt="Second slide"
                >
              </div>
            </div>
            <a
              class="carousel-control-prev"
              href="#carouselExampleControls"
              role="button"
              data-slide="prev"
            >
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div> -->
      </div>
      <div class="row" style="margin-top: 50px;">
          <div class="col-4">
              <div class="card">
                 <div class="card-header h3"> <i class="fas fa-flag-checkered"> </i> One Stop Shop</div>
                  <div class="card-body">
                      <p class="text-justify" style="font-size: 15px;"> Constructo is your one and only application that offers different kinds construction materials across different stores in Davao city. </p>
                  </div>
              </div>
          </div>
          <div class="col-4">
              <div class="card">
                  <div class="card-header h3"> <i class="fas fa-hands-helping"> </i> Efficient Impact  </div>
                  <div class="card-body">
                      <p class="text-justify" style="font-size: 15px;">Helping you build your ideal infrastructure with your desired material price and quality. <br><br> </p>
                  </div>
              </div>
          </div>
          <div class="col-4">
              <div class="card">
                  <div class="card-header h3"> <i class="fas fa-clock"> </i> Real time Notifications </div>
                  <div class="card-body">
                      <p class="text-justify" style="font-size: 15px;"> Deal with any transactions with in a real time basis and recieve orders and messages real time. </p>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import WelcomeNavbar from "./WelcomeNavbar";
export default {
  components: {
    "my-navbar": WelcomeNavbar
  },
  data : function(){
      return{
          featuredProducts : []
      }
  },
  methods: {
      loadFeaturedProducts : function(){
        axios.get('api/getFeaturedProducts').then(response=>{
            this.featuredProducts = response.data;
        })
      },
  },
  mounted : function(){
      this.loadFeaturedProducts();
  }
};
</script>
<style scoped>
.card-header{
    background-color: rgb(65, 65, 66);
    color : white;
    opacity: 0.5;
}
.card{
    opacity: 1;
}
.background-container{
    background-image: url('/uploads/corousel/background.jpg');
    height: 800px;
    opacity: 1;
}
</style>
