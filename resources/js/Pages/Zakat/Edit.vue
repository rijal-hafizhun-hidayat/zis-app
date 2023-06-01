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
                                    <input type="text" v-model="zakat.nama_donatur" class="form-control" id="nama_donatur" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor Hp</label>
                                    <input type="text" v-model="zakat.nomor_hp" class="form-control" id="nomor_hp" v-on:keypress="NumbersOnly()" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_zakat" class="form-label">Jenis Zakat</label>
                                    <select v-model="zakat.jenis_zakat" class="form-select" id="jenis_zakat" required>
                                        <option selected disabled value="">-- Pilih --</option>
                                        <option value="Zakat Fitrah">Zakat Fitrah</option>
                                        <option value="Zakat Maal">Zakat Maal</option>
                                    </select>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah'" class="mb-3">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <select v-model="zakat.sha_id" class="form-select" id="satuan" required>
                                        <option selected disabled value="">-- pilih --</option>
                                        <option v-for="sha in shas" :value="sha.id" :key="sha.id">{{ sha.nama }}</option>
                                    </select>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah'" class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah / 1 Beras di hitung 2,5 Kg</label>
                                    <input @change="setBeratBeras(zakat.jumlah, zakat.sha_id)" type="text" v-model="zakat.jumlah" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" required>
                                </div>
                                <div v-if="zakat.jenis_zakat == 'Zakat Fitrah' && zakat.sha_id == 1" class="mb-3">
                                    <label for="jumlah" class="form-label">Berat Beras / kg</label>
                                    <input type="text" disabled v-model="zakat.berat_beras" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" :required="zakat.sha_id == 1">
                                </div>
                                <div v-if="zakat.sha_id == 2 || zakat.jenis_zakat == 'Zakat Maal'" class="mb-3">
                                    <label for="jumlah" class="form-label">Nominal</label>
                                    <input type="text" :disabled="zakat.jenis_zakat == 'Zakat Fitrah'" v-model="zakat.nominal" class="form-control" v-on:keypress="NumbersOnly()" id="jumlah" required>
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
import { reactive } from 'vue';
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
            berat_beras: props.zakat.berat_beras === null ? '' : props.zakat.berat_beras ,
            jumlah: props.zakat.jumlah === null ? '' : props.zakat.jumlah,
            nominal: props.zakat.nominal === null ? '' : props.zakat.nominal,
            old_bukti_pembayaran: props.zakat.bukti_pembayaran,
            confirmed: props.zakat.confirmed,
            new_bukti_pembayaran: ''
        })

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
                Swal.fire({
                    icon: 'error',
                    title: err.data.title,
                    text: err.data.text
                })
            })
            NProgress.done()
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
                zakat.nominal = res.data.data[0].harga*jumlah
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
            submit,
            setTotalNominal,
            NumbersOnly,
            setTotalNominal,
            setBeratBeras,
            dataAppend
        }
    }
}
</script>