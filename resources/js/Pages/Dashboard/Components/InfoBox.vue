<template>
    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Fakir
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_fakir) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Miskin
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_miskin) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Amil
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_amil) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Mu'allaf
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_muallaf) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Riqab
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_riqab) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Gharimin
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_gharimin) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Fisablillah
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_fisabilillah) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat Ibnu Sabil
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat_ibnu_sabil) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Infaq
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.infaq) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Shadaqah
            </div>
            <div class="card-body">
                <h5 class="card-title">Sisa Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.shadaqah) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Pengeluaran per bulan {{ total.bulan }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Total Pengeluaran:</h5>
                <h1 class="card-text">{{ numberWithDots(total.pengeluaran) }}</h1>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { onMounted, reactive } from 'vue';
import NProgress from 'nprogress';
export default{
    setup(){
        const total = reactive({
            zakat_fakir: '',
            zakat_miskin: '',
            zakat_amil: '',
            zakat_muallaf: '',
            zakat_riqab: '',
            zakat_gharimin: '',
            zakat_fisabilillah: '',
            zakat_ibnu_sabil: '',
            infaq: '',
            shadaqah: '',
            pengeluaran: '',
            bulan: ''
        })

        onMounted(() => {
            getSumZIS()
        })

        function getSumZIS(){
            NProgress.start()
            axios.get('/getSumZIS')
            .then((res) => {
                total.zakat_fakir = res.data.zakat_fakir,
                total.zakat_miskin = res.data.zakat_miskin,
                total.zakat_amil = res.data.zakat_amil,
                total.zakat_muallaf = res.data.zakat_muallaf,
                total.zakat_riqab = res.data.zakat_riqab,
                total.zakat_gharimin = res.data.zakat_gharimin,
                total.zakat_fisabilillah = res.data.zakat_fisabilillah,
                total.zakat_ibnu_sabil = res.data.zakat_ibnu_sabil,
                total.infaq = res.data.infaq,
                total.shadaqah = res.data.shadaqah,
                total.pengeluaran = res.data.pengeluaran,
                total.bulan = res.data.bulan
                console.log(total)
            })
            .catch((err) => {
                console.log(err)
            })
            .finally(() => {
                NProgress.done()
            })
        }

        function numberWithDots(x) {
            return 'Rp. ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        return {
            total,
            getSumZIS,
            numberWithDots
        }
    }
}
</script>
<style scoped>
    @media (max-width: 576px) {
        .divide{
            margin-bottom: 20px;
        }
    }

    @media (min-width: 992px) {
        .divide{
            margin-bottom: 30px;
        }
    }
</style>