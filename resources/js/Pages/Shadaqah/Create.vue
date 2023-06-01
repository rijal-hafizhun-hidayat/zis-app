<template>
    <Head title="Tambah Shadaqah" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Tambah Shadaqah</div>
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
                                    <label for="rekening" class="form-label">Jenis Bantuan</label>
                                    <select class="form-select" v-model="form.jenis_bantuan" aria-label="Default select example" :class="{ 'is-invalid': validation.jenis_bantuan }">
                                        <option disabled selected value="">-- pilih --</option>
                                        <option value="Uang">Uang</option>
                                        <option value="Barang">Barang</option>
                                    </select>
                                    <div v-if="validation.jenis_bantuan" class="invalid-feedback">
                                        {{ validation.jenis_bantuan[0] }}
                                    </div>
                                </div>
                                <div v-if="form.jenis_bantuan == 'Uang'" class="mb-3">
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <input type="text" v-on:keypress="numOnly" class="form-control" v-model="form.nominal" id="nominal" :required="form.jenis_bantuan == 'Uang'" :class="{ 'is-invalid': validation.nominal }">
                                    <div v-if="validation.nominal" class="invalid-feedback">
                                        {{ validation.nominal[0] }}
                                    </div>
                                </div>
                                <div v-if="form.jenis_bantuan == 'Barang'" class="mb-3">
                                    <label for="jumlah" class="form-label">Keterangan</label>
                                    <input type="text" v-model="form.keterangan" class="form-control" id="jumlah" :required="form.jenis_bantuan == 'Barang'" :class="{ 'is-invalid': validation.jenis_bantuan }">
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran:</label>
                                    <input type="file" @input="form.bukti_pembayaran = $event.target.files[0]" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" :class="{ 'is-invalid': validation.bukti_pembayaran }">
                                    <div v-if="validation.bukti_pembayaran" class="invalid-feedback">
                                        {{ validation.bukti_pembayaran[0] }}
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
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { router, Head } from '@inertiajs/vue3'
import { reactive, ref } from 'vue';
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Head },
    setup(){
        const form = reactive({
            nama_donatur: '',
            nomor_hp: '',
            jenis_bantuan: '',
            nominal: '',
            keterangan: '',
            bukti_pembayaran: ''
        })

        const validation = ref([])

        function submit(){
            NProgress.start()
            const d = new Date();
            setForm()
            axios.post('/shadaqah', {
                nama_donatur: form.nama_donatur,
                nomor_hp: form.nomor_hp,
                nominal: form.nominal,
                jenis_bantuan: form.jenis_bantuan,
                keterangan: form.keterangan,
                bulan: d.getMonth(),
                bukti_pembayaran: form.bukti_pembayaran,
                confirmed: 0
            }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: res.data.title,
                    text: res.data.text
                })

                router.get('/shadaqah')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
            NProgress.done()
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

        function setForm(){
            if(form.jenis_bantuan == 'Uang'){
                form.keterangan = ''
            }
            else{
                form.nominal = ''
            }
        }

        return {
            form,
            validation,
            submit,
            numOnly,
            setForm
        }
    }
    
}
</script>