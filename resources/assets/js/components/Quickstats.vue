<template>
    <div class="stats-container">
        <div 
            v-for="stat in stats"
            v-bind:key="stat.id">
            <div class="stat">
                <div class="stat-icon">
                <div class="stat-counter"><i class="fas fa-fire"></i><span>{{ stat.count }}</span></div>
                </div>
                <div class="stat-text">{{ stat.text }}</div>
            </div> 
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                stats: [
                    {
                        id: 1,
                        icon: 'fire',
                        count: 5,
                        text: 'Fires'
                    }, {
                        id: 2,
                        icon: 'vehicular',
                        count: 60,
                        text: 'Vehicular Accidents'   
                    }, {
                        id: 3,
                        icon: 'landslide',
                        count: 7,
                        text: 'Landslides'   
                    }
                ]
            }
        },

        mounted() {
            // get report stats for today (last 24 hours)
            axios.get('/stats/')
                .then(response => {
                    let results = response.data.result;
                    if (results) {
                        results.forEach((el, i) => el.index = i);
                        this.stats = results;
                    }
                })

        }
    }
</script>
