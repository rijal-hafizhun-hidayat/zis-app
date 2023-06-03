<template>
    <main class="py-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-11">
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-bold">Update Password</div>
                            <form class="mt-3" @submit.prevent="savePass()">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Password Baru</label>
                                    <input type="password" v-model="form.newPass" class="form-control" id="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="konfPassword" class="form-label">Koonfirmasi Password</label>
                                    <input type="password" v-model="form.konfPass" class="form-control" id="konfPassword" required>
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
import { reactive } from 'vue'
import Swal from 'sweetalert2'
import axios from 'axios';
import NProgress from 'nprogress';
export default {
    setup() {
        const form = reactive({
            newPass: '',
            konfPass: ''
        });

        function savePass(){
            NProgress.start()
            axios.put('/updatePassword', {
                newPass: form.newPass,
                konfPass: form.konfPass,
            })
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Berhasil Update Password'
                })
                NProgress.done()
                form.reset('newPass', 'konfPass')
            })
            .catch((err) => {
                form.konfPass = ''
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.response.data.message
                })
            })
            .finally(() => {
                NProgress.done()
            })
        }

        return {
            form,
            savePass
        }
    },
}
</script>

<style scoped>
@media (min-width: 992px){
    .py-1 .container .row .col-sm-11 .card .card-body  form{
        width: 50%;
    }
}
   
</style>