<template>
    <Head title="Login" />
    <NavBar />
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <!-- <p>{{ validation.username[0] }}</p> -->
                        <form @submit.prevent="login()">
                            <div class="row mb-3">
                                <label for="username" class="col-md-4 col-form-label text-md-end">Username</label>
                                <div class="col-md-6">
                                    <input id="username" v-model="form.username" type="text" class="form-control" :class="{ 'is-invalid': validation.username }">
                                    <div v-if="validation.username" class="invalid-feedback">
                                        {{ validation.username[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                <div class="col-md-6">
                                    <input id="password" v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': validation.password }">
                                    <div v-if="validation.password" class="invalid-feedback">
                                        {{ validation.password[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>
<script>
import NavBar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import NProgress from 'nprogress';
import { reactive, ref } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
export default {
    components: { NavBar, Footer, Head },
    setup() {
        const form = reactive({
            username: '',
            password: ''
        })

        const validation = ref([])

        function login(){
            NProgress.start()
            axios.post('/login', {
                username: form.username,
                password: form.password
            })
            .then((res) => {
                router.get('/dashboard')
            })
            .catch((err) => {
                if(err.response.data.errors){
                    validation.value = err.response.data.errors
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: err.response.data.title,
                        text: err.response.data.text,
                    })

                    form.password = '',
                    validation.value = ''
                }
            })
            .finally(() => {
                NProgress.done()
            })
        }

        return {
            form,
            validation,
            login
        }
    },
}
</script>