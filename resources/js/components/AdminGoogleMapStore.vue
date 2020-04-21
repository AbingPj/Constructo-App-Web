<template>
    <div class="google-map" :id="mapName">

    </div>
</template>
<script>
var map;
var marker;
export default {
props :{
 propMapname: [String],
 propStoreAddress : [String],
 propUsername : [String],
 propIsViewStore : [Number]
},
created : function(){
this.$eventBus.$on('zoom-map', (data)=>{
    this.decodeAndZoom(data.address, data.username);
 });
},
data : function(){
    return{
        mapName  : "google-store-map",
        davao : {lat : 7.1907, lng : 125.4553},
        storeAddressToDecode : this.propStoreName+","+this.propStoreAddress+",davao city",
        isViewStore : this.propIsViewStore,
        stores : []
    }
},
watch : {
 isViewStore : function(){
     console.log(isViewStore);
 }
},
methods : {
    decodeAndZoom :function(data,username){
     console.log(data);
   var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: data}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
          var location = results[0].geometry.location;

          map.setCenter(location);
          map.setZoom(17);
      }
    });
 },
 initMap : function(){
        const element  = document.getElementById(this.mapName);
          const options ={
            zoom : 10,
            center : this.davao
          }
         map = new google.maps.Map(element,options);

 },
 decodeAddress : function(address,username){
  var geocoder = new google.maps.Geocoder();
    geocoder.geocode({address : address}, (results,status)=>{
      if(status == google.maps.GeocoderStatus.OK){
       var location = results[0].geometry.location;
       this.createMarker(location,username);
      }
    });
 },
 openInfoWindow : function(marker,username){
     var content= "<button style='text-transform: capitalize;' class='btn btn-primary' disabled>"+username+"</button>";
      var infowindow = new google.maps.InfoWindow({
            content : content
      });
      infowindow.open(map,marker);
 },
 createMarker : function(location,username){
     let app = this;
  var marker = new google.maps.Marker({
     map: map,
     position : location
  });
   marker.addListener('click',function(){
     map.setZoom(17);
     map.setCenter(marker.getPosition());
     app.openInfoWindow(marker,username);
   });

 },
 getStores : function(){
     axios.get('api/storeCount').then((response)=>{
       this.stores = response.data;

       this.stores.forEach(element => {
           this.decodeAddress(element.address, element.firstname+" "+element.lastname);
       });
     });

 }

},
mounted : function(){
     this.initMap();
     this.getStores();

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

