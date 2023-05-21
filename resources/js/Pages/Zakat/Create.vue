<template>
    <Head>
        <title>Tambah Data Zakat</title>
    </Head>
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Tambah Zakat</div>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit" ref="inputFile">
                                <div class="mb-3">
                                    <label for="nama_donatur" class="form-label">Nama Donatur</label>
                                    <input type="text" v-model="zakat.nama_donatur" class="form-control" id="nama_donatur" :class="{ 'is-invalid': validation.nama_donatur }">
                                    <div v-if="validation.nama_donatur" class="invalid-feedback">
                                        {{ validation.nama_donatur[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor Hp</label>
                                    <input type="text" v-model="zakat.nomor_hp" class="form-control" id="nomor_hp" v-on:keypress="NumbersOnly()" :class="{ 'is-invalid': validation.nomor_hp }">
                                    <div v-if="validation.nomor_hp" class="invalid-feedback">
                                        {{ validation.nomor_hp[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_zakat" class="form-label">Jenis Zakat</label>
                                    <select v-model="zakat.jenis_zakat" class="form-select" id="jenis_zakat" :class="{ 'is-invalid': validation.jenis_zakat }">
                                        <option selected disabled value="">-- Pilih --</option>
                                        <option value="Zakat Fitrah">Zakat Fitrah</option>
                                        <option value="Zakat Maal">Zakat Maal</option>
                                    </select>
                                    <div v-if="validation.jenis_zakat" class="invalid-feedback">
                                        {{ validation.jenis_zakat[0] }}
                                    </div>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah'" class="mb-3">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <select v-model="zakat.sha_id" class="form-select" id="satuan" :class="{ 'is-invalid': validation.sha_id }">
                                        <option selected disabled value="">-- pilih --</option>
                                        <option v-for="sha in shas" :value="sha.id" :key="sha.id">{{ sha.nama }}</option>
                                    </select>
                                    <div v-if="validation.sha_id" class="invalid-feedback">
                                        {{ validation.sha_id[0] }}
                                    </div>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah'" class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah / 1 Beras di hitung 2,5 Kg</label>
                                    <input type="text" @change="setBeratBeras(zakat.jumlah, zakat.sha_id)" v-model="zakat.jumlah" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :class="{ 'is-invalid': validation.jumlah }">
                                    <div v-if="validation.jumlah" class="invalid-feedback">
                                        {{ validation.jumlah[0] }}
                                    </div>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah' && zakat.sha_id == 1" class="mb-3">
                                    <label for="jumlah" class="form-label">Berat Beras / kg</label>
                                    <input type="text" disabled v-model="zakat.berat_beras" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :required="zakat.sha_id == 1" :class="{ 'is-invalid': validation.berat_beras }">
                                    <div v-if="validation.berat_beras" class="invalid-feedback">
                                        {{ validation.berat_beras[0] }}
                                    </div>
                                </div>
                                <div v-if="zakat.sha_id == 2 || zakat.jenis_zakat == 'Zakat Maal'" class="mb-3">
                                    <label for="jumlah" class="form-label">Nominal</label>
                                    <input type="text" :disabled="zakat.jenis_zakat == 'Zakat Fitrah'" v-model="zakat.nominal" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :class="{ 'is-invalid': validation.nominal }">
                                    <div v-if="validation.nominal" class="invalid-feedback">
                                        {{ validation.nominal[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label pe-2">Bukti Pembayaran:</label>
                                    <input type="file" @input="zakat.bukti_pembayaran = $event.target.files[0]" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" :class="{ 'is-invalid': validation.bukti_pembayaran }">
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
import NavBar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import { reactive, ref } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { router, Head } from '@inertiajs/vue3'
export default{
    components: { NavBar, Footer, Head },
    props: {
        shas: Object,
        errors: Object
    },
    setup() {
        const zakat = reactive({
            nama_donatur: '',
            nomor_hp: '',
            jenis_zakat: '',
            sha_id: '',
            berat_beras: '',
            jumlah: '',
            nominal: '',
            bukti_pembayaran: '',
        })

        const validation = ref([])

        const empty = ref(null)

        //const inputFile = ref(null)

        function NumbersOnly(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }

        function submit(){
            console.log(zakat)
            const d = new Date();
            let month = d.getMonth();

            setForm()

            axios.post('/zakat', {
                nama_donatur: zakat.nama_donatur,
                nomor_hp: zakat.nomor_hp,
                jenis_zakat: zakat.jenis_zakat,
                sha_id: zakat.sha_id,
                berat_beras: zakat.berat_beras,
                jumlah: zakat.jumlah,
                nominal: zakat.nominal,
                bulan: month,
                bukti_pembayaran: zakat.bukti_pembayaran,
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

                return router.get('/zakat')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
        }

        function setTotalNominal(jumlah){
            axios.get(`/getNominalSha/${zakat.sha_id}`)
            .then((res) => {
                zakat.nominal = res.data.data[0].harga*jumlah
                //console.log(zakat.nominal)
            })
            .catch((err) => {
                console.log(err)
            })
        }

        function setBeratBeras(jumlah){
            if(zakat.sha_id == 1){
                zakat.nominal = ''
                zakat.berat_beras = jumlah*2.5
            }
            
            else{
                zakat.berat_beras = ''
                setTotalNominal(jumlah)
            }
        }

        function setForm(){
            if(zakat.jenis_zakat == 'Zakat Maal'){
                zakat.sha_id = 2
                zakat.berat_beras = '',
                zakat.jumlah = ''
            }
        }

        return {
            submit,
            NumbersOnly,
            setTotalNominal,
            setBeratBeras,
            setForm,
            zakat,
            empty,
            validation
        }
    },
}
</script>