<template>
    <Head title="Tambah Akun" />
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Tambah Akun</div>
                        <div class="card-body">
                            <form @submit.prevent="submit()" class="mt-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" v-model="form.name" class="form-control" id="name" :class="{ 'is-invalid': validation.name }">
                                    <div v-if="validation.name" class="invalid-feedback">
                                        {{ validation.name[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" v-model="form.username" class="form-control" id="username" :class="{ 'is-invalid': validation.username }">
                                    <div v-if="validation.username" class="invalid-feedback">
                                        {{ validation.username[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" v-model="form.role" aria-label="Default select example" :class="{ 'is-invalid': validation.role }">
                                        <option disabled selected value="">-- pilih --</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Bendahara</option>
                                        <option value="3">Pemeliharaan & Pembangunan</option>
                                    </select>
                                    <div v-if="validation.role" class="invalid-feedback">
                                        {{ validation.role[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" v-model="form.password" class="form-control" id="password" :class="{ 'is-invalid': validation.password }">
                                    <div v-if="validation.password" class="invalid-feedback">
                                        {{ validation.password[0] }}
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <Footer />
</template>
<script>
import { Head, router } from '@inertiajs/vue3'
import NavBar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import NProgress from 'nprogress';
import axios from 'axios'
import Swal from 'sweetalert2'
import { reactive, ref } from 'vue'
export default {
    components: { NavBar, Footer, Head },
    setup() {
        const form = reactive({
            name: '',
            username: '',
            role: '',
            password: ''
        })

        const validation = ref([])

        function submit(){
            NProgress.start()
            axios.post('/akun', {
                name: form.name,
                username: form.username,
                role: form.role,
                password: form.password
            })
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.data.message
                })
                router.get('/akun')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
            .finally(() => {
                NProgress.done()
            })
        }

        return {
            form,
            validation,
            submit
        }
    },
}
</script>