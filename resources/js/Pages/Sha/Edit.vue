<template>
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Edit Satuan</div>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit()">
                                <div class="mb-3">
                                    <label for="makanan-pokok" class="form-label">Nama Satuan</label>
                                    <input type="text" class="form-control" v-model="sha.nama" id="makanan-pokok" :class="{ 'is-invalid': validation.nama }" >
                                    <div v-if="validation.nama" class="invalid-feedback">
                                        {{ validation.nama[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                                        <input type="text" v-on:keypress="NumbersOnly" @input="formatInput" class="form-control" v-model="nominal" id="harga" :class="{ 'is-invalid': validation.harga }">
                                        <div v-if="validation.harga" class="invalid-feedback">
                                            {{ validation.harga[0] }}
                                        </div>
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
    <Footer />
</template>
<script>
import Navbar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import axios from 'axios'
import { reactive, watch, ref } from 'vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import NProgress from 'nprogress';
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

        const validation = ref([])
        const nominal = ref('')
        nominal.value = numberWithDots(sha.harga)

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
            NProgress.start(0)
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

                router.get('/sha')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
            .finally(() => {
                NProgress.done()
            })
        }

        const formatInput = (event) => {
            let value = event.target.value.replace(/\./g, ''); // Remove existing dots
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every three digits
            nominal.value = value;
        };

        watch(nominal, (newValue) => {
            sha.harga = newValue.replace(/\./g, ''); // Remove dots for the actual value
        });

        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        return {
            submit,
            NumbersOnly,
            formatInput,
            numberWithDots,
            sha,
            validation,
            nominal
        }
    },
}
</script>