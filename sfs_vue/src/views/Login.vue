<template>
    <div class="container entire-page">
        <div class="col-12 col-sm-5 col-lg-4">
            <div class="card border-0">
                <div class="card-header fw-bold fs-3 border-0 text-center">LOGIN</div>
                <div class="card-body">
                    <form class="row gy-3">
                        <div class="col-12">
                            <label>Email:</label>
                            <input v-model="form.email" type="email" class="form-control">
                        </div>
                        <div class="col-12">
                            <label>Password:</label>
                            <input v-model="form.password" type="password" class="form-control">
                        </div>

                        <div class="col-12 my-4">
                            <button v-if="!form.isLoading" type="submit" @click.prevent="LoginUser"
                                class="btn btn-outline-dark w-100">Login</button>
                            <button v-else disabled class="btn btn-outline-dark w-100">Please wait..</button>
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
import { useAccount } from '@/store/Account'


const account = useAccount()

const router = useRouter()

const form = reactive({
    email: '',
    password: '',
    isLoading: false
})

async function LoginUser() {
    if (!form.email || !form.password) {
        alert('please complete the form')
        return;
    }


    form.isLoading = true;

    try {
        let resp: any = await api.login(form)
        if (resp.status == 203) {
            alert('Invalid Details')
            form.isLoading = false;
            return;
        }

        form.isLoading = false;
        form.password = ''
        form.email = ''
        account.state = resp.data
        account.token = resp.data.token
        if (resp.data.role == 'staff')
            router.replace({ path: '/staff' })
        else
            router.replace({ path: '/approver' })

        // router.push({ path: '/login' })
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