<template>
    <div class="container entire-page">
        <div class="col-12 col-sm-5 col-lg-4">
            <div class="card border-0">
                <div class="card-header fw-bold fs-3 border-0 text-center">NEW ACCOUNT</div>
                <div class="card-body">
                    <form class="row gy-3">
                        <div class="col-12">
                            <label>Valid Email:</label>
                            <input v-model="form.email" type="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label>Password:</label>
                            <input v-model="form.password" type="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label>Role:</label>
                            <select v-model="form.role" class="form-select" required>
                                <option value="staff">Staff</option>
                                <option value="approver">Approver</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label>Department:</label>
                            <select v-model="form.dept_id" class="form-select" required>
                                <option value="1">Department01</option>
                                <option value="2">Department02</option>
                            </select>
                        </div>

                        <div class="col-12 my-4">
                            <button v-if="!form.isLoading" type="submit" @click.prevent="createAccount"
                                class="btn btn-dark w-100">Create
                                Account</button>
                            <button v-else disabled class="btn btn-dark w-100">Please wait..</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <router-link to="/">Back</router-link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import api from '@/store/axiosManager';
import { reactive } from 'vue';
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
    email: '',
    password: '',
    dept_id: '1',
    role: 'staff',
    isLoading: false
})

async function createAccount() {
    if (!form.email || !form.password) {
        alert('please complete the form')
        return;
    }

    const regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!regex.test(form.email)) {
        alert('please use a valid email address')
        return;
    }

    form.isLoading = true;

    try {
        let resp: any = await api.register(form)
        if (resp.status == 203) {
            alert('Email already exists')
            form.isLoading = false;
            return;
        }
        form.isLoading = false;
        alert('Account Successfully Created, Go to Login')
        router.push({ path: '/login' })
    } catch (error) {
        form.isLoading = false;
        console.log(error);

    }

}
</script>

<style scoped>
.entire-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
</style>