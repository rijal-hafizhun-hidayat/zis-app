<script setup>
import { Head, router } from '@inertiajs/vue3';
import NavBar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import { reactive, onMounted } from 'vue'
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    id: Number
})
const asnaf = reactive({
    golongan_zakat: '',
    total: ''
})

onMounted(() => {
    getAsnafById()
})

const getAsnafById = () => {
    axios.get(`/getAsnafById/${props.id}`)
    .then((res) => {
        asnaf.golongan_zakat = res.data.data.golongan_zakat
        asnaf.total = res.data.data.total
    })
    .catch((err) => {
        console.log(err)
    })
}

const submit = () => {
    axios.put(`/asnaf/${props.id}`, {
        golongan_zakat: asnaf.golongan_zakat,
        total: asnaf.total 
    })
    .then((res) => {
        Swal.fire({
            icon: 'success',
            title: res.data.title,
            text: res.data.text
        })
        router.get('/asnaf')
    })
    .catch((err) => {
        console.log(err)
    })
}
</script>
<template>
    <Head title="Edit Golongan Asnaf" />
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Golongan Asnaf</div>
                        <div class="card-body">
                            <form @submit.prevent="submit" class="mt-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" v-model="asnaf.golongan_zakat" class="form-control" id="name">
                                </div>
                                <label for="name" class="form-label">Total</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                    <input type="text" class="form-control" v-model="asnaf.total" placeholder="Total" aria-label="Total" aria-describedby="basic-addon1">
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