<template>
<div>
    <div v-for="report in reports" v-bind:key="report.id">
        <a v-bind:data-id="report.id" v-on:click="reviewReport">{{ report.body }}</a>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            reports: []
        }
    },

    mounted: function() {
        axios.get('/user-reports/').then(response => {
            this.reports = response.data.result;
        });
    },

    methods: {
        reviewReport: function(event) {
            // console.log(event.target.dataset.id);
            let s = event.target.dataset.id;
            Bus.$emit('selectReport', this.reports.filter(r => r.id = s)[0]);
        }
    }
};
</script>