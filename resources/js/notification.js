
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
        this.countNotifications();
        this.countChatNotifications();
        this.fetchChatNotifications();
        this.fetchNotifications();
       

        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification((notification) => {

                document.title = document.title + ' (1)';
                var current_url = window.location.pathname + window.location.search;
                console.log(current_url);
                if(notification.type == NOTIFICATION_TYPES.chat)
                {
                    //this.chatnotifications.unshift(notification);
                      $('#message-notif2').addClass('badge-notif');
                      if(notification.data.room_id == $('#room').val()){
                        $('#message-notif2').removeClass('badge-notif');
                        this.MarkAllMessageRead();
                      }
                         

                      this.fetchChatNotifications();
                    
                    document.getElementById('chatNotificationAudio').play();
                }else{
                  //case notifications below

                    //this.notifications.unshift(notification);
                     $('#case-notif2').addClass('badge-notif');
                     this.fetchNotifications();
                    
                    //console.log(window.location.pathname + window.location.search);
                 
                    if(current_url == "/cases/case_id/"+notification.data.case_id){
                      console.log("reload events executed in this page");
                          /** execute events below based on type **/
                          switch(notification.data.type)
                          {
                            case "added_note":
                                try{
                                  dt.search('').draw();
                                }catch(err)
                                {
                                  console.log(err); 
                                }
                            break;
                            case "accept_case":
                                try{
                                   fetchCase();
                                }catch(err)
                                {
                                  console.log(err); 
                                }
                            break;
                            case "forward_case":
                               try{
                                   fetchCase();
                                }catch(err)
                                {
                                  console.log(err); 
                                }
                            break;
                            default:

                          }

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
            
              //console.log(notification.type);
        });
    },
    methods: {
      countNotifications()
      {
         axios.post(Laravel.baseUrl +'/notification/count').then(response => {
           if(response.data > 0)
              $('#case-notif2').addClass('badge-notif');
        });
      },
      countChatNotifications()
      {
         axios.post(Laravel.baseUrl +'/notification/chat/count').then(response => {
           if(response.data > 0)
              $('#message-notif2').addClass('badge-notif');
        });
      },

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

       MarkAllMessageRead(){
            axios.post('/notification/chat/read').then(response => {
               $('#message-notif2').removeClass('badge-notif');
            });
        },
      MarkAllNotificationRead() {
            axios.post('/notification/read').then(response => {
               $('#case-notif2').removeClass('badge-notif');
            });
        },
      test(){
          alert('a');
      }
     
    }
});

