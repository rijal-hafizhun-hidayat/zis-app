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
                                    <input type="text" v-model="form.kebutuhan" class="form-control" id="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rekening" class="form-label">Jenis Dana</label>
                                    <select class="form-select" v-model="form.jenis_dana" aria-label="Default select example" required>
                                        <option disabled selected value="">-- pilih --</option>
                                        <option value="Zakat">Zakat</option>
                                        <option value="Infaq">Infaq</option>
                                        <option value="Shadaqah">Shadaqah</option>
                                    </select>
                                </div>
                                <div v-if="form.jenis_dana == 'Zakat'" class="mb-3">
                                    <label for="nama_organisasi" class="form-label">Nama Organisasi</label>
                                    <input type="text" v-model="form.nama_organisasi" class="form-control" id="nama_organisasi">
                                </div>
                                <div class="mb-3">
                                    <label for="berat_beras">Berat Beras / kg (Jika ada penyaluran beras)</label>
                                    <input type="text" class="form-control" v-model="form.berat_beras" v-on:keypress="numOnly()" id="berat_beras">
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_mustahiq" class="form-label">Jumlah Mustahiq</label>
                                    <input type="text" v-model="form.jumlah_mustahiq" v-on:keypress="numOnly()" class="form-control" id="jumlah_mustahiq">
                                </div>
                                <div class="mb-3">
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <input type="text" v-model="form.nominal" v-on:keypress="numOnly()" class="form-control" id="nominal">
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pengeluaran" class="form-label pe-2">Upload Bukti Pengeluaran Baru:</label>
                                    <input type="file" @input="form.new_bukti_pengeluaran = $event.target.files[0]" name="bukti_pengeluaran" id="bukti_pengeluaran" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label pe-2">Bukti Pengeluaran:</label>
                                    <Modal :image="form.old_bukti_pengeluaran" :path="'Pengeluaran'" :id="form.id"/>
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
import Modal from '../Components/Modal.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { router, Head } from '@inertiajs/vue3'
import { reactive } from 'vue';
export default{
    components: { Navbar, Footer, Modal, Head },
    props: {
        pengeluaran: Object
    },
    setup(props){
        const form = reactive({
            id: props.pengeluaran.id,
            kebutuhan: props.pengeluaran.kebutuhan,
            jenis_dana: props.pengeluaran.jenis_dana,
            nama_organisasi: props.pengeluaran.nama_organisasi,
            berat_beras: props.pengeluaran.berat_beras,
            jumlah_mustahiq: props.pengeluaran.jumlah_mustahiq,
            nominal: props.pengeluaran.nominal,
            confirmed: props.pengeluaran.confirmed,
            old_bukti_pengeluaran: props.pengeluaran.bukti_pengeluaran,
            new_bukti_pengeluaran: ''
        })

        function submit(){
            axios.post(`/pengeluaran/${props.pengeluaran.id}`, {
                kebutuhan: form.kebutuhan,
                nominal: form.nominal,
                jenis_dana: form.jenis_dana,
                nama_organisasi: form.nama_organisasi,
                berat_beras: form.berat_beras,
                jumlah_mustahiq: form.jumlah_mustahiq,
                bukti_pengeluaran: form.new_bukti_pengeluaran,
                confirmed: form.confirmed
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

                return router.get('/pengeluaran')
            })
            .catch((err) => {
                return Swal.fire({
                    icon: 'error',
                    title: err.data.title,
                    text: err.data.text
                })
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
            data.append('nominal', form.nominal)
            data.append('jenis_dana', form.jenis_dana)
            data.append('nama_organisasi', form.nama_organisasi)
            data.append('berat_beras', form.berat_beras)
            data.append('jumlah_mustahiq', form.jumlah_mustahiq)
            data.append('bukti_pengeluaran', form.new_bukti_pengeluaran)
            data.append('confirmed', form.confirmed)

            if(form.nama_organisasi == ''){
                data.delete('nama_organisasi')
            }

            if(form.berat_beras == ''){
                data.delete('berat_beras')
            }

            if(form.jumlah_mustahiq == ''){
                data.delete('jumlah_mustahiq')
            }

            data.append("_method", "PUT")

            return data;
        }

        return {
            form,
            submit,
            numOnly,
            dataAppend
        }
    }
}
</script>