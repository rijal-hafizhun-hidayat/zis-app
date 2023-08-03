<script setup>
import { Head, router } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

const routeCreateAsnaf = () => {
    router.get('/asnaf/add')
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
                                    <button @click="routeCreateAsnaf" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></button>
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
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        
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