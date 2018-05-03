<template>
    <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
        <!-- <div
            v-for="element in elements"
            v-bind:key="element.id">
            <h6>{{ element.text }}</h6>
        </div> -->
        <div 
            v-for="(item,i) in results">
<!--             <h6 v-if="item.text==null"
                :key="i" 
                @click="focusMarker(i)"
                style="text-decoration: underline">
                {{item.latlng}}</h6>
 -->            <h6 :key="i"
                @click="focusMarker(i)"
                style="text-decoration: underline">{{item.incident}}</h6>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                elements: [
                    {
                        id: 1,
                        icon: 'fire',
                        incidentType: 'fire',
                        location: [123.00, 56.00],
                        text: 'Olango Island'
                    }, {
                        id: 2,
                        icon: 'fire',
                        incidentType: 'fire',
                        location: [123.00, 56.00],
                        text: 'Maribago Mactan'    
                    }
                ],
                results:[]
            }
        },

        methods: {
            focusMarker(index){
                Bus.$emit('marker_result_clicked',index);
                console.log(index);
            }
        },

        created(){
            Bus.$on('reports_fetched',data=>{
                this.results=data.results;
                console.log('event data',data);
            })

        }
    }
</script>
