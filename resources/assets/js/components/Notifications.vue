<template>
  <div class="notifications-container">
      <div class="notifications-list">
          <div class="notification-item"
            v-bind:key="notification.id"
            v-bind:data-key="notification.id"
            v-bind:class="{ 'notification-item--unread': !notification.is_read }"
            v-for="notification in unreadNotifications">
            <span><i :class="notification.icon"></i></span>
            <span>{{ notification.template }}</span>
            <span class="read-mark" v-on:click="readNotification(notification.id)"><i class="fas fa-circle"></i></span>
          </div>
          <div v-if="unreadNotifications.length == 0" class="text-center">
              👍 Nothing here yet.
          </div>
      </div>
  </div>
</template>

<script>
    let ICONS = {
        'assigned': 'fas fa-notes-medical af-yellow',
        'resolved': 'fas fa-clipboard-check af-green',
        'responder:nearby': 'fas fa-clipboard-check af-red'
    };

    export default {
        data() {
            return {
                unreadNotifications: [],
            }
        },

        created() {
            $('.modal-mask .panel-body').addClass('p-0');

            this.fetchNotifications('unread');

            Bus.$on('notifications:created', notif => {
                // console.log(notif);
                // notif.icon = this.getIcon(notif);
                // this.setTemplateMessage(notif);
                // this.unreadNotifications.unshift(notif);
                this.fetchNotifications('unread');
            });
        },

        beforeDestroy() {
            $('.modal-mask .panel-body').removeClass('p-0');
        },

        methods: {
            
            fetchNotifications(status) {
                axios.get('/notifications/' + status)
                    .then(response => {
                        let result = response.data.result;
                        result.forEach(o => {
                            o.icon = this.getIcon(o);
                            this.setTemplateMessage(o);
                        });
                        this.unreadNotifications = result;
                        Bus.$emit('reports_changed');//update sidebar
                    });
            },

            readNotification(id) {
                axios.post('/notifications/read/' + id)
                    .then(response => {
                        this.fetchNotifications('unread');
                        Bus.$emit('notifRead', 'yeah');
                    });
            },

            getIcon(notification) {
                return ICONS[notification.action];
            },

            setTemplateMessage(notification) {
                if (notification.action == 'responder:assigned') {
                    notification.template = `You were assigned for report #${notification.object_id}`; 
                } else if (notification.action == 'created') {
                    notification.template = `You submitted report #${notification.object_id}`;
                } else if (notification.action == 'reporter:assigned') {
                    notification.template = `Your report #${notification.object_id} has been validated.`;
                } else if (notification.action == 'reporter:resolved') {
                    notification.template = `Your report #${notification.object_id} has been resolved.`;
                } else if (notification.action == 'reporter:rejected') {
                    notification.template = `Your report #${notification.object_id} has been rejected.`;
                } else if (notification.action == 'responder:nearby') {
                    notification.template = `An incident report #${notification.object_id} is nearby.`;
                }
            }

        }
    }

</script>
