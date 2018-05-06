<template>
  <div class="notifications-container">
      <div class="notifications-list">
          <div class="notification-item"
            v-bind:key="notification.id"
            v-bind:class="{ 'notification-item--unread': !notification.is_read }"
            v-for="notification in unreadNotifications">
            <span><i :class="notification.icon"></i></span>
            <span>{{ notification.template }}</span>
          </div>
      </div>
  </div>
</template>

<script>
    let ICONS = {
        'assigned': 'fas fa-notes-medical af-yellow',
        'resolved': 'fas fa-clipboard-check af-green'
    };

    export default {
        data() {
            return {
                unreadNotifications: [],
            }
        },

        mounted() {
            $('.modal-mask .panel-body').addClass('p-0');

            this.fetchNotifications('unread');
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
                    });
            },

            getIcon(notification) {
                return ICONS[notification.action];
            },

            setTemplateMessage(notification) {
                if (notification.action == 'assigned') {
                    notification.template = `You were assigned a ticket #${notification.object_id}`; 
                } else if (notification.action == 'resolved') {
                    notification.template = `Your report #${notification.object_id} has been resolved.`;
                }
            }

        }
    }

</script>
