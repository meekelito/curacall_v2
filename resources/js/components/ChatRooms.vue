<template>
	<ul>
    <li v-for="room in rooms" >{{ room }}</li>
  </ul>
</template>

<script>
   
  export default {
    props: ['rooms','user']
  };


</script>