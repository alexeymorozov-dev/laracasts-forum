<template>
    <div class="btn-group" v-if="notifications.length">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <i class="fa fa-bell"></i>
        </button>
        <!--        <div class="dropdown-menu dropdown-menu-right" v-for="notification in notifications">-->
        <!--            <button class="dropdown-item" type="button"-->
        <!--               :href="notification.data.link"-->
        <!--               v-text="notification.data.message"-->
        <!--               @click="markAsRead(notification)">-->
        <!--            </button>-->
        <!--        </div>-->

        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="notification in notifications">
                <a class="dropdown-item"
                   :href="notification.data.link"
                   v-text="notification.data.message"
                   @click="markAsRead(notification)"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>

export default {
    data() {
        return {notifications: false}
    },

    created() {
        axios.get('/profiles/' + window.App.user.name + '/notifications')
            .then(response => this.notifications = response.data);
    },

    methods: {
        markAsRead(notification) {
            axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
        }
    }
}

</script>
