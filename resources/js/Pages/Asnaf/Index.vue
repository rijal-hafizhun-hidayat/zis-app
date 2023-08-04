<script setup defer>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import { router } from '@inertiajs/vue3'

const props = defineProps({
    asnafs: Object
})

const routeCreateAsnaf = () => {
    router.visit('/asnaf/add', {
        method: 'get'
    })
}

const show = (id) => {
    router.get(`/asnaf/${id}`)
}
</script>
<template>
    <Head>
        <title>Asnaf</title>
    </Head>
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="mt-2">Asnaf</div>
                                <div class="ms-auto mt-1">
                                    <!-- <Link :href="'/infaq/add'" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></Link> -->
                                    <!-- <button @click="routeCreateAsnaf" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></button> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 400px; overflow: auto" class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Golongan Zakat</th>
                                            <th scope="col">Total Nominal</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr v-for="(asnaf, index) in asnafs" :key="asnaf.id">
                                            <td>{{ index+1 }}</td>
                                            <td>{{ asnaf.golongan_zakat }}</td>
                                            <td>{{ asnaf.total }}</td>
                                            <td>
                                                <button @click="destroy(asnaf.id)" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></button>
                                                <button @click="show(asnaf.id)" class="btn btn-warning me-2"><i class="fa-solid fa-pen-to-square"></i></button>
                                            </td>
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