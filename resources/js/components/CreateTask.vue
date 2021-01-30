<template>
  <div class="card" style="width: 40rem; float: center">
    <div class="card-body">
      <h3 class="card-title">Create Task</h3>
      <form @submit.prevent="createTask">
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
          <textarea
            name="description"
            id="description"
            cols="30"
            rows="5"
            class="form-control"
            placeholder="Task Description"
            v-model="task.description"
          ></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
      </form>
    </div>
  </div>
</template>
 
<script>
export default {
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