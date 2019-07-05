<template>
    <div>
        <textarea name="message" id="btn-input" class="form-control content-group" rows="3" cols="1" placeholder="Type your message here..." v-model="newMessage"  @keyup.enter="sendMessage" @keydown="isTyping(room_id)" ></textarea>

        <div class="row">
          <div class="col-xs-6">
            <ul class="icons-list icons-list-extended mt-10">
              <li><a v-on:click="selectFile()" data-popup="tooltip" title="Send photo" data-container="body"><i class="icon-file-picture"></i></a></li>
              <li><a v-on:click="selectFile()" data-popup="tooltip" title="Send video" data-container="body"><i class="icon-file-video"></i></a></li>
              <li><a v-on:click="selectFile()" data-popup="tooltip" title="Send file" data-container="body"><i class="icon-file-plus"></i></a></li>
              <li><a v-on:click="selectFile()" data-popup="tooltip" title="Mark as urgent" data-container="body"><i class="icon-bubble-notification"></i></a></li>
            </ul>
          </div>
          <input type="file" id="files" ref="files" multiple class="hidden"/>
          <div class="col-xs-6 text-right">
            <button class="btn bg-teal-400 btn-labeled btn-labeled-right" id="btn-chat" @click="sendMessage"><b><i class="icon-circle-right2"></i></b> Send</button>
          </div>
        </div>
    </div>


</template>

<script>
    export default {
        props: ['user','room_id'],

        data() {
            return {
              newMessage: ''
          }
        },

        methods: {
          sendMessage() {
            let time = moment().format('YYYY-MM-DD HH:mm:ss');
              this.$emit('messagesent', {
                  user: this.user,
                  room_id: this.room_id,
                  message: this.newMessage
              });

              this.newMessage = '';
          },
          isTyping(room) {
            let channel = Echo.private('chat');

            setTimeout(function() {
              channel.whisper('typing', {
                user: Laravel.user,
                room: room,
                typing: true
              });
            }, 300);
          },
          selectFile()
          {
            $('#files').click();
          }
        }    
    }
</script>
