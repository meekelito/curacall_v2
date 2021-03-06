
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
    case: 'App\\Notifications\\CaseNotification',
    reminders: 'App\\Notifications\\ReminderNotification',
};

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('chatnotification', require('./components/ChatNotification.vue'));
Vue.component('remindernotification', require('./components/ReminderNotification.vue'));

window.app = new Vue({
    el: '#notificationapp',
    data: {
        notifications: '',
        chatnotifications: '',
        remindernotifications: '',
        all_count:0,
        case_count:0,
        chat_count:0,
        reminder_count:0,
        favicon: ''
    },
    created() {

            Push.Permission.request();
    
       

        this.countNotifications('case');
        this.countNotifications('chat');
        // this.fetchChatNotifications();
        // this.fetchNotifications();
        //this.fetchReminderNotifications();
        this.fetchDesktopNotifications('case');
        this.fetchDesktopNotifications('reminder');
        this.fetchDesktopNotifications('chat');
        this.countNotifications('reminder');
       
        // var notification_count = 0;
                this.favicon = new Favico({
                    animation : 'popFade',
                    position : 'up'
                });
                


        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification((notification) => {

                var current_url = window.location.pathname + window.location.search;
                console.log(current_url);
                if(notification.type == NOTIFICATION_TYPES.chat)
                {
                      if(notification.data.room_id != $('#room').val()){
                        this.countNotifications('chat');
                        this.fetchNotifications('chat');
                      }else
                        this.MarkChatAsRead(notification.id);

                    
                    document.getElementById('chatNotificationAudio').play();
                    console.log(notification);
                    this.doNotification(notification.id,'Hey! a new chat notification for you',notification.data.from_name + ": " +notification.data.message,notification.data.action_url,'chat',notification.prof_img);
                }else if(notification.type == NOTIFICATION_TYPES.reminders){

                     var playPromise = document.getElementById('reminderNotificationAudio').play();

                    // var playPromise = document.getElementById('reminderNotificationAudio').play();

                      this.countNotifications('reminder');
                      this.fetchNotifications('reminder');
                      this.doNotification(notification.id,'Hey! a new reminder notification for you',notification.data.message,notification.data.action_url,'reminder');
                }else if(notification.type == NOTIFICATION_TYPES.case){
                  //case notifications below
                    var self = this;
                    //this.notifications.unshift(notification);
                    this.countNotifications('case');
                    this.fetchNotifications('case');
                    this.doNotification(notification.id,'Hey! a new case notification for you',notification.data.message,notification.data.action_url,'case',notification.prof_img);
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
                            case "pull_case":
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
                     
                     if(current_url == "/all-cases" || current_url == "/active-cases" || current_url == "/pending-cases" || current_url == "/closed-cases" || current_url == "/silent-cases"){
                       $.pjax.reload('#content',{ url: window.location.href });
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
      doNotification (id,title,body,url,tag = 'case',icon = null) {
        if(icon == null)
          icon = Laravel.baseUrl + "/assets/images/curacall_logo.jpg";

        var self = this;
            Push.create(title, {
              body: body,
              icon: icon,
              timeout: 60000,        
                onClick: function () {
                  //NotificationMarkAsRead(id);
                  var notification = { id : id, data : { message: body, action_url : url },is_read: 0};
                  if(tag == 'case')
                    self.$refs.notification.MarkAsRead(notification);
                  else if(tag == 'reminder')
                     self.$refs.remindernotification.MarkAsRead(notification);
                  else if(tag == 'chat')
                     self.$refs.chatnotification.MarkAsRead(notification);
                  //$.pjax.reload('#content',{ url: url });
            },
              vibrate: [100, 100, 100],   
          });

        },
      countNotifications(type)
      {
         axios.post(Laravel.baseUrl +'/notification/count',{ type: type}).then(response => {
           if(response.data > 0){
              $('#'+ type +'-notif').addClass('badge-notif');
              $('#'+ type +'-notif').html(response.data);
           }else
           {
              $('#'+ type +'-notif').removeClass('badge-notif');
              $('#'+ type +'-notif').html('');
   
           }

           if(type == 'case')
            this.case_count = response.data;
          
           if(type == 'chat')
              this.chat_count = response.data;
          
           if (type == 'reminder')
              this.reminder_count = response.data;

           this.notificationTitle();
        });
      },
      fetchNotifications(type) {  
        axios.post(Laravel.baseUrl +'/notification/get',{ type: type}).then(response => {
           if(type == 'case')
            this.notifications = response.data;
          
           if(type == 'chat')
              this.chatnotifications = response.data;
          
           if (type == 'reminder')
              this.remindernotifications = response.data;

        });
      },
       fetchDesktopNotifications(type) {  
        var self = this;
 
        axios.post(Laravel.baseUrl +'/notification/get',{ type: type}).then(response => {
          var desktop_alert = [];
          $.each(response.data, function(key, value) {
              
              if(value.is_read != 1){
                desktop_alert.push(value);
              }
         });
           
           if(type == 'case'){
               this.notifications = response.data;
           }
           
          
           if(type == 'chat'){
              this.chatnotifications = response.data;
           }
          
           if (type == 'reminder'){
              this.remindernotifications = response.data;
           }

            var time = 500;
            $.each(desktop_alert, function(key, value) {
                  console.log(value.id);
      
                 setTimeout(function() {
                    if(type != 'chat')
                     self.doNotification(value.id,'Hey! a new '+ type +' notification for you',value.data.message,value.data.action_url,type,value.prof_img);
                   else
                     self.doNotification(value.id,'Hey! a new '+ type +' notification for you',value.data.from_name + ": " +value.data.message,value.data.action_url,type,value.prof_img);
                }, time);
                 time += 500;
            });

        });
      },
      MarkChatAsRead(id){
        axios.post('/notification/read', { id : id }).then(response => {
                this.fetchNotifications('chat');
        });
      },
      notificationTitle(){
          this.all_count = this.case_count + this.chat_count + this.reminder_count;
          
          this.favicon.badge(this.all_count);
      }
     
    }
});

