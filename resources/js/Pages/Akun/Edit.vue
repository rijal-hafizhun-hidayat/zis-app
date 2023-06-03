<template>
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Akun</div>
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
                                        <option selected disabled value="">-- Pilih Menu --</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Bendahara</option>
                                    </select>
                                    <div v-if="validation.role" class="invalid-feedback">
                                        {{ validation.role[0] }}
                                    </div>
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
import { useForm, router } from '@inertiajs/vue3'
import NavBar from '../Components/Navbar.vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import NProgress from 'nprogress';
import { ref } from 'vue'
export default {
    components: { NavBar },
    props: {
        akun: Object
    },
    setup(props) {
        const form = useForm({
            name: props.akun.name,
            username: props.akun.username,
            role: props.akun.role
        })

        const validation = ref([])

        function submit(){
            NProgress.start()
            axios.put(`/akun/${props.akun.id}`, {
                name: form.name,
                username: form.username,
                role: form.role
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
                console.log(err)
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