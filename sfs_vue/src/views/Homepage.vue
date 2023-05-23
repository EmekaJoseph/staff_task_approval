<template>
    <div class="container-fluid entire-page">
        <div>
            <div class="text-center large-welcome">Welcome to SFS-APP</div>
            <div class="btns-panel">
                <div></div>
                <router-link to="/login" class="btn btn-light">Login</router-link>
                <router-link to="/register" class="btn btn-dark">Create Account</router-link>
                <div></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped>
.entire-page {
    background-color: #2400ee31;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;

}

.entire-page .large-welcome {
    font-weight: bold;
    font-size: 3rem;
    text-justify: center;
    margin-bottom: 50px;
}

.entire-page .btns-panel {
    display: flex;
    justify-content: space-between;
}

.btn {
    width: 135px;
}
</style>