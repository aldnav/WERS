<template>
<div class="report">
    <div class="row report-body">
        {{ report.report }}
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6 left-col">
            <h6>Nature of Incident</h6>
            <div class="cell-val text-center">{{ report.incident}}</div>
        </div>
        <div class="col-sm-6 right-col">
            <h6>Location</h6>
            <div class="cell-val text-center">{{ report.name }}</div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col text-center">
            <img src="/images/avatar.png" class="rounded-circle avatar">
            <h6>{{ report.reporter }}</h6>
        </div>
        <div class="col text-center contact-div">
            Contact User: <br/>{{report.contact_number||"0948221324"}}
        </div>
    </div>
    <template v-if="resolution_show">
    <hr>
    <div class="row">
        <div class="col">
            <label for="resolution_note">How is this report resolved?</label>
            <textarea class="form-control" name="resolution_note" id="resolution_note" rows="4" v-model="resolution_note"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-pull-right">
            <button type="button" class="btn btn-link" v-on:click="cancelAction"><i class="fas fa-long-arrow-alt-left"></i>&nbsp;Cancel</button>
            <button type="button" class="btn btn-primary" v-on:click="continueAction">Confirm</button>
        </div>
    </div>
    </template>
    <template v-else>
    <hr>
    <div class="row no-gutters">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary" v-on:click="validate(0)">Validate</button>
            <button type="button" class="btn btn-primary" v-on:click="unsafePost('validate')"><small>Validate and Resolve</small></button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-light float-right" v-on:click="unsafePost('reject')">Reject</button>
        </div>
    </div>
    </template>
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
            resolution_note: '',
            resolution_show: false,
            pending_action: null
        }
    },

    methods: {
        getIncidentType: function(id) {
            return window.INCIDENTS[id];
        },

        getLocation: function() {
            return this.report.incident_id;
        },

        // getReporterInfo: function() {
        //     axios.get('/user-reports/user-info/' + this.report.owner_id).then(response=>{
        //         this.owner = response.data
        //     })
        // },

        unsafePost(action) {
            this.resolution_show = true;
            $('#resolution_note').focus();

            this.pending_action = action;
        },

        continueAction() {
            if (this.pending_action == 'validate')
                this.toValidate();
            else if (this.pending_action == 'reject')
                this.toReject();
        },

        cancelAction() {
            this.pending_action = null;
            this.resolution_show = false;
        },

        validate: function(resolve) {
            axios.post('/user-reports/validate/' + this.report.id + '/' + this.report.owner_id + '/' + resolve,
                {
                    resolution_note: this.resolution_note
                })
                .then(response => {
                    this.$swal({text:"Report submitted!", icon:"success", timer:1000});
                    console.log("Result ticket:",response.data.result);
                    Bus.$emit('reports_changed');
                    Bus.$emit('dismiss');
                })
                .catch(response => { console.log(response) });
        },

        toValidate() {
            if (this.resolution_note.length > 0)
                this.validate(1);
        },

        reject() {
            axios.post('/user-reports/reject/' + this.report.id + '/' + this.report.owner_id,
                {
                    resolution_note: this.resolution_note
                })
                .then(response => {
                    this.$swal({text:"Report rejected!", icon:"success", timer:1000});
                    Bus.$emit('dismiss');
                })
                .catch(response => { console.log(response) });
        },

        toReject() {
            if (this.resolution_note.length > 0)
                this.reject();
        }

    }
}
</script>