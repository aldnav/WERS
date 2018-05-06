<template>
  <div >

    <div>
        <h3 class="headline mb-0">{{address}}</h3>
        <div>{{reporter}} {{reportDate}}: <br/> {{content}}</div>
        <div>Contact Number: {{contactNumber}}</div>
        <button @click="respond">Respond</button>
    </div>
</div>

</template>

<script>
    export default {
        props:['content','address', 'contactNumber', 
          'reporter','reportDate'],

        data: function() {
            return {
                marker_index:'',
            }
        },

        methods: {
            respond(){
                Bus.$emit('respond_to_report',this.marker_index);
            }
        },
        created(){
            Bus.$on('marker_result_clicked', index=> {
              this.marker_index=index;
            })
        }
    }
</script>
