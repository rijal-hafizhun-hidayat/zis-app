<template>
    <Head title="Tambah Pengeluaran" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Tambah Pengeluaran</div>
                        <div class="card-body">
                            <form @submit.prevent="submit()" class="mt-3">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Keterengan Pengeluaran</label>
                                    <input type="text" v-model="form.kebutuhan" class="form-control" id="nama" :class="{ 'is-invalid': validation.kebutuhan }">
                                    <div v-if="validation.kebutuhan" class="invalid-feedback">
                                        {{ validation.kebutuhan[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="rekening" class="form-label">Jenis Dana</label>
                                    <select class="form-select" v-model="form.jenis_dana" aria-label="Default select example" :class="{ 'is-invalid': validation.jenis_dana }">
                                        <option disabled selected value="">-- pilih --</option>
                                        <option value="Zakat">Zakat</option>
                                        <option value="Infaq">Infaq</option>
                                        <option value="Shadaqah">Shadaqah</option>
                                    </select>
                                    <div v-if="validation.jenis_dana" class="invalid-feedback">
                                        {{ validation.jenis_dana[0] }}
                                    </div>
                                </div>
                                <div class="mb-3" v-if="form.jenis_dana == 'Zakat'">
                                    <label for="rekening" class="form-label">Golongan Asnaf</label>
                                    <select class="form-select" v-model="form.jenis_asnaf" aria-label="Default select example" :class="{ 'is-invalid': validation.jenis_dana }">
                                        <option disabled selected value="">-- pilih --</option>
                                        <option v-for="asnaf in asnafs">{{ asnaf.golongan_zakat }}</option>
                                    </select>
                                </div>
                                <div v-if="form.jenis_dana == 'Shadaqah'" class="mb-3">
                                    <label for="barang" class="form-label">Pilih Barang</label>
                                    <select class="form-select" id="barang" v-model="form.nama_barang">
                                        <option disabled selected value="">-- Pilih --</option>
                                        <option  v-for="barang in barangs" :key="barang.id">{{ barang.keterangan }}</option>
                                    </select>
                                </div>
                                <div v-if="form.jenis_dana == 'Zakat'" class="mb-3">
                                    <label for="nama_organisasi" class="form-label">Nama Mustahik</label>
                                    <input type="text" v-model="form.nama_organisasi" class="form-control" id="nama_organisasi" :class="{ 'is-invalid': validation.nama_organisasi }">
                                    <div v-if="validation.nama_organisasi" class="invalid-feedback">
                                        {{ validation.nama_organisasi[0] }}
                                    </div>
                                </div>
                                <div v-if="form.jenis_dana == 'Zakat' || form.jenis_dana == 'Infaq'" class="mb-3">
                                    <label for="berat_beras">Berat Beras / kg (Jika ada penyaluran beras)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="form.berat_beras" v-on:keypress="numOnly()" id="berat_beras" aria-describedby="basic-addon2" :class="{ 'is-invalid': validation.berat_beras }">
                                        <span class="input-group-text" id="basic-addon2">Kg</span>
                                        <div v-if="validation.berat_beras" class="invalid-feedback">
                                            {{ validation.berat_beras[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_mustahiq" class="form-label">Jumlah Mustahiq</label>
                                    <input type="text" v-model="form.jumlah_mustahiq" v-on:keypress="numOnly()" class="form-control" id="jumlah_mustahiq" :class="{ 'is-invalid': validation.jumlah_mustahiq }">
                                    <div v-if="validation.jumlah_mustahiq" class="invalid-feedback">
                                        {{ validation.jumlah_mustahiq[0] }}
                                    </div>
                                </div>
                                <div v-if="form.jenis_dana == 'Zakat' || form.jenis_dana == 'Infaq'" class="mb-3">
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control" v-model="nominal" v-on:keypress="numOnly()" @input="formatInput" id="nominal" aria-describedby="basic-addon1" :class="{ 'is-invalid': validation.nominal }">
                                        <div v-if="validation.nominal" class="invalid-feedback">
                                            {{ validation.nominal[0] }}
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pengeluaran" class="form-label pe-2">Bukti Pengeluaran:</label>
                                    <input type="file" @input="form.bukti_pengeluaran = $event.target.files[0]" name="bukti_pengeluaran" id="bukti_pengeluaran" accept="image/*" :class="{ 'is-invalid': validation.bukti_pengeluaran }">
                                    <div v-if="validation.bukti_pengeluaran" class="invalid-feedback">
                                        {{ validation.bukti_pengeluaran[0] }}
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
import Swal from 'sweetalert2';
import axios from 'axios';
import { router, Head } from '@inertiajs/vue3'
import { reactive, ref, watch, onMounted } from 'vue';
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Head },
    props: {
        asnafs: Array
    },
    setup(props){
        console.log(props.asnafs)
        const form = reactive({
            kebutuhan: '',
            nama_organisasi: '',
            jenis_dana: '',
            jenis_asnaf: '',
            nama_barang: '',
            nominal: '',
            berat_beras: '',
            jumlah_mustahiq: '',
            bukti_pengeluaran: ''
        })

        const validation = ref([])
        const barangs = ref([])
        const nominal = ref('')

        onMounted(() => {
            getBarangShadaqah()
        })

        function getBarangShadaqah(){
            axios.get('/getBarangShadaqah')
            .then((res) => {
                barangs.value = res.data.data
            })
            .catch((err) => {
                console.log(err)
            })
        }

        function submit(){
            NProgress.start()
            const d = new Date()
            axios.post('/pengeluaran', {
                nama_organisasi: form.nama_organisasi,
                kebutuhan: form.kebutuhan,
                jenis_dana: form.jenis_dana,
                jenis_asnaf: form.jenis_asnaf,
                nama_barang: form.nama_barang,
                nominal: form.nominal,
                bulan: d.getMonth(),
                berat_beras: form.berat_beras,
                jumlah_mustahiq: form.jumlah_mustahiq,
                bukti_pengeluaran: form.bukti_pengeluaran,
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
                router.get('/pengeluaran')
            })
            .catch((err) => {
                if(err.response.data.errors){
                    validation.value = err.response.data.errors
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: err.response.data.title,
                        text: err.response.data.text
                    })
                }
            })
            .finally(() => {
                NProgress.done()
            })
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

        watch(nominal, (newValue) => {
            form.nominal = newValue.replace(/\./g, ''); // Remove dots for the actual value
        });

        return {
            form,
            validation,
            nominal,
            barangs,
            submit,
            numOnly,
            formatInput,
            getBarangShadaqah
        }
    }
}
</script>