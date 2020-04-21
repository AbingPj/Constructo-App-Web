<template>
<div>
    <customer-header propBrand="onstructo"> </customer-header>

   <div class="container">
     <div class="row mt-3">
         <div class="col-4">
             <div class="card">
                 <div class="card-header"> <i class="fas fa-comments"> </i> Message Heads </div>
                 <div class="card-body">
                     <ul class="list-group" v-if="messageHeads.length != 0">
                         <li class="list-group-item">Lloyd</li>
                         <li class="list-group-item"></li>
                     </ul>
                     <ul class="list-group" v-if="messageHeads.length == 0">
                         <li class="list-group-item"> No Messages </li>
                     </ul>
                 </div>
             </div>
         </div>
         <div class="col-8">
             <div class="card" v-if="messageHeads.length != 0">
                 <div class="card-header h3">Handy Boy </div>
                 <div class="card-body">
                 </div>
             </div>

             <div class="card" v-if="messageHeads.length == 0">
                 <div class="card-header"> <i class="fab fa-cuttlefish"> </i>onstructo </div>
                 <div class="card-body">
                     <ul class="list-group">
                         <li class="list-group-item"> No messages as of the moment.</li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
   </div>
</div>
</template>

<script>
  import CustomerHeader  from './CustomerHeader';

export default {
  components :{
      'customer-header' : CustomerHeader
  },
  data : function(){
      return{
          messageHeads : []
      }
  }
}
</script>
<style scoped>
.card-header{
    background-color: rgb(65, 65, 66);
    color : white;
    font-size: 16px;
}
</style>
