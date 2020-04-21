<template>

  <footer class="mainfooter" role="contentinfo" style="margin-top:20%;">
  <div class="footer-top p-y-2">
      <div class="container-fluid">
          <div class="row">
              <div class="col-12 mb-5">
                 <div class="footer-title text-center mt-2">
                       <h2 class="p-b-1">Welcome to Constructo</h2>
                  </div>
              </div>
              <div class="col-4 ">
                  <div class="footer-pad">
                    <h4 class="mr-4" style="color:lightblue;">Going Online</h4>
                      <p class="text-justify mt-3 mr-4">
                  Having your business posted online helps
                 you gain more popularity which can help you gain customers
                 and eventually gain you more revenue Start investing your store online now
                     </p>
                  </div>
              </div>
               <div class="col-4">
                  <div class="footer-pad">
                    <h4 class="ml-4" style="color:lightblue;">User Friendly</h4>
                      <p class="text-justify mt-3 ml-4">Constructo is built with user friendly interface that helps you from navigating through different components smoothly, also helps you to traverse your business with less learning curve
                     </p>
                  </div>
              </div>
               <div class="col-4">
                  <div class="footer-pad">
                    <h4 class="ml-4" style="color:lightblue;">We are Innovative</h4>
                      <p class="text-justify mt-3 ml-4"> Constructo is built with modern web technologies such as Vue.js and Laravel Framework , which provides you better experience in using the applciation.
                     </p>
                  </div>
              </div>
          </div>

          </div>
      </div>
<div class="footer-middle">
<div class="container">
  <div class="row">
    <div class="col-md-4 col-sm-6">
      <!--Column1-->
      <div class="footer-pad">
        <h4 style="color:lightblue;">Address</h4>
          <address>
            <ul class="list-unstyled">
              <li>University of Mindanao<br>Matina Mc Arthur Highway <br>Davao City <br>Phillipines<br></li>
            </ul>
         </address>
      </div>

    </div>
    <div class="col-md-4 col-sm-6">
      <!--Column1-->
      <div class="footer-pad">
        <h4 style="color:lightblue;">Our Services</h4>
        <ul class="list-unstyled">
          <li><a href="#"></a></li>
          <li><a href="#">Payment Center</a></li>
          <li><a href="#">Contact Directory</a></li>
          <li><a href="#">Forms</a></li>
          <li><a href="#">News and Updates</a></li>
          <li><a href="#">FAQs</a></li>
        </ul>
      </div>
    </div>
    <div class="col-md-4 col-sm-6">
      <!--Column1-->
      <div class="footer-pad">
        <h4 style="color:lightblue;">Meet us in Social</h4>
        <ul class="list-unstyled">
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Google +</a></li>
          <li><a href="#">Email</a></li>
          <li><a href="#">Twitter</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<div class="footer-bottom">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <!--Footer Bottom-->
        <p class="text-center">&copy; Copyright 2018 - City of Davao, Phillipines.  All rights reserved.</p>
      </div>
    </div>
  </div>
</div>
</footer>
</template>
<script>
export default {

}
</script>
<style>
footer{
    color: white;
  }
  footer a{
    color: #bfffff;
  }
  footer a:hover{
    color: white;
  }

  .footer-bottom{
    background: #6a8392;
    padding: 2em;
  }
  .footer-top{
    background: #2d4958;
  }
  .footer-middle{
     background: #3d6277;
    padding-top: 2em;
    color: white;
  }
  /**Sub Navigation**/
  .subnavigation-container{
    background: #3d6277;
  }
  .subnavigation .nav-link{
    color: white;
    font-weight: bold;
  }
  .subnavigation-container{
    text-align: center;
  }
  .subnavigation-container .navbar{
    display: inline-block;
    margin-bottom: -6px; /* Inline-block margin offffset HACK -Gilron */
  }
  .col-subnav a{
    padding: 1rem 1rem;
    color: white;
    font-weight: bold;
  }
  .col-subnav .active{
    border-top:5px solid orange;
   background: white;
    color: black;
  }
</style>


