<template>
    <Head title="Edit Infaq" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Infaq</div>
                        <div class="card-body">
                            <form @submit.prevent="submit()" class="mt-3">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Donatur</label>
                                    <input type="text" v-model="form.nama_donatur" class="form-control" id="nama" :class="{ 'is-invalid': validation.nama_donatur }">
                                    <div v-if="validation.nama_donatur" class="invalid-feedback">
                                        {{ validation.nama_donatur[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor Hp</label>
                                    <input type="text" v-model="form.nomor_hp" v-on:keypress="numOnly()" class="form-control" id="nomor_hp" :class="{ 'is-invalid': validation.nomor_hp }">
                                    <div v-if="validation.nomor_hp" class="invalid-feedback">
                                        {{ validation.nomor_hp[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="rekening" class="form-label">Metode Pembayaran</label>
                                    <select class="form-select" v-model="form.metode_pembayaran" aria-label="Default select example" :class="{ 'is-invalid': validation.metode_pembayaran }">
                                        <option disabled selected value="">-- pilih --</option>
                                        <option value="Rekening">Rekening</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                    <div v-if="validation.metode_pembayaran" class="invalid-feedback">
                                        {{ validation.metode_pembayaran[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Nominal</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" v-model="nominal" v-on:keypress="numOnly()" @input="formatInput" class="form-control" id="jumlah" :class="{ 'is-invalid': validation.nominal }">
                                        <div v-if="validation.nominal" class="invalid-feedback">
                                            {{ validation.nominal[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <label class="form-label pe-2">Update Bukti Pembayaran:</label>
                                    <input type="file" @input="form.new_bukti_pembayaran = $event.target.files[0]" name="bukti_pembayaran" accept="image/*" :class="{ 'is-invalid': validation.new_bukti_pembayaran }">
                                    <div v-if="validation.new_bukti_pembayaran" class="invalid-feedback">
                                        {{ validation.new_bukti_pembayaran[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label pe-2">Bukti Pembayaran:</label>
                                    <Modal :image="form.old_bukti_pembayaran" :path="'Infaq'" :id="infaq.id"/>
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
import Modal from '../Components/Modal.vue';
import { reactive, ref, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { router, Head } from '@inertiajs/vue3'
import NProgress from 'nprogress';
export default {
    components: { Navbar, Footer, Modal, Modal, Head },
    props: {
        infaq: Object
    },
    setup(props){
        const form = reactive({
            nama_donatur: props.infaq.nama_donatur,
            nomor_hp: props.infaq.nomor_hp,
            metode_pembayaran: props.infaq.metode_pembayaran,
            nominal: props.infaq.nominal,
            old_bukti_pembayaran: props.infaq.bukti_pembayaran,
            confirmed: props.infaq.confirmed,
            new_bukti_pembayaran: ''
        })

        const validation = ref([])
        const nominal = ref('')
        nominal.value = numberWithDots(form.nominal)

        function submit(){
            NProgress.start()
            axios.post(`/infaq/${props.infaq.id}`,dataAppend() , {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: res.data.title,
                    text: res.data.message
                })
                router.get('/infaq')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
            .finally(() => {
                NProgress.done()
            })
        }

        function dataAppend(){
            let data = new FormData();
            data.append('nama_donatur', form.nama_donatur)
            data.append('nomor_hp', form.nomor_hp)
            data.append('metode_pembayaran', form.metode_pembayaran)
            data.append('nominal', form.nominal)
            data.append('bukti_pembayaran', form.new_bukti_pembayaran)
            data.append('confirmed', form.confirmed)

            data.append("_method", "PUT")

            return data;
        }

        function numOnly(evt){
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
            nominal.value = value;
        };

        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        watch(nominal, (newValue) => {
            form.nominal = newValue.replace(/\./g, ''); // Remove dots for the actual value
        });

        return {
            form,
            validation,
            nominal,
            submit,
            numOnly,
            dataAppend,
            formatInput,
            numberWithDots
        }
    }
}
</script>