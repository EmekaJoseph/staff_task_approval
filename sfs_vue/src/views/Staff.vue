<template>
    <div class="container">
        <div class="header">
            Welcome, <span class="email text-muted fw-bold fs-5">{{ account.state.email }}</span>

        </div>
        <div class="text-muted">Logged in as: ({{ account.state.role }})
            <span @click="logOut" class="float-end logout-btn bg-danger-subtle px-3 text-danger rounded-2 cursor-pointer">
                logout
            </span>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card border-0">
                <div class="card-header text-center fw-bold text-uppercase bg-transparent border-0 p-3">{{
                    account.state.dept }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="row gy-3 mb-4">
                            <div class="col-md-4">
                                <label>Task Title:</label>
                                <input v-model="form.task_name" class="form-control" type="text">
                            </div>
                            <div class="col-md-3">
                                <label>Start date:</label>
                                <input v-model="form.start_date" :min="form.today" class="form-control" type="date">
                            </div>
                            <div class="col-md-3">
                                <label>End Date:</label>
                                <input v-model="form.end_date" :min="form.start_date" class="form-control" type="date">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button @click="addTask" class="btn btn-dark w-100">Add</button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center my-5" v-if="isLoading">Loading data...</div>
                                    <div v-else>
                                        <div class="text-center my-5" v-if="!list.length">
                                            No Tasks, Add a Task.
                                        </div>
                                        <div v-else class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Name</th>
                                                        <th>Start</th>
                                                        <th>End</th>
                                                        <th>Status</th>
                                                        <th>Complete?</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(li, index) in list" :key="index">
                                                        <th>{{ (index + 1) }}</th>
                                                        <td>{{ li.task_name }}</td>
                                                        <td>{{ li.start_date }}</td>
                                                        <td>{{ li.end_date }}</td>
                                                        <td class="small" v-if="li.is_approved == '0'">Unapproved</td>
                                                        <td class="text-success small" v-else>Approved</td>
                                                        <td v-if="li.is_completed == '0'">
                                                            <button @click="mark_as_complete(li.task_id)"
                                                                class="m-0 p-0 px-2 btn btn-outline-success btn-sm">
                                                                Complete
                                                            </button>
                                                        </td>
                                                        <td class="text-success small" v-else>Completed</td>
                                                        <td>
                                                            <button v-if="li.is_approved == '0'"
                                                                @click="confirmDelete(li.task_id)"
                                                                class="m-0 p-0 px-2 btn btn-outline-danger btn-sm">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { useAccount } from '@/store/Account'
import api from '@/store/axiosManager';
import { useRouter } from 'vue-router';
import { onMounted, reactive, ref } from 'vue';


onMounted(() => {
    isLoading.value = true
    getList()
})

async function getList() {
    let { data } = await api.taskList()
    list.value = data
    isLoading.value = false
}

const list = ref<any>([])
const isLoading = ref<boolean>(false)
const router = useRouter()
const account = useAccount()

const form = reactive({
    task_name: '',
    start_date: new Date().toISOString().split('T')[0],
    end_date: new Date().toISOString().split('T')[0],
    today: new Date().toISOString().split('T')[0],
})


async function addTask() {
    if (!form.task_name) {
        alert('Please add a Task Title')
        return;
    }

    try {
        let resp = await api.addTask(form)
        if (resp.status == 203) {
            alert('name already exists')
            return
        }

        form.task_name = ''
        isLoading.value = true
        getList()
    } catch (error) {
        alert('Internet Error')
    }
}


async function confirmDelete(task_id: string) {
    if (confirm('Sure you want to delete?')) {
        try {
            await api.deleteTask(task_id)
            getList()
        } catch (error) {
            alert('Internet Error')
        }
    }
}

async function mark_as_complete(task_id: string) {
    if (confirm('Send for approval?')) {
        try {
            await api.mark_as_complete(task_id)
            getList()
        } catch (error) {
            alert('Internet Error')
        }
    }
}


async function logOut() {
    try {
        await api.logout()
    } catch (error) {
        //
    }
    account.state = account.nullState;
    router.replace({ path: '/login' })
}

</script>

<style scoped>
.header {
    width: 100%;
    padding-block: 20px;
    margin-bottom: 40px;
}

/* .entire-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;

} */
</style>