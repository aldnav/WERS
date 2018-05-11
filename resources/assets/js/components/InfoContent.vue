<template>
  <div >

    <div v-if="isResponder">
        <h2 class="headline mb-0">{{address}}</h2>
        <div>{{reporter}} {{reportDate}}: <br/> {{content}}</div>
        <div>Contact Number: {{contactNumber}}</div>
        <button @click="respond">Respond</button>
    </div>
    <div v-else>
        <h3 class="headline mb-0">{{address}}</h3>
        <div>{{reportDate}}: <br/> {{content}} <br/>Responder: {{responder}} <br/> Contact Number: {{contactNumber}}</div>    
    </div>
</div>

</template>

<script>
    export default {
        props:['content','address', 'contactNumber', 
          'reporter','reportDate','show','responder','isResponder'],

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
