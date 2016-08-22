<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Tasks <small>({{ tasks.length }})</small></span>

                    <a href class="btn btn-primary btn-sm" @click.prevent="showCreateTaskForm()">
                        <i class="fa fa-plus" role="button" title="New task"></i>
                    </a>
                </div>
            </div>


            <div class="panel-body">
                <div v-if="tasks.length == 0">
                    You have no tasks for today.
                </div>
                <div v-if="tasks.length > 0">
                    <table class="table table-borderless m-b-none">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th colspan="2"></th>
                        </thead>

                        <tbody>
                        <tr v-for="task in tasks">
                            <!-- ID -->
                            <td style="vertical-align: middle;">
                                {{ task.id }}
                            </td>

                            <!-- Name -->
                            <td style="vertical-align: middle;">
                                {{ task.name }}
                            </td>

                            <!-- Edit Button -->
                            <td style="vertical-align: middle;">
                                <a class="action-link" href @click.prevent="edit(task)">
                                    Edit
                                </a>
                            </td>

                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                <a class="action-link text-danger" href @click.prevent="destroy(task)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-create-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Create Task
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="newTaskForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in newTaskForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form class="form-horizontal" role="form" @submit.prevent>
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>

                                <div class="col-md-7">
                                    <input
                                            id="create-task-name"
                                            type="text"
                                            class="form-control"
                                            @keyup.enter="storeTask"
                                            v-model="newTaskForm.task.name"
                                            placeholder="Be kind to someone."
                                    />
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Description</label>

                                <div class="col-md-7">
                                    <textarea
                                            class="form-control"
                                            @keyup.enter="storeTask"
                                            v-model="newTaskForm.task.description"
                                            placeholder="Description"
                                    >
                                    </textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click.prevent="storeTask">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-edit-task" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">
                            Edit Task
                        </h4>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editTaskForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editTaskForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form class="form-horizontal" role="form" @submit.prevent>
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>

                                <div class="col-md-7">
                                    <input
                                            id="edit-task-name"
                                            type="text"
                                            class="form-control"
                                            @keyup.enter="updateTask"
                                            v-model="editTaskForm.task.name"
                                            placeholder="Be kind to someone."
                                    />
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Description</label>

                                <div class="col-md-7">
                                    <textarea
                                            class="form-control"
                                            @keyup.enter="updateTask"
                                            v-model="editTaskForm.task.description"
                                            placeholder="Description"
                                    >
                                    </textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click.prevent="updateTask">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
        data() {
            return {
                tasks: [],
                newTaskForm: {
                    task: this.emptyTask(),
                    errors: [],
                },
                editTaskForm: {
                    task: this.emptyTask(),
                    errors: [],
                }
            }
        },
        ready() {
            this.loadTasks();
        },
        methods: {
            loadTasks() {
                this.$http.get('/api/tasks').then(({data}) => {
                    this.tasks = data;
                });
            },
            showCreateTaskForm() {
                $('#modal-create-task').modal('show');
            },
            hideCreateTaskForm() {
                $('#modal-create-task').modal('hide');
            },
            showEditTaskForm() {
                $('#modal-edit-task').modal('show');
            },
            hideEditForm() {
                $('#modal-edit-task').modal('hide');
            },
            storeTask() {
                this.newTaskForm.errors = [];
                const conn = this.$http.post('/api/tasks', this.newTaskForm.task);
                conn.then(() => {
                   this.loadTasks();
                   this.newTaskForm = {
                       errors: [],
                       task: this.emptyTask(),
                   };
                   this.hideCreateTaskForm();
                });

                conn.catch((response) => {
                    if (typeof response.data === 'object') {
                        this.newTaskForm.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.newTaskForm.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            edit(task) {
                this.editTaskForm = {
                    task: Object.assign({}, task),
                    errors: [],
                };
                this.showEditTaskForm();
            },
            updateTask() {
                this.editTaskForm.errors = [];
                const conn = this.$http.patch(`/api/tasks/${this.editTaskForm.task.id}`, this.editTaskForm.task);

                conn.then(() => {
                    this.loadTasks();
                    this.editTaskForm = {
                        errors: [],
                        task: this.emptyTask(),
                    };
                    this.hideEditForm();
                });

                conn.catch((response) => {
                    if (typeof response.data === 'object') {
                        this.editTaskForm.errors = _.flatten(_.toArray(response.data));
                    } else {
                        this.editTaskForm.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },
            destroy(task) {
                this.$http.delete(`/api/tasks/${task.id}`).then(() => {
                    this.loadTasks();
                });
            },
            emptyTask() {
                return {
                    name: '',
                    description: ''
                }
            }
        }
    }
</script>
