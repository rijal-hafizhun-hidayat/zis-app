<template>
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Makanan Pokok</div>
                            <input type="search" v-model="searchQuery" class="search-form" placeholder="Cari .....">
                            <Link href="/sha/add" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Nominal</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr v-for="(sha, index) in searchedMakananPokoks" :key="sha.id">
                                        <th scope="row">{{ index+1 }}</th>
                                        <td>{{ sha.nama }}</td>
                                        <td>Rp.{{ sha.harga }}</td>
                                        <td>
                                            <Link v-if="sha.nama != 'Beras' && sha.nama != 'Uang'" as="button" @click="destroy(sha.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></Link>
                                            <Link :href="`/sha/${sha.id}`" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
    components: { NavBar, Footer, Link },
    props: {
        shas: Object
    },
    setup(props) {

        const searchQuery = ref("")
        
        const searchedMakananPokoks = computed(() => {
            return props.shas.filter((shas) => {
                return (
                    shas.nama
                        .toLowerCase()
                        .indexOf(searchQuery.value.toLowerCase()) != -1 
                );
            });
        });

        function destroy(id){
            axios.delete(`/sha/${id}`)
            .then((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.data.message
                })
            })
            .catch((err) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'gagal hapus data'
                })
            })
        }

        return {
            destroy,
            searchQuery,
            searchedMakananPokoks
        }
    },
}
</script>