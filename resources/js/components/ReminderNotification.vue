<template>
<li class="dropdown" id="reminder-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="reminder-dropdown">
            <i class="icon-alarm"></i>
            <span class="visible-xs-inline-block position-right">Reminders</span>
            <span id="reminder-notif" class="bg-warning-400"></span>
          </a>
          
          <div class="dropdown-menu dropdown-content">
            <div class="dropdown-content-heading">
              Reminders
              <ul class="icons-list">
                <li><a v-on:click="fetchNotifications('reminder')"><i class="icon-sync"></i></a></li>
              </ul>
            </div>

            <ul class="notification-list media-list dropdown-content-body width-350">
               <li v-for="notification in remindernotifications" class="media" v-bind:class="[ notification.is_read == 0 ? 'new': '']">
                <div class="media-left">
                   <img class="img-circle" width="30" v-bind:src="notification.prof_img"  alt="">
                </div>

                <div class="media-body">
                 <a v-on:click="MarkAsRead(notification)">
                 <div class="text-muted">{{ notification.data.message }}</div>
                  <div class="media-annotation"><span v-bind:class="[ notification.is_read == 0 ? 'badge-notif': '']"></span> {{ notification.created_at }}</div>
                  </a> 
                </div>
              </li>
            </ul>

            <div class="dropdown-content-footer">
              <a href="#" data-popup="tooltip" title="All Reminders"><i class="icon-menu display-block"></i></a>
            </div>
          </div>
        </li>
</template>

<script>
    export default {
        props: ['remindernotifications'],
        methods: {
            MarkAsRead: function(notification) {
                var data = {
                    id: notification.id
                };
                 axios.post(Laravel.baseUrl +'/notification/read', data).then(response => {
                    //window.location.href = notification.data.action_url;
                    $.pjax.reload('#content',{ url: notification.data.action_url });
                    
                    if(notification.is_read == 0){
                        this.countNotifications('reminder');
                        this.fetchNotifications('reminder');
                        this.notificationTitle();
                    }
                  
                });
            },
            notificationTitle(){
              this.$parent.notificationTitle();
            },
            countNotifications(type){
              this.$parent.countNotifications(type);
            },
            fetchNotifications(type){
              this.$parent.fetchNotifications(type);
            }

        },
        mounted(){

         $('#reminder-dropdown').click(function () {
              if(!$(this).hasClass('open')) {
                  window.app.fetchNotifications('reminder')
              }
          });

        }
    }
</script>
