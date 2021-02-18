<template>
<div>
  <ul>
    <li>
      <router-link to="/" class="nav-item nav-link">Task List</router-link>
    </li>
    <li>
      <router-link to="/create" class="nav-item nav-link"
        >Create Task</router-link
      >
    </li>

    <li style="float: right">
      <a
        class="active"
        href="#"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        >Logout</a
      >
    </li>
    <li style="float: right; padding-right: 2%">
      <span style="color: white; display: block; padding: 14px 16px"
        >Welcome {{ user.name }}</span
      >
    </li>
  </ul>
  <form id="logout-form" action="logout" method="POST" style="display: none;">
   <input type="hidden" name="_token" :value="csrf">
  </form>
</div>
</template>
<script>
export default {
  data() {
    return {
      user: window.authUser,
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
  },
  methods: {
    submit : function(){
     this.$refs.form.submit();
   }
  }
};
</script>
<style scoped>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover:not(.active) {
  background-color: #111;
}

</style>>