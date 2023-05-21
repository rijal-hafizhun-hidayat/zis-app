<template>
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Edit Makanan Pokok</div>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit()">
                                <div class="mb-3">
                                    <label for="makanan-pokok" class="form-label">Nama Makanan Pokok</label>
                                    <input type="text" class="form-control" v-model="sha.nama" id="makanan-pokok" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga / 2,5 Kg</label>
                                    <input type="text" v-on:keypress="NumbersOnly" class="form-control" v-model="sha.harga" id="harga" required>
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
import Navbar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import axios from 'axios'
import { reactive } from 'vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

export default {
    components: { Navbar, Footer },
    props: {
        sha: Object
    },
    setup(props) {
        const sha = reactive({
            nama: props.sha.nama,
            harga: props.sha.harga
        })

        function NumbersOnly(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                //makananPokok.harga = makananPokok.harga.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                return true;
            }
        }

        function submit(){
            axios.put(`/sha/${props.sha.id}`, {
                nama: sha.nama,
                harga: sha.harga
            })
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.data.message
                })

                return router.get('/sha')
            })
            .catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'gagal update data'
                })
            })
        }

        return {
            submit,
            NumbersOnly,
            sha
        }
    },
}
</script>