<template>
    <Head>
        <title>Shadaqah</title>
    </Head>
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Pemasukan Shadaqah</div>
                            <input type="search" v-model="searchQuery" class="search-form" placeholder="Cari Nama.....">
                            <Link href="/shadaqah/add" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link>
                        </div>
                        <div class="card-body">
                            <div style="height: 500px; overflow: auto" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Donatur</th>
                                            <th scope="col">Nomor Hp</th>
                                            <th scope="col">Jenis Bantuan</th>
                                            <th scope="col">Keterangan</th>
                                            <th scrope="col">Nominal</th>
                                            <th scope="col">Bukti Pembayaran</th>
                                            <th scope="col">Status Pembayaran</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(shadaqah, index) in searchedSahadaqahs" :key="shadaqah.id">
                                            <td>{{ index+1 }}</td>
                                            <td>{{ shadaqah.nama_donatur }}</td>
                                            <td>{{ shadaqah.nomor_hp }}</td>
                                            <td>{{ shadaqah.jenis_bantuan }}</td>
                                            <td>{{ shadaqah.keterangan }}</td>
                                            <td v-if="shadaqah.nominal != NULL">{{ numberWithDots(shadaqah.nominal) }}</td>
                                            <td v-else></td>
                                            <td><Modal :image="shadaqah.bukti_pembayaran" :path="'Shadaqah'" :id="shadaqah.id"/></td>
                                            <td v-if="shadaqah.confirmed == 1"><button type="button" class="btn btn-success" disabled><i class="fa-solid fa-circle-check"></i></button></td>
                                            <td v-else><button type="button" class="btn btn-danger" disabled><i class="fa-solid fa-circle-xmark"></i></button></td>
                                            <td>
                                                <Link as="button" @click="destroy(shadaqah.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></Link>
                                                <Link :href="`/shadaqah/${shadaqah.id}`" class="btn btn-warning me-2"><i class="fa-solid fa-pen-to-square"></i></Link>
                                                <button class="btn btn-success" @click="confirmed(shadaqah.id)"><i class="fa-solid fa-stamp"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Total</th>
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
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Modal from '../Components/Modal.vue';
import { Link, router, Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue'
import Swal from 'sweetalert2';
import NProgress from 'nprogress';
export default{
    components: { Navbar, Footer, Link, Head, Modal },
    props: {
        shadaqahs: Object,
        total: Number
    },
    setup(props){
        const searchQuery = ref('')

        const searchedSahadaqahs = computed(() => {
            return props.shadaqahs.filter((shadaqah) => {
                return (
                    shadaqah.nama_donatur
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
                    axios.delete(`/shadaqah/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: res.data.text,
                            text: res.data.text
                        })

                        return router.get('/shadaqah')
                    })
                    .catch((err) => {
                        return Swal.fire({
                            icon: 'error',
                            title: err.data.title,
                            text: err.data.text
                        })
                    })
                }
            })
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
                    axios.put(`/shadaqah/confirmed/${id}`, {
                        confirmed: 1
                    })
                    .then((res) => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.text,
                            icon: 'success',
                        })
                        router.get('/shadaqah')
                    })
                    .catch((err) => {
                        console.log(err)
                    })
                    NProgress.done()
                }
            })
        }

        function numberWithDots(x) {
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        

        return {
            searchQuery,
            searchedSahadaqahs,
            destroy,
            numberWithDots,
            confirmed
        }
    }
}
</script>