<script setup>
import { Head, router } from '@inertiajs/vue3';
import NavBar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import { reactive, watch, ref } from 'vue'
import axios from 'axios';

const total = ref('')
const asnaf = reactive({
    golongan_zakat: '',
    total: ''
})

const submit = () => {
    axios.post('/asnaf', {
        golongan_zakat: asnaf.golongan_zakat,
        total: asnaf.total 
    })
    .then((res) => {
        console.log(res)
    })
    .catch((err) => {
        console.log(err)
    })
}

const numOnly = (evt) => {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
}

const formatInput = (event) => {
    let value = event.target.value.replace(/\./g, ''); // Remove existing dots
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every three digits
    total.value = value;
};

watch(total, (newValue) => {
    //console.log(newValue.total)
    asnaf.total = newValue.replace(/\./g, ''); // Remove dots for the actual value
});
</script>
<template>
    <Head title="Tambah Akun" />
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Tambah Golongan Asnaf</div>
                        <div class="card-body">
                            <form @submit.prevent="submit" class="mt-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" v-model="asnaf.golongan_zakat" class="form-control" id="name">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Total Nominal</label>
                                    <input type="text" v-model="total" v-on:keydown="numOnly()" @input="formatInput" class="form-control" id="total">
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