<template>
    <Head>
        <title>Zakat</title>
    </Head>
    <NavBar />
    <main class="py-5">
        <div class="container">
            <!-- <img :src="image" alt=""> -->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="">
                                <form action="/laporan/zakat" method="post" target="_blank">
                                    <div class="d-flex">
                                        <div class="mt-2">Pemasukan Zakat</div>
                                        <div class="ms-3">
                                            <select v-model="filter.bulan" name="bulan" class="form-select" aria-label="Default select example">
                                                <option selected disabled value="">-- Pilih Bulan --</option>
                                                <option v-for="bulan in bulans" :value="bulan">{{ bulan }}</option>
                                            </select>
                                        </div>
                                        <div class="ms-3">
                                            <select v-model="filter.jenis_zakat" name="jenis_zakat" class="form-select" aria-label="Default select example">
                                                <option selected disabled value="">-- Pilih Jenis Zakat --</option>
                                                <option value="Zakat Fitrah">Zakat Fitrah</option>
                                                <option value="Zakat Maal">Zakat Maal</option>
                                            </select>
                                        </div>
                                        <div class="ms-3">
                                            <select v-model="filter.satuan" name="sha_id" class="form-select" aria-label="Default select example">
                                                <option selected disabled value="">-- Pilih Satuan --</option>
                                                <option value="2">Uang</option>
                                                <option value="1">Beras</option>
                                            </select>
                                        </div>
                                        <div class="ms-3">
                                            <input type="search" v-model="filter.nama_donatur" name="nama_donatur" class="form-control" placeholder="Cari Nama Donatur .....">
                                        </div>
                                        <div class="ms-3">
                                            <Link :href="'/zakat'" class="btn btn-secondary">Reset</Link>
                                        </div>
                                        <div class="ms-3">
                                            <button type="submit" class="btn btn-danger">PDF</button>
                                        </div>
                                        <div class="ms-auto mt-1">
                                            <button type="button" @click="create()" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 500px; overflow: auto" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Donatur</th>
                                            <th scope="col">Nomor Hp</th>
                                            <th scope="col">Tanggal Zakat</th>
                                            <th scope="col">Jenis Zakat</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Berat Beras</th>
                                            <!-- <th scope="col">Jumlah</th> -->
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Bulan</th>
                                            <th scope="col">Bukti Pembayaran</th>
                                            <th scope="col">Status Pembayaran</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(zakat, index) in zakats" :key="zakat.id">
                                            <th scope="row">{{ index+1 }}</th>
                                            <td>{{ zakat.nama_donatur }}</td>
                                            <td>{{ zakat.nomor_hp }}</td>
                                            <td>{{ timezone(zakat.waktu_zakat) }}</td>
                                            <td>{{ zakat.jenis_zakat }}</td>
                                            <td>{{ zakat.nama }}</td>
                                            <td>{{ zakat.berat_beras }}</td>
                                            <td v-if="zakat.nominal">{{ numberWithDots(zakat.nominal) }}</td>
                                            <td v-else></td>
                                            <td>{{ zakat.bulan }}</td>
                                            <td><Modal :image="zakat.bukti_pembayaran" :path="'Zakat'" :id="zakat.id"/></td>
                                            <td v-if="zakat.confirmed == 1"><button type="button" class="btn btn-success" disabled><i class="fa-solid fa-circle-check"></i></button></td>
                                            <td v-else><button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-circle-xmark"></i></button></td>
                                            <td>
                                                <Link as="button" @click="destroy(zakat.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></Link>
                                                <Link :href="`/zakat/${zakat.id}`" class="btn btn-warning me-2"><i class="fa-solid fa-pen-to-square"></i></Link>
                                                <button class="btn btn-success" @click="confirmed(zakat.id)"><i class="fa-solid fa-stamp"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Total</th>
                                            <td></td>
                                            <th>{{ totalBeratBeras }} Kg</th>
                                            <td>{{ numberWithDots(total) }}</td>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
import Modal from '../Components/Modal.vue'
import Pagination from './Components/Pagination.vue'
import { ref, watch, reactive } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import axios from 'axios'
import Swal from 'sweetalert2'
import moment from 'moment';
import NProgress from 'nprogress';
export default {
    components: { NavBar, Footer, Link, Head, Modal, Pagination },
    props: {
        zakats: Object,
        total: Number,
        totalBeratBeras: Number,
        image: String
    },
    setup(props) {
        console.log(props.zakats)
        const bulans = ref(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'])
        const searchNamaDonatur = ref('')
        const filter = reactive({
            bulan: '',
            jenis_zakat: '',
            satuan: '',
            nama_donatur: ''
        })

        function destroy(id){
            Swal.fire({
                title: 'Hapus Data?',
                text: "bukti pembayaran akan terhapus dari database",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Konfirmasi'
            })
            .then((result) => {
                if(result.isConfirmed){
                    NProgress.start()
                    axios.delete(`/zakat/${id}`)
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
                    .finally(() => {
                        NProgress.done()
                    })
                }
            })
        }

        function create(){
            router.visit('/zakat/add', {
                method: 'get',
            })
        }

        function timezone(value){
            moment.locale('id');
            return moment(value).format('LL'); 
        }

        function numberWithDots(x) {
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function report(){
            window.open('/report')
        }

        function showImage(image){
            return router.get(`/zakat/showImage/${image}`)
        }

        function confirmed(id){
            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                text: "Tidak bisa di kembalikan jika sudah terkonfirmasi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Konfirmasi'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    NProgress.start()
                    axios.put(`/zakat/confirmed/${id}`)
                    .then((res) => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.text,
                            icon: 'success',
                        })
                        router.get('/zakat')
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                    .finally(() => {
                        NProgress.done()
                    })
                }
            })
        }

        function search(){
            router.get('/zakat', {
                search: searchQuery.value
            })
        }

        function printPdf(filter){
            window.open('/report')
        }

        watch(filter, async(newFilter, oldNamaDonatur) => {
            router.get(`/zakat`, {
                filters: newFilter
            }, {
                preserveState: true
            })
        })

        return {
            bulans,
            searchNamaDonatur,
            filter,
            destroy,
            create,
            timezone,
            numberWithDots,
            report,
            confirmed,
            showImage,
            search,
            printPdf
        }
    },
}
</script>