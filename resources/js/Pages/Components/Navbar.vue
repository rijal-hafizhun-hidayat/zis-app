<template>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <!-- <a class="navbar-brand" href="#">ZIS-APP</a> -->
            <Link class="navbar-brand" href="/dashboard">ZIS-APP</Link>
            <button v-if="user.name" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div v-if="user.name" class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li v-if="user.role == 1 || user.role == 2" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Zakat
                        </a>
                        <ul class="dropdown-menu">
                            <li><Link href="/zakat" class="dropdown-item">Data</Link></li>
                            <li><Link href="/sha" class="dropdown-item">Satuan</Link></li>
                            <li><Link @click="laporanZakat()" class="dropdown-item">Laporan</Link></li>
                            <!-- <li><a @click="laporanZakat()" class="dropdown-item">Laporan</a></li> -->
                            <!-- <li><a class="dropdown-item" href="#">Print</a></li> -->
                        </ul>
                    </li>
                    <li v-if="user.role == 1 || user.role == 2" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Infaq
                        </a>
                        <ul class="dropdown-menu">
                            <li><Link href="/infaq" class="dropdown-item">Data</Link></li>
                            <li><a class="dropdown-item" @click="laporanInfaq()" href="#">Laporan</a></li>
                        </ul>
                    </li>
                    <li v-if="user.role == 1 || user.role == 2" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Shadaqah
                        </a>
                        <ul class="dropdown-menu">
                            <li><Link href="/shadaqah" class="dropdown-item">Data</Link></li>
                            <li><a class="dropdown-item" @click="laporanShadaqah()" href="#">Laporan</a></li>
                        </ul>
                    </li>
                    <li v-if="user.role == 1" class="nav-item">
                        <Link href="/akun" class="nav-link">Akun</Link>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pengeluaran
                        </a>
                        <ul class="dropdown-menu">
                            <li><Link href="/pengeluaran" class="dropdown-item">Data</Link></li>
                            <li><a class="dropdown-item" @click="laporanPengeluaran()" href="#">Laporan</a></li>
                        </ul>
                    </li>
                </ul>
                <div id="user" class="d-flex dropdown">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ user.name }}</a>
                            <ul class="dropdown-menu">
                                <li><Link class="dropdown-item" href="/profile">Profile</Link></li>
                                <li>
                                    <button @click="logout()" class="dropdown-item">Log Out</button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>
<script>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import NProgress from 'nprogress';
import axios from 'axios'
export default {
    components: { Link },
    props: {
        user: Object
    },
    setup(props) {
        const user = computed(() => usePage().props.user)

        function logout(){
            NProgress.start()
            axios.post('/logout')
            .then((res) => {
                router.get('/login')
            })
            .catch((err) => {
                console.log(err)
            })
            .finally(() => {
                NProgress.done()
            })
        }

        function laporanZakat(){
            router.get('/laporan/zakat')
        }

        function laporanPengeluaran(){
            router.get('/laporan/pengeluaran')
        }

        function laporanInfaq(){
            router.get('/laporan/infaq')
        }

        function laporanShadaqah(){
            router.get('/laporan/shadaqah')
        }

        return {
            user,
            logout,
            laporanZakat,
            laporanPengeluaran,
            laporanInfaq,
            laporanShadaqah
        }    
    },
}
</script>

<style scoped>
@media (min-width: 992px) {
    nav .container{
        width: 1200px;
    }
}
</style>