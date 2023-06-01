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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Pemasukan Zakat</div>
                            <input type="search" v-model="searchQuery" class="search-form" placeholder="Cari Nama Donatur .....">
                            <!-- <Link href="#" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link> -->
                            <button class="btn btn-primary btn-sm" @click="create()"><i class="fa-solid fa-plus"></i></button>
                            <!-- <button @click="report()" class="btn btn-success">cetak</button> -->
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
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Bukti Pembayaran</th>
                                            <th scope="col">Status Pembayaran</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(zakat, index) in searchedZakats" :key="zakat.id">
                                            <th scope="row">{{ index+1 }}</th>
                                            <td>{{ zakat.nama_donatur }}</td>
                                            <td>{{ zakat.nomor_hp }}</td>
                                            <td>{{ timezone(zakat.waktu_zakat) }}</td>
                                            <td>{{ zakat.jenis_zakat }}</td>
                                            <td>{{ zakat.nama }}</td>
                                            <td>{{ zakat.berat_beras }}</td>
                                            <td>{{ zakat.jumlah }}</td>
                                            <td v-if="zakat.nominal">{{ numberWithDots(zakat.nominal) }}</td>
                                            <td v-else></td>
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
                                            <td></td>
                                            <th>{{ numberWithDots(total) }}</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <Pagination class="mt-6" :links="zakats.links" /> -->
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
import { ref, computed } from 'vue'
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
        const searchQuery = ref('')
        const searchedZakats = computed(() => {
            return props.zakats.filter((zakat) => {
                return (
                    zakat.nama_donatur
                        .toLowerCase()
                        .indexOf(searchQuery.value.toLowerCase()) != -1 
                );
            });
        });

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
                    NProgress.done()
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
                    axios.put(`/zakat/confirmed/${id}`)
                    .then((res) => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.text,
                            icon: 'success',
                        })
                        return router.get('/zakat')
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                }
            })
        }

        function search(){
            router.get('/zakat', {
                search: searchQuery.value
            })
        }

        // watch(searchQuery, async (newSearchQuery, oldSearchQuery) => {
        //     // console.log(newSearchQuery)
        //     router.get('/zakat', {
        //         search: newSearchQuery
        //     })
        // })

        return {
            searchQuery,
            searchedZakats,
            destroy,
            create,
            timezone,
            numberWithDots,
            report,
            confirmed,
            showImage,
            search
        }
    },
}
</script>
<!-- <style scoped>
    thead, tbody { display: block; }

    tbody {
        height: 100px;       /* Just for the demo          */
        overflow-y: auto;    /* Trigger vertical scroll    */
        overflow-x: hidden;  /* Hide the horizontal scroll */
    }
</style> -->