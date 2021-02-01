<template>
  <div class="container">
    <div class="row">
      <div class="col-md-11"><h4>Task Dashboard</h4></div>
      <div class="col-md-1">
        <button
          type="button"
          v-if="selected_task != null"
          @click="updateTask"
          class="btn btn-primary"
        >
          Update
        </button>
      </div>
    </div>
    <br />
    <div class="row">
      <div class="col-md-6">
        <table
          class="table table-bordered table-striped"
          v-if="tasks.length > 0"
          id="task"
        >
          <tbody>
            <tr>
              <th>Created On</th>
              <th>Task Title</th>
              <!-- <th>Description</th> -->
              <th>Action</th>
            </tr>
            <tr
              v-for="(task, index) in tasks"
              :key="index"
              @click="taskSelected(task)"
            >
              <td>{{ new Date(task.created_at).toLocaleString() }}</td>
              <td>
                {{ task.name }}
              </td>
              <td>
                <!-- <button
                  @click="initUpdate(index)"
                  class="btn btn-success btn-xs"
                  style="padding: 8px"
                >
                  <span class="glyphicon glyphicon-edit"></span>
                </button> -->

                <button
                  @click="deleteTask(index)"
                  class="btn btn-danger btn-xs"
                  style="padding: 8px"
                >
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6" v-if="selected_task != null">
        <div class="alert alert-danger" v-if="errors.length > 0">
          <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
          </ul>
        </div>
        <input
          type="text"
          name="name"
          id="title"
          class="form-control"
          style="font-size: 20px"
          v-model="update_task.name"
        />
        <Editor
          :parent_content="selected_task.description"
          @update="
            (new_content) => {
              update_task.description = new_content;
            }
          "
        ></Editor>
      </div>
      <h2 v-if="tasks.length == 0">No Tasks created</h2>
    </div>
    <pagination
      v-if="pagination.last_page > 1"
      :pagination="pagination"
      @paginate="
        (selected_page) => {
          pagination.current_page = selected_page;
          readTasks();
        }
      "
    ></pagination>
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
      selected_task: null,
      errors: [],
      tasks: [],
      update_task: {},
      new_name: null,
      pagination: {
        current_page: 1,
      },
    };
  },
  mounted() {
    this.readTasks();
  },
  methods: {
    taskSelected(task) {
      this.selected_task = task;
      this.update_task.name = task.name;
      this.update_task.description = task.description;
    },
    readTasks() {
      axios
        .get("/task?page=" + this.pagination.current_page)
        .then((response) => {
          this.tasks = response.data.data.data;
          this.pagination = response.data.pagination;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    initUpdate(index) {
      this.errors = [];
      $("#update_task_model").modal("show");
      this.update_task.id = this.tasks[index].id;
      this.update_task.name = this.tasks[index].name;
      this.update_task.description = this.tasks[index].description;
    },
    updateTask() {
      if (this.update_task.description === "<p></p>") {
        this.update_task.description = "";
      }
      this.errors = [];
      axios
        .patch("/task/" + this.selected_task.id, {
          name: this.update_task.name,
          description: this.update_task.description,
        })
        .then((response) => {
          this.readTasks();
        })
        .catch((error) => {
          this.errors = [];
          if (error.response.data.errors.name) {
            this.errors.push(error.response.data.errors.name[0]);
          }
          if (error.response.data.errors.description) {
            this.errors.push(error.response.data.errors.description[0]);
          }
        });
    },
    deleteTask(index) {
      let conf = confirm("Do you ready want to delete this task?");
      if (conf === true) {
        axios
          .delete("/task/" + this.tasks[index].id)
          .then((response) => {
            if (this.pagination.current_page > 1 && this.tasks.length == 1) {
              this.pagination.current_page += -1;
              this.readTasks();
            } else {
              this.tasks.splice(index, 1);
            }
          })
          .catch((error) => {});
      }
    },
  },
};
</script>
<style scoped>
table tr:hover {
  cursor: pointer;
}
</style>
