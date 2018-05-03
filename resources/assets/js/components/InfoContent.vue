<template>
  <div >

    <div>
        <h3 class="headline mb-0">{{address}}</h3>
        <div>{{reporter}} {{reportDate}}: <br/> {{content}}</div>
        <div>Contact Number: {{contactNumber}}</div>
    </div>
</div>

</template>

<script>
    export default {
        props:['content','address', 'contactNumber', 
          'reporter','reportDate']
    }
</script>
