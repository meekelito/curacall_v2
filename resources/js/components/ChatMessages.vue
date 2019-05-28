<template>
    <ul class="chat media-list chat-list content-group" v-chat-scroll>
        <li v-for="message in messages" v-bind:class="{'media reversed' : (message.user.id == user )}">
          <div v-if="message.user.id == user">
            <div class="media-body"> 
              <div class="media-content">{{ message.message }}</div>
              <span class="media-annotation display-block mt-10">{{ formatTime(message.created_at) }}<a href="#"><i class="icon-pin-alt position-right text-muted"></i></a></span>
            </div>

            <div class="media-right">
              <img class="img-circle img-md" v-bind:src="'../../storage/uploads/users/'+message.user.prof_img">
            </div> 
          </div>

          <div v-else>
            <div class="media-left">
       
              <img class="img-circle img-md" v-bind:src="'../../storage/uploads/users/'+message.user.prof_img">
            </div>

            <div class="media-body">
              <div class="media-content">{{ message.message }}</div>
              <span class="media-annotation display-block mt-10">{{ formatTime(message.created_at) }} <a href="#"><i class="icon-pin-alt position-right text-muted"></i></a></span>
            </div>
          </div>
        </li>
    </ul>
    
</template>

<script>
   
  export default {
    props: ['messages','user'],

    methods:{
        formatTime (time) {
        if(typeof time =='object')
          time = time.date;
       
    
            var local_date= moment.utc(time).local().format('x');

            //let previousTime = moment(time,'YYYY-MM-DD HH:mm:ss').format('x');
            let timeDifference = moment(local_date,'x').fromNow();
            return timeDifference;
        }
    }
  };


</script>