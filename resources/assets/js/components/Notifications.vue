<style >
    .has-notifications {
        color: red;
    }
</style>
<template>
    <a
            href
            class="notifications"
            v-bind:class="{'has-notifications': notifications.length > 0}"
    >
        <span
                title="Notifications"
                class="fa"
                v-bind:class="{'fa-bell-o': notifications.length == 0, 'fa-bell' : notifications.length > 0}"
        >
        </span>
    </a>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },
        ready() {
            setTimeout(() => this.notifications.push(1), 2000);
        }
    }
</script>