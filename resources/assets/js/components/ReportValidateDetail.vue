<template>
<div class="report">
    <div class="row report-body">
        {{ report.body }}
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6 left-col">
            <h6>Nature of Incident</h6>
            <div class="cell-val text-center">{{ getIncidentType(report.incident_id) }}</div>
        </div>
        <div class="col-sm-6 right-col">
            <h6>Location</h6>
            <div class="cell-val text-center">{{ getLocation() }}</div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col text-center">
            <img src="/images/avatar.png" class="rounded-circle avatar">
            <h6>{{ owner.name }}</h6>
        </div>
        <div class="col text-center contact-div">
            <button type="button" class="btn btn-success">Contact User</button>
        </div>
    </div>
    <hr>
    <div class="row no-gutters">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary" v-on:click="validate(0)">Validate</button>
            <button type="button" class="btn btn-primary" v-on:click="validate(1)"><small>Validate and Resolve</small></button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-light float-right">Reject</button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props:  {
        report: null
    },

    data() {
        return {
            owner: {
                name: '',
                avatar: ''
            },
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },

    methods: {
        getIncidentType: function(id) {
            return window.INCIDENTS[id];
        },

        getLocation: function() {
            return this.report.incident_id;
        },

        getReporterInfo: function() {
            axios.get('/user-reports/user-info/' + this.report.owner_id).then(response=>{
                console.log(response);
                this.owner = response.data
            })
        },

        validate: function(resolve) {
            axios.post('/user-reports/validate/' + this.report.id + '/' + this.report.owner_id + '/' + resolve)
                .then(response => { console.log(response) })
                .catch(response => { console.log(response) });
        }

    },

    mounted() {
        this.getReporterInfo();
    }
}
</script>