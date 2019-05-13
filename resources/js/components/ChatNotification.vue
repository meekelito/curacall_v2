<template>
<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" v-on:click="MarkAllMessageRead()">
            <i class="icon-bubbles4"></i>
            <span class="visible-xs-inline-block position-right">Messages</span>
            <span id="message-notif2" class="bg-warning-400"></span>
          </a>
          
          <div class="dropdown-menu dropdown-content">
            <div class="dropdown-content-heading">
              Messages
              <ul class="icons-list">
                <li><a href="/new-message"><i class="icon-pencil5"></i></a></li>
              </ul>
            </div>

            <ul class="notification-list media-list dropdown-content-body width-350">
               <li v-for="notification in chatnotifications" class="media" v-bind:class="[ notification.is_read == 0 ? 'new': '']">
                <div class="media-left">
                   <img class="img-circle" width="30" v-bind:src="'/storage/uploads/users/' + notification.data.from_image"  alt="">
                </div>

                <div class="media-body">
                 <a v-on:click="MarkAsRead(notification)">{{ notification.data.from_name }}</a> 
                 <div class="text-muted">{{ notification.data.message }}</div>
                  <div class="media-annotation">{{ notification.created_at }}</div>
                </div>
              </li>
            </ul>

            <div class="dropdown-content-footer">
              <a href="#" data-popup="tooltip" title="All Messages"><i class="icon-menu display-block"></i></a>
            </div>
          </div>
        </li>
</template>

<script>
    export default {
        props: ['chatnotifications'],
        methods: {
            MarkAsRead: function(notification) {
                var data = {
                    id: notification.id
                };
                axios.post('/notification/chat/read', data).then(response => {
                   //window.location.href = notification.data.action_url;
                   $.pjax.reload('#content',{ url: notification.data.action_url });
                });
            },
            MarkAllMessageRead: function() {
                axios.post('/notification/chat/read').then(response => {
                   $('#message-notif2').removeClass('badge-notif');
                });
            }
        }
    }
</script>
