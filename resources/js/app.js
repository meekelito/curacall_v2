
//require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)


Vue.component('chat-messages', require('./components/ChatMessages.vue'));
Vue.component('chat-form', require('./components/ChatForm.vue'));


const app = new Vue({
    el: '#app',
		data: {
      messages: [],
      newMessage: '',
      user: '',
      typing: false
    },

    created() {
      this.fetchMessages(); 
      
      Echo.private('chat')
		  .listen('MessageSent', (e) => {
        var current_chat_room_id = $('#room').val();

        if(e.message.room_id == current_chat_room_id){
            this.messages.push({
              message: e.message.message,
              user: e.user,
              created_at: e.message.created_at
            });
        }
		  });

      let _this = this;

      Echo.private('chat')
      .listenForWhisper('typing', (e) => {
        this.user = e.user;
        this.room = e.room;
        this.typing = e.typing;

        // remove is typing indicator after 0.9s
        setTimeout(function() {
          _this.typing = false
        }, 900);
      });
    
    },

    methods: {
      fetchMessages() {

        const room_id = document.getElementById('room').value;
        axios.get(Laravel.baseUrl +'/messages?room='+room_id).then(response => {
            this.messages = response.data;
        });
      },

      addMessage(message) {
          this.messages.push(message);
          axios.post(Laravel.baseUrl +'/messages', message).then(response => {
            // console.log(response.data);
          });
      },
     
    }
});
