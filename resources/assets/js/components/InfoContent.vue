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
                marker:[],
            }
        },

        methods: {
            respond(){
                console.log("marker",this.marker);
                Bus.$emit('respond_to_report',this.marker);
            }
        },
        created(){
            Bus.$on('marker_result_clicked', item=> {
              this.marker=item;
            })
        }
    }
</script>
