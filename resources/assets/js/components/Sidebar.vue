<template>
    <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
        <div
            v-for="element in elements"
            v-bind:key="element.id">
            <h6>{{ element.text }}</h6>
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
                ]
            }
        }
    }
</script>
