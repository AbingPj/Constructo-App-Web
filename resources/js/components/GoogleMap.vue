<template>
    <div class="google-map" :id="mapName">
    </div>
</template>
<script>
var StoreSearchMap;
var marker;
export default {
props :{
 propSearchKeyword: [String],
 propCategory: [Number],
 propTerm : [Number],
 propPrice : [Number],
 propStoreAddress : [String],
 propStoreName : [String],
},
data : function(){
    return{
        mapName  : "google-storesearch-map",
        davao : {lat : 7.1907, lng : 125.4553},
        storeAddressToDecode : this.propStoreName+","+this.propStoreAddress+",davao city",
        loadProducts : [],
        searchProductKeyword : '',
        customerId : localStorage.getItem('id'),
    }
},
created: function(){
 this.$eventBus.$on('searchData', (data)=>{
     console.log(data);
    this.decodeAndZoom(data);
 });
},
methods : {
 initMap : function(){
        const element  = document.getElementById(this.mapName);
          const options ={
            zoom : 10,
            center : this.davao
          }
         StoreSearchMap = new google.maps.Map(element,options);

 },
 decodeCustomerAddress : function(address, username){
   var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : address}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       this.createCustomerMarker(location, username);
       console.log(location);
             }
    });
 },
 createCustomerMarker : function(location , username){
     let app = this;
  var marker = new google.maps.Marker({
     map: StoreSearchMap,
     position : location,
    label : {
        text : 'Me',
        color: "white",
    fontSize: "9px",
    fontWeight: "bold"
    }
  });
   marker.addListener('click',function(){
     StoreSearchMap.setZoom(17);
     StoreSearchMap.setCenter(marker.getPosition());
    app.openInfoWindow(marker,"Me")
   });
 },
 decodeAddress : function(address, username){
     console.log(address);
  var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : address}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       this.createMarker(location, username);
       console.log(location);
             }
    });
 },
 createMarker : function(location,username){
     let app = this;
  var marker = new google.maps.Marker({
     map: StoreSearchMap,
     position : location
  });
   marker.addListener('click',function(){
     StoreSearchMap.setZoom(17);
     StoreSearchMap.setCenter(marker.getPosition());
    app.openInfoWindow(marker,username)
   });
 },
 searchProduct: function() {
        let obj = {
          keyword: this.propSearchKeyword,
          category: this.propCategory,
          term: this.propTerm,
          minprice: this.propPrice
        };
        console.log(obj);
        axios.post("api/customersearchProduct", obj).then(response => {
          this.loadProducts = response.data;
          console.log(this.loadProducts);


        for(let x =0 ; x < this.loadProducts.length; x++){

            if(this.loadProducts[x].isViewable == 1 && this.loadProducts[x].isblocked == 0){
           console.log(x);
           console.log(this.loadProducts[x].location);
           this.decodeAddress(this.loadProducts[x].location, this.loadProducts[x].storename);
         }
        }
          this.showLoading = false;
        });



 },
 decodeAndZoom : function(data){
     let address  = data.location;
     let storename = data.storename;
     console.log(address + storename);
   var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : address}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       console.log(location);
        this.createMarker(location,storename);
        StoreSearchMap.setCenter(location);
        StoreSearchMap.setZoom(17);
             }
    });
 },
 openInfoWindow : function(marker,username){
     var content= "<button style='text-transform: capitalize;' class='btn btn-primary' disabled>"+username+"</button> <br>";
      var infowindow = new google.maps.InfoWindow({
            content : content
      });
      infowindow.open(StoreSearchMap,marker);
 },
 getCustomerLocation : function(){
    let data = {customerId : this.customerId};
      axios.post('api/customerGetProfile',data).then( resp => {
          this.decodeCustomerAddress(resp.data[0].address , resp.data[0].firstname+ '' + resp.data[0].lastname);
      })
 }

},

mounted : function(){
this.searchProduct();
this.getCustomerLocation();
     this.initMap();
     this.decodeAddress();
}
}
</script>
<style scoped>
.google-map {
  width: 800px;
  height: 600px;
  margin: 0 auto;
  background: gray;
}

</style>

