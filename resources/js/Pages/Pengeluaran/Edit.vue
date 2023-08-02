<template>
    <Head title="Edit Pengeluaran" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Pengeluaran</div>
                        <div class="card-body">
                            <form @submit.prevent="submit()" class="mt-3">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Keterengan</label>
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
                                <div v-if="form.jenis_dana == 'Shadaqah'" class="mb-3">
                                    <label for="barang" class="form-label">Pilih Barang</label>
                                    <select class="form-select" id="barang" v-model="form.id_shadaqah">
                                        <option disabled value="">-- Pilih --</option>
                                        <option  v-for="barang in barangs" :value="barang.id">{{ barang.keterangan }}</option>
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
                                    <label class="form-label" for="berat_beras">Berat Beras / kg (Jika ada penyaluran beras)</label>
                                    <div class="input-group has-validation">
                                        <input type="text" class="form-control" v-model="form.berat_beras" v-on:keypress="numOnly()" id="berat_beras" aria-describedby="basic-addon2" :class="{ 'is-invalid': validation.berat_beras }">
                                        <span class="input-group-text" id="basic-addon2">Kg</span>
                                        <div v-if="validation.berat_beras" class="invalid-feedback">
                                            {{ validation.berat_beras[0] }}
                                        </div>
                                    </div>
                                    
                                    <!-- <p class="invalid-feedback"></p> -->
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
                                    <label for="bukti_pengeluaran" class="form-label pe-2">Upload Bukti Pengeluaran Baru:</label>
                                    <input type="file" @input="form.new_bukti_pengeluaran = $event.target.files[0]" name="bukti_pengeluaran" id="bukti_pengeluaran" accept="image/*" :class="{ 'is-invalid': validation.new_bukti_pengeluaran }">
                                    <div v-if="validation.new_bukti_pengeluaran" class="invalid-feedback">
                                        {{ validation.new_bukti_pengeluaran[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label pe-2">Bukti Pengeluaran:</label>
                                    <Modal :image="form.old_bukti_pengeluaran" :path="'Pengeluaran'" :id="form.id"/>
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
import axios from 'axios';
import Swal from 'sweetalert2';
import { router, Head } from '@inertiajs/vue3'
import { reactive, ref, watch, onMounted } from 'vue';
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Modal, Head },
    props: {
        pengeluaran: Object
    },
    setup(props){
        console.log(props.pengeluaran)
        const form = reactive({
            id: props.pengeluaran.id,
            kebutuhan: props.pengeluaran.kebutuhan,
            jenis_dana: props.pengeluaran.jenis_dana,
            nama_organisasi: props.pengeluaran.nama_organisasi === null ? '' : props.pengeluaran.nama_organisasi,
            berat_beras: props.pengeluaran.berat_beras === null ? '' : props.pengeluaran.berat_beras,
            jumlah_mustahiq: props.pengeluaran.jumlah_mustahiq === null ? '' : props.pengeluaran.jumlah_mustahiq,
            nominal: props.pengeluaran.nominal === null ? '' : numberWithDots(props.pengeluaran.nominal),
            confirmed: props.pengeluaran.confirmed,
            id_shadaqah: props.pengeluaran.id_shadaqah,
            old_bukti_pengeluaran: props.pengeluaran.bukti_pengeluaran,
            new_bukti_pengeluaran: ''
        })

        const validation = ref([])
        const nominal = ref('')
        const barangs = ref([])
        nominal.value = form.nominal

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
            axios.post(`/pengeluaran/${props.pengeluaran.id}`, dataAppend(), {
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
                validation.value = err.response.data.errors
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

        function dataAppend(){
            let data = new FormData();
            data.append('kebutuhan', form.kebutuhan)
            data.append('nominal', form.nominal == null ? '' : form.nominal)
            data.append('jenis_dana', form.jenis_dana)
            data.append('id_shadaqah', form.id_shadaqah)
            data.append('nama_organisasi', form.nama_organisasi)
            data.append('berat_beras', form.berat_beras)
            data.append('jumlah_mustahiq', form.jumlah_mustahiq)
            data.append('bukti_pengeluaran', form.new_bukti_pengeluaran)
            data.append('confirmed', form.confirmed)

            data.append("_method", "PUT")

            return data;
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
            barangs,
            submit,
            numOnly,
            dataAppend,
            formatInput,
            numberWithDots,
            getBarangShadaqah
        }
    }
}
</script>