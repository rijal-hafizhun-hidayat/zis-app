<template>
    <Head title="Pengeluaran" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Pengeluaran</div>
                            <input type="search" v-model="searchQuery" class="search-form" placeholder="Cari Keterangan.....">
                            <Link href="/pengeluaran/add" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link>
                        </div>
                        <div class="card-body">
                            <div style="height: 400px; overflow: auto" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Organisasi</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Jenis Dana</th>
                                            <th scope="col">Berat Beras</th>
                                            <th scope="col">Jumlah Mustahiq</th>
                                            <th scope="col">Nominal</th>
                                            <th scope="col">Bukti Pengeluaran</th>
                                            <th scope="col">Status Pengeluaran</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(pengeluaran, index) in searchedPengeluarans" :key="pengeluaran.id">
                                            <td>{{ index+1 }}</td>
                                            <td>{{ pengeluaran.nama_organisasi }}</td>
                                            <td>{{ pengeluaran.kebutuhan }}</td>
                                            <td>{{ pengeluaran.jenis_dana }}</td>
                                            <td>{{ pengeluaran.berat_beras }}</td>
                                            <td>{{ pengeluaran.jumlah_mustahiq }}</td>
                                            <td v-if="pengeluaran.nominal">{{ numberWithDots(pengeluaran.nominal) }}</td>
                                            <td v-else></td>
                                            <td><Modal :image="pengeluaran.bukti_pengeluaran" :path="'Pengeluaran'" :id="pengeluaran.id"/></td>
                                            <td v-if="pengeluaran.confirmed == 1"><button type="button" class="btn btn-success" disabled><i class="fa-solid fa-circle-check"></i></button></td>
                                            <td v-else><button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-circle-xmark"></i></button></td>
                                            <td>{{ timezone(pengeluaran.created_at) }}</td>
                                            <td>
                                                <Link as="button" @click="destroy(pengeluaran.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></Link>
                                                <Link :href="`/pengeluaran/${pengeluaran.id}`" class="btn btn-warning me-2"><i class="fa-solid fa-pen-to-square"></i></Link>
                                                <button class="btn btn-success" @click="confirmed(pengeluaran.id)"><i class="fa-solid fa-stamp"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <td></td>
                                            <th>{{ totalBeratBeras }} Kg</th>
                                            <td>{{ totalMustahiq }}</td>
                                            <th>{{ numberWithDots(total) }}</th>
                                            <td></td>
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
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Modal from '../Components/Modal.vue';
import { Link, router, Head } from '@inertiajs/vue3';
import moment from 'moment';
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, computed } from 'vue'
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Link, Modal, Head },
    props: {
        pengeluarans: Object,
        total: Number,
        totalBeratBeras: Number,
        totalMustahiq: Number
    },
    setup(props){

        const searchQuery = ref('')

        const searchedPengeluarans = computed(() => {
            return props.pengeluarans.filter((pengeluaran) => {
                return (
                    pengeluaran.kebutuhan
                        .toLowerCase()
                        .indexOf(searchQuery.value.toLowerCase()) != -1 
                );
            });
        });
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
                    axios.delete(`/pengeluaran/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: res.data.title,
                            text: res.data.text
                        })
                        router.get('/pengeluaran')
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: err.data.title,
                            text: err.data.text
                        })
                        router.get('/pengeluaran')
                    })
                    .finally(() => {
                        NProgress.done()
                    })
                }
            })
        }
        
        function confirmed(id){
            Swal.fire({
                title: 'Konfirmasi Pengeluaran?',
                text: "Tidak bisa di kembalikan jika sudah terkonfirmasi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Konfirmasi'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    NProgress.done()
                    axios.put(`/pengeluaran/confirmed/${id}`)
                    .then((res) => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.text,
                            icon: 'success',
                        })
                        router.get('/pengeluaran')
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                    NProgress.done()
                }
            })
        }

        function timezone(value){
            moment.locale('id');
            return moment(value).format('LL'); 
        }
        
        function numberWithDots(x) {
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        return {
            destroy,
            timezone,
            numberWithDots,
            confirmed,
            searchQuery,
            searchedPengeluarans
        }
    }
}
</script>