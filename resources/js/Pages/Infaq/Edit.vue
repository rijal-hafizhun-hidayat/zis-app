<template>
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
                                    <input type="text" v-model="form.nominal" v-on:keypress="numOnly()" class="form-control" id="jumlah" :class="{ 'is-invalid': validation.nominal }">
                                    <div v-if="validation.nominal" class="invalid-feedback">
                                        {{ validation.nominal[0] }}
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
import Modal from '../Components/Modal.vue';
import { reactive, ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3'

export default {
    components: { Navbar, Footer, Modal, Modal },
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

        function submit(){
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

                return router.get('/infaq')

            })
            .catch((err) => {
                validation.value = err.response.data.errors
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

        return {
            form,
            validation,
            submit,
            numOnly,
            dataAppend
        }
    }
}
</script>