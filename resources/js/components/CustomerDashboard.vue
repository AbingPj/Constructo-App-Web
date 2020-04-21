<template>
    <div style="position: relative;">
        <myHeader></myHeader>
            <div class=" container">
                 <myMain > </myMain>
            </div>

         <my-footer style=""></my-footer>


    </div>
</template>
<script>
import CustomerHeader from './CustomerHeader';
import CustomerMain from './CustomerMain';
import myFooter from './myFooter';
export default {

    components : {
       'myHeader': CustomerHeader,
       'myMain' : CustomerMain,
       'my-footer' : myFooter
    }
}
</script>
