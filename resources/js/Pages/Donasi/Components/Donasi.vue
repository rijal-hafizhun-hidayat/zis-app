<template>
    <div class="container">
        <form @submit.prevent="submit()">
            <div class="row">
                <p>Jika Anda berminta untuk menyalurkan sebagian rezeki anda, silahkan isi formulir dibawah ini</p>
                <p>Silahkan bisa melakukan pembayaran dengan transfer lewat rekening <b style="color: black;">MANDIRI</b>, NO REKENING: <b style="color: black;">1340024601774</b></p>
                <h1 class="mb-4">Donasi Sekarang !</h1>
                <div class="col">
                    <input type="text" v-model="donasi.nama_donatur" class="form-control" placeholder="Nama Lengkap" aria-label="Nama Lengkap" :class="{ 'is-invalid': validation.nama_donatur }">
                    <div v-if="validation.nama_donatur" class="invalid-feedback">
                        {{ validation.nama_donatur[0] }}
                    </div>
                </div>
                <div class="col">
                    <input type="text" class="form-control" v-model="donasi.nomor_hp" placeholder="Nomor Handphone" v-on:keypress="numOnly()" aria-label="Nomor Handphone" :class="{ 'is-invalid': validation.nomor_hp }">
                    <div v-if="validation.nomor_hp" class="invalid-feedback">
                        {{ validation.nomor_hp[0] }}
                    </div>
                </div>
                <div class="mt-4">
                    <select v-model="donasi.jenis_donasi" class="form-select" aria-label="Jenis Donasi" :class="{ 'is-invalid': validation.jenis_donasi }">
                        <option selected disabled value="">-- Jenis Donasi --</option>
                        <option value="Zakat Fitrah">Zakat Fitrah</option>
                        <option value="Zakat Maal">Zakat Maal</option>
                        <option value="Infaq">Infaq</option>
                        <option value="Shadaqah">Shadaqah</option>
                    </select>
                    <div v-if="validation.jenis_donasi" class="invalid-feedback">
                        {{ validation.jenis_donasi[0] }}
                    </div>
                </div>
                <div v-if="donasi.jenis_donasi == 'Zakat Fitrah'" class="mt-4">
                    <label class="form-label" for="sha_id">Jenis Sha</label>
                    <select v-model="donasi.satuan" class="form-select" :class="{ 'is-invalid': validation.sha_id }">
                        <option selected disabled value="">-- Jenis Sha --</option>
                        <option v-for="satuan in satuans" :value="satuan.id">{{ satuan.nama }}</option>
                    </select>
                    <div v-if="validation.sha_id" class="invalid-feedback">
                        {{ validation.sha_id[0] }}
                    </div>
                </div>
                <div v-if="donasi.jenis_donasi == 'Zakat Fitrah'" class="mt-4">
                    <label class="form-label" for="jumlah">jumlah 1 = beras dihitung 2.5 Kg</label>
                    <input type="text" @change="getNominal(donasi.satuan, donasi.jumlah)" v-model="donasi.jumlah" v-on:keypress="numOnly()" class="form-control" maxlength="2" id="jumlah" :class="{ 'is-invalid': validation.jumlah }">
                    <div v-if="validation.jumlah" class="invalid-feedback">
                        {{ validation.jumlah[0] }}
                    </div>
                </div>
                <div v-if="donasi.jenis_donasi == 'Zakat Fitrah' && donasi.satuan == 1" class="input-group mt-4">
                    <input type="text" :disabled="donasi.satuan == 1" v-model="donasi.berat_beras" class="form-control" placeholder="Berat Beras" aria-describedby="berat_beras" :class="{ 'is-invalid': validation.berat_beras }">
                    <div v-if="validation.berat_beras" class="invalid-feedback">
                        {{ validation.berat_beras[0] }}
                    </div>
                    <span class="input-group-text" id="berat_beras">Kg</span>
                </div>
                <div class="mt-4" v-if="donasi.jenis_donasi == 'Zakat Maal'">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp</span>
                        <input type="text" v-model="nisabZakat" class="form-control" @change="setNominalZakatMaal(nisabZakat)" @input="formatInputNisabZakat" placeholder="Masukkan Penghasilan /tahun">
                    </div>
                    <div v-if="donasi.nisab_zakat != null && donasi.nisab_zakat < minimalNisabZakat" class="alert alert-danger" role="alert">minimal nishab zakat yaitu Rp. {{ formatRupiah(minimalNisabZakat) }}</div>
                </div>
                <div v-if="donasi.satuan == 2 || donasi.jenis_donasi != 'Zakat Fitrah'" class="mt-4">
                    <label v-if="donasi.satuan == 2" for="nominal" class="form-label">Untuk Jenis Sha <b>Uang</b>, Nominal akan ditentukan sesuai harga beras</label>
                    <div class="input-group">
                        <span class="input-group-text" id="Rupiah">Rp.</span>
                        <input type="text" :disabled="donasi.satuan == 2 && donasi.jenis_donasi == 'Zakat Fitrah' || donasi.jenis_donasi == 'Zakat Maal'" v-model="nominal" v-on:keypress="numOnly()" @input="formatInput" class="form-control" placeholder="Nominal" aria-label="Nominal" aria-describedby="Rupiah" :class="{ 'is-invalid': validation.nominal }">
                        <div v-if="validation.nominal" class="invalid-feedback">
                        {{ validation.nominal[0] }}
                    </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="buktiDonasi" class="form-label">Upload Bukti Pengiriman Donasi Anda: </label>
                    <input type="file" @input="donasi.bukti_donasi = $event.target.files[0]" class="ms-3" name="bukti_donasi" accept="image/*" id="buktiDonasi" :class="{ 'is-invalid': validation.bukti_donasi }">
                    <div v-if="validation.bukti_donasi" class="invalid-feedback">
                        {{ validation.bukti_donasi[0] }}
                    </div>
                </div>
            </div>
            <button>Kirim Donasi</button>
        </form>
    </div>
