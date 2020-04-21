<template>
    <div class="google-map" :id="mapName">
    </div>
</template>
<script>
var map;
var marker;
export default {
 props: {
   propCustomerAddress : [String],
   propName : [String]
 },
 data : function(){
     return{
        customerAddressToDecode : this.propCustomerAddress+",davao city",
         mapName  : this.propname+"-map",
        davao : {lat : 7.1907, lng : 125.4553},
     }
 },
 methods :{
 initMap : function(){
        const element  = document.getElementById(this.mapName);
          const options ={
            zoom : 10,
            center : this.davao
          }
         map = new google.maps.Map(element,options);

 },
 decodeAddress : function(){
  var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : this.customerAddressToDecode}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       this.createMarker(location);
      }
    });
 },
 createMarker : function(location){
 var marker = new google.maps.Marker({
     map: map,
     position : location,
  });
   marker.addListener('click',function(){
     map.setZoom(17);
     map.setCenter(marker.getPosition());
   });
 }
 },
 mounted : function(){
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

