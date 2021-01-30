<template>
  <div class="container">
        <div class="panel panel-default">
            <h3>
              <span class="glyphicon glyphicon-dashboard"></span> Assignment
              Dashboard
            </h3>
            <br />
          <div>
            <table
              class="table table-bordered table-striped"
              v-if="tasks.length > 0"
            >
              <tbody>
                <tr>
                  <th>No.</th>
                  <th>Task</th>
                  <th>Description</th>
                  <th>Action</th>
                </tr>
                <tr v-for="(task, index) in tasks" :key="index">
                  <td>{{ (pagination.current_page-1)*5 + index + 1 }}</td>
                  <td>
                    {{ task.name }}
                  </td>
                  <td>
                    {{ task.description }}
                  </td>
                  <td>
                    <button
                      @click="initUpdate(index)"
                      class="btn btn-success btn-xs"
                      style="padding: 8px"
                    >
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>

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
        </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Update Task</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" v-if="errors.length > 0">
              <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
              </ul>
            </div>
            <div class="form-group">
              <label>Name:</label>
              <input
                type="text"
                placeholder="Task Name"
                class="form-control"
                v-model="update_task.name"
              />
            </div>
            <div class="form-group">
              <label for="description">Description:</label>
              <textarea
                cols="30"
                rows="5"
                class="form-control"
                placeholder="Task Description"
                v-model="update_task.description"
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button type="button" @click="updateTask" class="btn btn-primary">
              Submit
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <pagination
      v-if="pagination.last_page > 1"
      :pagination="pagination"
      @paginate="(selected_page)=>{pagination.current_page=selected_page; readTasks()}"
    ></pagination>
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
      tasks: [],
      update_task: {},
      pagination: {
        current_page: 1,
      },
    };
  },
  mounted() {
    this.readTasks();
  },
  methods: {
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
      axios
        .patch("/task/" + this.update_task.id, {
          name: this.update_task.name,
          description: this.update_task.description,
        })
        .then((response) => {
          $("#update_task_model").modal("hide");
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
            this.readTasks();
          })
          .catch((error) => {});
      }
    },
  },
};
</script>