</template>
<script>
import { reactive, ref, onMounted, watch } from 'vue'
import axios from 'axios';
import Swal from 'sweetalert2';
import NProgress from 'nprogress';
export default{
    props: {
        errors: Object
    },
    setup(){
        const donasi = reactive({
            nama_donatur: '',
            nomor_hp: '',
            jenis_donasi: '',
            satuan: '',
            jumlah: '',
            nisab_zakat: '',
            nominal: '',
            berat_beras: '',
            metode_pembayaran: 'Rekening',
            bukti_donasi: null
        })

        const minimalNisabZakat = ref('')
        const satuans = ref([])
        const validation = ref([])
        const nominal = ref('')
        const nisabZakat = ref('')

        onMounted(() => {
            getSatuan()
            getSatuanEmas()
        })

        function numOnly(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }

        function submit(){
            NProgress.start()
            const d = new Date();
            let month = d.getMonth();

            axios.post('/donasi', {
                nama_donatur: donasi.nama_donatur,
                nomor_hp: donasi.nomor_hp,
                jenis_donasi: donasi.jenis_donasi,
                sha_id: donasi.satuan,
                jumlah: donasi.jumlah,
                nominal: donasi.nominal,
                nisab_zakat: donasi.nisab_zakat,
                berat_beras: donasi.berat_beras,
                metode_pembayaran: donasi.metode_pembayaran,
                bulan: month,
                bukti_donasi: donasi.bukti_donasi,
                confirmed: 0
            },
            {
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
            })
            .catch((err) => {
                if(err.response.data.errors != null){
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

        function getSatuanEmas(){
            axios.get('/getSatuanEmas')
            .then((res) => {
                minimalNisabZakat.value = res.data.data.harga
            })
            .catch((err) => {
                console.log(err)
            })
        }

        function getSatuan(){
            axios.get('/getSatuan')
            .then((res) => {
                satuans.value = res.data.data
            })
            .catch((err) => {
                console.log(err)
            })
        }
        
        function getNominal(id, jumlah){
            axios.get(`/getNominal/${id}`)
            .then((res) => {
                nominal.value = res.data.data.harga * jumlah
                donasi.berat_beras = jumlah * 2.5
                nominal.value = nominal.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            })
            .catch((err) => {
                console.log(err)
            })
        }

        function setNominalZakatMaal(nisab){
            let nisabReplace = ''
            nisabReplace = nisab.replace(/\./g, '')
            nominal.value = (nisabReplace / 100) * 2.5 
        }

        function setBeratBeras(jumlah){
            donasi.berat_beras = jumlah * 2.5
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

        function isMinimalNisabZakat(nisabZakat){
            if(nisabZakat != null && nisabZakat < minimalNisabZakat){
                return true
            }
            else{
                return false
            }
        }

        const formatInput = (event) => {
            let value = event.target.value.replace(/\./g, ''); // Remove existing dots
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every three digits
            nominal.value = value;
        };

        const formatInputNisabZakat = (event) => {
            let value = event.target.value.replace(/\./g, ''); // Remove existing dots
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every three digits
            nisabZakat.value = value;
        }

        const formatRupiah = (val) => {
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        watch(nominal, (newValue) => {
            donasi.nominal = newValue.toString().replace(/\./g, ''); // Remove dots for the actual value
        });

        watch(nisabZakat, async (newNisabZakat) => {
            donasi.nisab_zakat = newNisabZakat.toString().replace(/\./g, ''); // Remove dots for the actual value
        })

        return {
            donasi,
            satuans,
            validation,
            nominal,
            minimalNisabZakat,
            nisabZakat,
            numOnly,
            submit,
            getSatuan,
            getNominal,
            setBeratBeras,
            formatInput,
            numOnly,
            isMinimalNisabZakat,
            formatInputNisabZakat,
            getSatuanEmas,
            formatRupiah,
            setNominalZakatMaal
        }
    }
}
</script>
<style scoped>
.row{
    margin-bottom: 20px;
}
    p{
        color: #969595;
    }

    button{
        border: none;
        width: 200px;
        height: 50px;
        background-color: transparent;
        border: 2px solid rgb(255, 127, 0, 1);
        border-radius: 5px;
        margin-bottom: 20px;
        transition-duration: 0.4s;
        font-weight: bold;
    }

    button:hover{
        background-color: rgb(255, 127, 0, 1);
        color: white;
    }
</style>