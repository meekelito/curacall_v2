
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const NOTIFICATION_TYPES = {
    chat: 'App\\Notifications\\MessageNotification',
    case: 'App\\Notifications\\CaseNotification'
};

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('chatnotification', require('./components/ChatNotification.vue'));

const app = new Vue({
    el: '#notificationapp',
    data: {
        notifications: '',
        chatnotifications: '',
    },
    created() {
        this.fetchChatNotifications();
        this.fetchNotifications();
    

        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification((notification) => {
                if(notification.type == NOTIFICATION_TYPES.chat)
                {
                    this.chatnotifications.unshift(notification);
                    $('#message-notif2').addClass('badge-notif');
                    document.getElementById('chatNotificationAudio').play();
                }else{
                    this.notifications.unshift(notification);
                    $('#case-notif2').addClass('badge-notif');

                    try{
                    dt.search('').draw();
                    }catch(err)
                    {
                      console.log(err); 
                    }
                    var playPromise = document.getElementById('caseNotificationAudio').play();

                    // In browsers that don’t yet support this functionality,
                    // playPromise won’t be defined.
                    if (playPromise !== undefined) {
                      playPromise.then(function() {
                        //alert('playback');
                        // Automatic playback started!
                      }).catch(function(error) {
                       // alert(error);
                        // Automatic playback failed.
                        // Show a UI element to let the user manually start playback.
                      });
                    }

                }
            
              console.log(notification.type);
        });
    },
    methods: {
      fetchNotifications() {

        
        axios.post(Laravel.baseUrl +'/notification/get').then(response => {
            this.notifications = response.data;
        });
      },

      fetchChatNotifications() {

        
        axios.post(Laravel.baseUrl +'/notification/chat/get').then(response => {
            this.chatnotifications = response.data;
        });
      },

      readNotifications(message) {
          this.messages.push(message);
          axios.post(Laravel.baseUrl +'/messages', message).then(response => {
             console.log(response.data);
          });
      },
      test(){
          alert('a');
      }
     
    }
});

