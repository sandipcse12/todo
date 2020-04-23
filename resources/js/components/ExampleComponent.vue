<template>
    <div class="container mt-4">
        <div class="justify-content-center">
            <input type="text" v-model="title" class="form-control" @keyup.enter="save">
        </div>
        <div>
            <div class="justify-content-center mt-4">
                <ul class="list-group list-group-flush" v-if="tasks.length>0">
                    <li class="list-group-item" v-for="(task,index) in tasks" :key="index"
                        @click="showTextStatus(task.id)">
                        <input class="form-check-input action-icon"
                               :class="{'action-icon-visible':task.status==='completed'}" type="checkbox"
                               @click="completeTask(task.id)"
                               :checked="task.status==='completed'" :disabled="task.status==='completed'"
                               v-if="task.showText">
                        <span class="text-style" :class="{'completed-task':task.status==='completed'}"
                              v-if="task.showText">{{task.title}}</span>
                        <input type="text" class="form-control" v-model="task.title" @keyup.enter="update(task.id)"
                               v-else>
                        <a href="javascript:" v-if="task.showText" @click="deleteTask(task.id)"
                           class="float-right text-danger action-icon"><i class="las la-times"></i></a>
                    </li>
                </ul>
                <ul class="list-group list-group-flush" v-else>
                    <li class="list-group-item text-center">No data found!</li>
                </ul>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-4">
                <ul class="list-group list-group-horizontal-sm">
                    <li class="list-group-item w-100 custom-padding"> {{count}} item left</li>
                </ul>
            </div>
            <div class="col-8">
                <ul class="list-group list-group-horizontal-sm">
                    <li class="list-group-item">
                        <button type="button" class="btn btn-link" @click="filterTasks('all')">All</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-link" @click="filterTasks('active')">Active</button>
                    </li>
                    <li class="list-group-item">
                        <button type="button" class="btn btn-link" @click="filterTasks('completed')">Completed</button>
                    </li>
                    <li class="list-group-item" v-if="competedTask>0">
                        <button type="button" class="btn btn-link" @click="cancelCompletedTask()">Cancel Completed
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                title: '',
                list: [],
                tasks: [],
                allTask: [],
                count: 0,
                competedTask: 0,
            }
        },
        created() {
            this.getTasks();
        },
        mounted() {
            console.log('Component mounted.')
        }, methods: {
            deleteTask(id) {
                let instance = this;
                axios.delete('/task/' + id)
                    .then(function (response) {
                        instance.getTasks();
                    }).catch(function (err) {
                        console.log(err);
                    }
                );
            },
            showTextStatus(id) {
                this.tasks.forEach((element) => {
                    if (element.id === id) {
                        element.showText = false;
                    }
                });
            },
            completeTask(id) {
                this.tasks.forEach((element) => {
                    if (element.id === id) {
                        element.status = 'completed';
                    }
                });

                let instance = this;
                axios.put('/complete-task', {
                    id,
                }).then(function (response) {
                    instance.getTasks();
                }).catch(function (err) {
                        console.log(err);
                    }
                );
            },
            update(id) {
                let title;
                this.tasks.forEach((element) => {
                    if (element.id === id) {
                        element.showText = true;
                        title = element.title;
                    }
                });
                axios.put('/task/' + id, {
                    title
                }).then(function (response) {
                    instance.getTasks();
                }).catch(function (err) {
                        console.log(err);
                    }
                );
            },
            filterTasks(value) {
                if (value === 'all') {
                    this.tasks = this.allTask;
                } else {
                    this.tasks = this.allTask.filter((task) => {
                        return task.status === value;
                    })
                }
            },
            save() {
                let instance = this;
                axios.post('/task', {
                    title: this.title,
                }).then(function (response) {
                    instance.getTasks();
                }).catch(function (err) {
                        console.log(err);
                    }
                );
                instance.title = '';
            },
            getTasks() {
                let instance = this;
                axios.get('/tasks')
                    .then(function (response) {
                        let data = response.data;
                        data.tasks.forEach((element) => {
                            element.showText = true
                        });
                        instance.tasks = data.tasks;
                        instance.allTask = data.tasks;
                        instance.count = data.count;
                        instance.competedTask = data.completed;
                    })
                    .catch(function (err) {
                            console.log(err);
                        }
                    );
            },
            cancelCompletedTask() {
                let instance = this;
                instance.isLoader = true;
                axios.delete('/tasks')
                    .then(function (response) {
                        instance.getTasks();
                    })
                    .catch(function (err) {
                            console.log(err);
                        }
                    );
            }
        }
    }
</script>
<style>
    .list-group-item {
        border: unset;
    }

    .completed-task {
        text-decoration: line-through;
    }

    .text-style {
        cursor: pointer;
    }

    .custom-padding {
        padding: 1.2rem 1.25rem !important;
    }

    .action-icon {
        display: none;
    }

    .action-icon-visible {
        display: unset !important;
    }

    li:hover .action-icon {
        display: unset;
    }
</style>
