<template>
    <Head>
        <title>Infaq</title>
    </Head>
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div v-if="$page.props.flash.message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $page.props.flash.message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="mt-2">Pemasukan Infaq</div>
                                <div class="ms-3">
                                    <select v-model="filter.bulan" class="form-select" aria-label="Default select example">
                                        <option selected disabled value="">-- Pilih Bulan --</option>
                                        <option v-for="bulan in bulans" :value="bulan">{{ bulan }}</option>
                                    </select>
                                </div>
                                <div class="ms-3">
                                    <select v-model="filter.metode_pembayaran" class="form-select" aria-label="Default select example">
                                        <option selected disabled value="">-- Pilih Jenis Pembayaran --</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Rekening">Rekening</option>
                                    </select>
                                </div>
                                <div class="ms-3">
                                    <input type="search" v-model="filter.nama_donatur" class="form-control" placeholder="Cari Nama Donatur .....">
                                </div>
                                <div class="ms-3">
                                    <Link :href="'/infaq'" class="btn btn-secondary">Reset</Link>
                                </div>
                                <div class="ms-auto mt-1">
                                    <!-- <Link :href="'/infaq/add'" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link> -->
                                    <button @click="create()" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 400px; overflow: auto" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Donatur</th>
                                            <th scope="col">Nomor Hp</th>
                                            <th scope="col">Jenis Pembayaran</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Bulan</th>
                                            <th scope="col">Bukti Pembayaran</th>
                                            <th scope="col">Status Pembayaran</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(infaq, index) in infaqs" :key="infaq.id">
                                            <td>{{ index+1 }}</td>
                                            <td>{{ infaq.nama_donatur }}</td>
                                            <td>{{ infaq.nomor_hp }}</td>
                                            <td>{{ infaq.metode_pembayaran }}</td>
                                            <td>{{ numberWithDots(infaq.nominal) }}</td>
                                            <td>{{ infaq.bulan }}</td>
                                            <td><Modal :image="infaq.bukti_pembayaran" :path="'Infaq'" :id="infaq.id"/></td>
                                            <td v-if="infaq.confirmed == 1"><button type="button" class="btn btn-success" disabled><i class="fa-solid fa-circle-check"></i></button></td>
                                            <td v-else><button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-circle-xmark"></i></button></td>
                                            <td>
                                                <Link as="button" @click="destroy(infaq.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></Link>
                                                <Link :href="`/infaq/${infaq.id}`" class="btn btn-warning me-2"><i class="fa-solid fa-pen-to-square"></i></Link>
                                                <button class="btn btn-success" @click="confirmed(infaq.id)"><i class="fa-solid fa-stamp"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <th>{{ numberWithDots(total) }}</th>
                                            <td></td>
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
import Navbar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'
import Modal from '../Components/Modal.vue'
import { Link, router, Head } from '@inertiajs/vue3'
import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import NProgress from 'nprogress';
export default {
    components: { Navbar, Footer, Link, Head, Modal },
    props: {
        infaqs: Object,
        total: Number
    },
    setup(props){
        const bulans = ref(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'])
        const searchQuery = ref('')
        const filter = reactive({
            bulan: '',
            metode_pembayaran: '',
            nama_donatur: ''
        })

        function destroy(id){
            Swal.fire({
                title: 'Hapus Data?',
                text: "bukti pengeluaran akan terhapus dari database",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Konfirmasi'
            })
            .then((result) => {
                if(result.isConfirmed){
                    NProgress.start()
                    axios.delete(`/infaq/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: res.data.title,
                            text: res.data.message
                        })
                        router.get('/infaq')
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: err.data.title,
                            text: err.data.text
                        })
                        router.get('/infaq')
                    })
                    .finally(() => {
                        NProgress.done()
                    })
                }
            })
        }

        function numberWithDots(x) {
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
                    axios.put(`/infaq/confirmed/${id}`)
                    .then((res) => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.message,
                            icon: 'success',
                        })
                        router.get('/infaq')
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

        function create(){
            router.get('/infaq/add')
        }

        watch(filter, async (newFilter, oldFilter) => {
            router.get(`/infaq`, {
                filter: newFilter
            }, {
                preserveState: true
            })
        })

        return {
            searchQuery,
            bulans,
            filter,
            destroy,
            numberWithDots,
            confirmed,
            create
        }
    }
}
</script>