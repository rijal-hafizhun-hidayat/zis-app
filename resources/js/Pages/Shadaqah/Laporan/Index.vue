<template>
    <Head title="Laporan Shadaqah" />
    <Navbar />
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div v-if="$page.props.flash.message" class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $page.props.flash.message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>Cetak Laporan Shadaqah</div>
                        </div>
                        <div class="card-body">
                            <form action="/laporan/shadaqah" method="post" target="_blank">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" class="form-select" id="bulan">
                                        <option selected value="">-- Semua --</option>
                                        <option v-for="bulan in bulans" :value="bulan">{{ bulan }}</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Cetak</button>
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
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
export default{
    components: { Navbar, Footer, Head },
    setup(){
        const bulans = ref(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'])

        return {
            bulans
        }
    }
}
</script>