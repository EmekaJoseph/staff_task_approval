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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center my-5" v-if="isLoading">Loading data...</div>
                                    <div v-else>
                                        <div class="text-center my-5" v-if="!list.length">
                                            No Tasks available.
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
                                                        <th>Approve</th>
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

                                                        <td>
                                                            <button @click="approveTask(li.task_id)"
                                                                class="m-0 p-0 px-2 btn btn-outline-success btn-sm">
                                                                Approve
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
    let { data } = await api.completed_list()
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



async function approveTask(task_id: string) {
    if (confirm('Approve task?')) {
        try {
            await api.approve(task_id)
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