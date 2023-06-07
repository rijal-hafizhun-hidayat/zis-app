<template>
    <Head title="Edit Shadaqah" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Edit Shadaqah</div>
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
                                    <input type="text" v-on:keypress="numOnly()" @input="formatInput" class="form-control" v-model="nominal" id="nominal" :required="form.jenis_bantuan == 'Cash'" :class="{ 'is-invalid': validation.nominal }">
                                    <div v-if="validation.nominal" class="invalid-feedback">
                                        {{ validation.nominal[0] }}
                                    </div>
                                </div>
                                <div v-if="form.jenis_bantuan == 'Barang'" class="mb-3">
                                    <label for="jumlah" class="form-label">Keterangan</label>
                                    <input type="text" v-model="form.keterangan" class="form-control" id="jumlah" :required="form.jenis_bantuan == 'Barang'" :class="{ 'is-invalid': validation.keterangan }">
                                    <div v-if="validation.keterangan" class="invalid-feedback">
                                        {{ validation.keterangan[0] }}
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <label class="form-label pe-2">Update Bukti Pembayaran Baru:</label>
                                    <input type="file" @input="form.new_bukti_pembayaran = $event.target.files[0]" name="bukti_pembayaran" accept="image/*" :class="{ 'is-invalid': validation.new_bukti_pembayaran }">
                                    <div v-if="validation.new_bukti_pembayaran" class="invalid-feedback">
                                        {{ validation.new_bukti_pembayaran[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label pe-2">Bukti Pembayaran:</label>
                                    <Modal :image="form.old_bukti_pembayaran" :path="'Shadaqah'" :id="shadaqah.id"/>
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
import { reactive, ref, watch } from 'vue';
import { router, Head } from '@inertiajs/vue3'
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Modal, Head },
    props: {
        shadaqah: Object
    },
    setup(props){
        const form = reactive({
            id: props.shadaqah.id,
            nama_donatur: props.shadaqah.nama_donatur,
            nomor_hp: props.shadaqah.nomor_hp,
            jenis_bantuan: props.shadaqah.jenis_bantuan,
            keterangan: props.shadaqah.keterangan == null ? '' : props.shadaqah.keterangan,
            nominal: props.shadaqah.nominal == null ? '' : props.shadaqah.nominal,
            old_bukti_pembayaran: props.shadaqah.bukti_pembayaran,
            new_bukti_pembayaran: '',
            confirmed: props.shadaqah.confirmed
        })

        const validation = ref([])
        const nominal = ref('')
        nominal.value = numberWithDots(form.nominal)

        function submit(){
            NProgress.start()
            axios.post(`/shadaqah/${props.shadaqah.id}`,dataAppend(), {
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

        function dataAppend(){
            let data = new FormData();
            data.append('nama_donatur', form.nama_donatur)
            data.append('nomor_hp', form.nomor_hp)
            data.append('jenis_bantuan', form.jenis_bantuan)
            data.append('keterangan', form.keterangan)
            data.append('nominal', form.nominal)
            data.append('bukti_pembayaran', form.new_bukti_pembayaran)
            data.append('confirmed', form.confirmed)

            data.append("_method", "PUT")

            return data;
        }

        function numberWithDots(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

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