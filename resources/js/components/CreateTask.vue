<template>
<div>
  <h3>Create Task</h3>
  <div class="card" style="width: 40rem; float: center">
    <div class="card-body">
        <div class="alert alert-danger" v-if="errors.length > 0">
          <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
        </div>
        <div class="form-group">
          <label for="names">Name:</label>
          <input
            type="text"
            name="name"
            id="name"
            placeholder="Task Name"
            class="form-control"
            v-model="task.name"
          />
        </div>
        <div class="form-group">
          <label for="description">Description:</label>
          <Editor
            @update="
              (new_content) => {
                task.description = new_content;
              }
            "
          ></Editor>
        </div>
        <button type="button" @click="createTask" class="btn btn-primary">Create</button>
    </div>
  </div>
</div>
</template>
 
<script>
import Editor from "./Editor";
export default {
  components: {
    Editor,
  },
  data() {
    return {
      task: {
        name: "",
        description: "",
      },
      errors: [],
    };
  },
  methods: {
    createTask() {
      if(this.task.description === "<p></p>"){
        this.task.description = '';
      }
      axios
        .post("/task", {
          name: this.task.name,
          description: this.task.description,
        })
        .then((response) => {
          this.reset();
          alert('Task added');
         this.$router.push({ name: "home" });
        })
        .catch((error) => {
          this.errors = [];
          if (error.response.data.errors && error.response.data.errors.name) {
            this.errors.push(error.response.data.errors.name[0]);
          }
          if (
            error.response.data.errors &&
            error.response.data.errors.description
          ) {
            this.errors.push(error.response.data.errors.description[0]);
          }
        });
    },
    reset() {
      this.task.name = "";
      this.task.description = "";
    },
  },
};
</script>