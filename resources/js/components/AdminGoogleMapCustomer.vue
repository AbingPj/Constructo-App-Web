<template>
    <div class="google-map" :id="mapName">

    </div>
</template>
<script>
var customermap;
export default {
props :{
 propAddressZoom : [String] ,
 propStoreAddress :  [Array],
 propUsername : [Array],
 propIsViewStore : [String]
},
props : ['propStoreAddress'],

created: function(){
 this.$eventBus.$on('zoom-map', (data)=>{
    this.decodeAndZoom(data.address, data.username);
 });
},

data : function(){
    return{
        storeAddressToDecode : this.propStoreName+","+this.propStoreAddress+",davao city",
        mapName : "google-customer-map",
        isStore : this.propIsViewStore,
        customers : [],
        addressToZoom : localStorage.getItem('addressToZoom'),
        userNameToZoom : localStorage.getItem('usernameToZoom')
    }
},
methods : {
 decodeAndZoom :function(data,username){
     console.log(data);
   var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: data}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
          var location = results[0].geometry.location;

          customermap.setCenter(location);
          customermap.setZoom(17);
      }
    });
 },
 initMap : function(){
     var latlng = new google.maps.LatLng(7.1907, 125.4553);
        const element  = document.getElementById(this.mapName);
         customermap = new google.maps.Map(element,{
             center : latlng,
             zoom : 10
         });
 },
 decodeAddress : function(customeraddress,username){
  var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : customeraddress}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       this.createMarker(location,username);
      }
    });
 },
 createMarker : function(location,username){
     let app = this;
     var marker = new google.maps.Marker({
     map: customermap,
     position : location
  });
   marker.addListener('click',function(){
     customermap.setZoom(17);
     customermap.setCenter(marker.getPosition());
     app.openInfoWindow(marker,username);
   });

 },
  openInfoWindow : function(marker,username){
     var content= "<button style='text-transform: capitalize;' class='btn btn-primary' disabled>"+username+"</button>";
      var infowindow = new google.maps.InfoWindow({
            content : content
      });
      infowindow.open(customermap,marker);
 },
 getCustomer: function(){
     axios.get('api/customerCount').then(response=>{
        this.customers = response.data;
        this.customers.forEach(element => {
            this.decodeAddress(element.address,element.firstname+" "+element.lastname);
        });
     });
 }


},
mounted : function(){
    this.initMap();
     this.getCustomer();

}
}
</script>
<style scoped>
.google-map {
  width: 500px;
  height: 500px;
  margin: 0 auto;
  background: gray;
}

</style>

