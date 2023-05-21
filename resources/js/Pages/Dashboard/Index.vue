<template>
    <Head title="Dashboard" />
    <NavBar />
    <main class="py-5">
        <div class="container">
            <div v-if="$page.props.flash.message" class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $page.props.flash.message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="row mb-4">
                <InfoBox />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Zakat</div>
                        <div class="card-body">
                            <ZakatChart />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Infaq</div>
                        <div class="card-body">
                            <InfaqChart />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="card">
                        <div class="card-header">Shadaqah</div>
                        <div class="card-body">
                            <ShadaqahChart />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="card">
                        <div class="card-header">Pengeluaran</div>
                        <div class="card-body">
                            <PengeluaranChart />
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
import ZakatChart from './Components/ZakatChart.vue'
import InfaqChart from './Components/InfaqChart.vue'
import ShadaqahChart from './Components/ShadaqahChart.vue'
import PengeluaranChart from './Components/PengeluaranChart.vue'
import InfoBox from './Components/InfoBox.vue'
import { computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'

export default {
    components: { NavBar, Footer, Head, ZakatChart, InfaqChart, ShadaqahChart, PengeluaranChart, InfoBox },
    setup() {

        const user = computed(() => usePage().props.user)

        return {
            user
        }
        
    },
}
</script>