<template>
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-11">
                    <div v-if="$page.props.flash.message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $page.props.flash.message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-bold">Update Username</div>
                            <form @submit.prevent="submit()" class="mt-3">
                                <div class="mb-3">
                                    <label for="oldUsername" class="form-label">Username</label>
                                    <input type="text" disabled v-model="form.username" class="form-control" :class="{ 'is-invalid': validation.username }" id="oldUsername" required>
                                    <div v-if="validation.username" class="invalid-feedback">{{ validation.username[0] }}</div>
                                </div>
                                <div class="mb-3">
                                    <label for="newUsername" class="form-label">Username Baru</label>
                                    <input type="text" v-model="form.newUsername"  class="form-control" :class="{ 'is-invalid': validation.newUsername }" id="newUsername" required>
                                    <div v-if="validation.newUsername" class="invalid-feedback">{{ validation.newUsername[0] }}</div>
                                    
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2'
import { reactive, onMounted, ref } from 'vue'
import axios from 'axios';
export default {
    setup(){
        const form = reactive({
            username: '',
            newUsername: '',
        })

        const validation = ref([])

        onMounted(() => {
            axios.get(`/getUsername`)
            .then((res) => {
                form.username = res.data.username
            })
            .catch((err) => {
                console.log(err)
            })
        })

        function submit(){
            axios.put(`/updateUsername`, {
                username: form.username,
                newUsername: form.newUsername
            })
            .then((res) => {
                form.username = res.data.data
                form.newUsername = ''
                return Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Berhasil Update Username'
                })
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            });
        }

        return {
            form,
            validation,
            submit
        }
    }
}
</script>

<style scoped>
@media (min-width: 992px){
    .py-5 .container .row .col-sm-11 .card .card-body  form{
        width: 50%;
    }
    #oldUsername{
        cursor: not-allowed;
    }
}
    
</style>