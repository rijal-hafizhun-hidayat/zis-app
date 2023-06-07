<template>
    <Head title="Edit Zakat" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Edit Zakat</div>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="submit()">
                                <div class="mb-3">
                                    <label for="nama_donatur" class="form-label">Nama Donatur</label>
                                    <input type="text" v-model="zakat.nama_donatur" class="form-control" id="nama_donatur" :class="{ 'is-invalid': validation.nama_donatur } ">
                                    <div v-if="validation.nama_donatur" class="invalid-feedback">
                                        {{ validation.nama_donatur[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor Hp</label>
                                    <input type="text" v-model="zakat.nomor_hp" class="form-control" id="nomor_hp" v-on:keypress="NumbersOnly()" :class="{ 'is-invalid': validation.nomor_hp }" >
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
                                    <input @change="setBeratBeras(zakat.jumlah, zakat.sha_id)" type="text" v-model="zakat.jumlah" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :class="{ 'is-invalid': validation.jumlah }">
                                    <div v-if="validation.jumlah" class="invalid-feedback">
                                        {{ validation.jumlah[0] }}
                                    </div>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah' && zakat.sha_id == 1" class="mb-3">
                                    <label for="jumlah" class="form-label">Berat Beras / kg</label>
                                    <div class="input-group has-validation">
                                        <input type="text" disabled v-model="zakat.berat_beras" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :class="{ 'is-invalid': validation.berat_beras }" :required="zakat.sha_id == 1" />
                                        <span class="input-group-text" id="basic-addon2">Kg</span>
                                        <div v-if="validation.berat_beras" class="invalid-feedback">
                                            {{ validation.berat_beras[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div v-if="zakat.sha_id == 2 || zakat.jenis_zakat == 'Zakat Maal'" class="mb-3">
                                    <label for="jumlah" class="form-label">Nominal</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" :disabled="zakat.jenis_zakat == 'Zakat Fitrah'" v-model="nominal" class="form-control" v-on:keypress="NumbersOnly()" @input="formatInput" id="jumlah" :class="{ 'is-invalid': validation.nominal }">
                                        <div v-if="validation.nominal" class="invalid-feedback">
                                            {{ validation.nominal[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label pe-2">Upload Bukti Pembayaran Baru:</label>
                                    <input type="file" @input="zakat.new_bukti_pembayaran = $event.target.files[0]" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label pe-2">Bukti Pembayaran:</label>
                                    <Modal :image="zakat.old_bukti_pembayaran" :path="'Zakat'" :id="zakat.id"/>
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
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Modal from '../Components/Modal.vue';
import { reactive, ref, watch } from 'vue';
import { router, Head } from '@inertiajs/vue3'
import axios from 'axios';
import Swal from 'sweetalert2';
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Modal, Head },
    props: {
        zakat: Object,
        shas: Object
    },
    setup(props){
        const zakat = reactive({
            id: props.zakat.id,
            nama_donatur: props.zakat.nama_donatur,
            nomor_hp: props.zakat.nomor_hp,
            jenis_zakat: props.zakat.jenis_zakat,
            sha_id: props.zakat.sha_id,
            berat_beras: props.zakat.berat_beras === null ? '' : props.zakat.berat_beras,
            jumlah: props.zakat.jumlah === null ? '' : props.zakat.jumlah,
            nominal: props.zakat.nominal === null ? '' : props.zakat.nominal,
            old_bukti_pembayaran: props.zakat.bukti_pembayaran,
            confirmed: props.zakat.confirmed,
            new_bukti_pembayaran: ''
        })

        const validation = ref([])
        const nominal = ref('')
        nominal.value = numberWithDots(zakat.nominal)

        function submit(){
            NProgress.start()
            axios.post(`/zakat/${props.zakat.id}`,dataAppend() ,{
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

                router.get('/zakat')
            })
            .catch((err) => {
                validation.value = err.response.data.errors
            })
            .finally(() => {
                NProgress.done()
            })
        }

        function NumbersOnly(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }

        function setTotalNominal(jumlah){
            axios.get(`/getNominalSha/${zakat.sha_id}`)
            .then((res) => {
                nominal.value = res.data.data[0].harga*jumlah
                nominal.value = nominal.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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

        const formatInput = (event) => {
            let value = event.target.value.replace(/\./g, ''); // Remove existing dots
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add dots every three digits
            nominal.value = value;
        };

        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        watch(nominal, (newValue) => {
            zakat.nominal = newValue.toString().replace(/\./g, ''); // Remove dots for the actual value
        });

        function dataAppend(){
            let data = new FormData();
            
            data.append('nama_donatur', zakat.nama_donatur)
            data.append('nomor_hp', zakat.nomor_hp)
            data.append('jenis_zakat', zakat.jenis_zakat)
            data.append('sha_id', zakat.sha_id)
            data.append('jumlah', zakat.jumlah)
            data.append('berat_beras', zakat.berat_beras)
            data.append('nominal', zakat.nominal)
            data.append('bukti_pembayaran', zakat.new_bukti_pembayaran)
            data.append('confirmed', zakat.confirmed)

            data.append("_method", "PUT")

            return data;
        }

        return {
            zakat,
            validation,
            nominal,
            submit,
            setTotalNominal,
            NumbersOnly,
            setTotalNominal,
            setBeratBeras,
            dataAppend,
            formatInput,
            numberWithDots
        }
    }
}
</script>