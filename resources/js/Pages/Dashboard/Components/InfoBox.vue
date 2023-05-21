<template>
    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Zakat
            </div>
            <div class="card-body">
                <h5 class="card-title">Total Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.zakat) }}</h1>
            </div>
        </div>
    </div>
    

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Infaq
            </div>
            <div class="card-body">
                <h5 class="card-title">Total Dana:</h5>
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
                <h5 class="card-title">Total Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.shadaqah) }}</h1>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card divide">
            <div class="card-header">
                Pengeluaran per {{ total.tanggal }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Total Dana:</h5>
                <h1 class="card-text">{{ numberWithDots(total.pengeluaran) }}</h1>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { onMounted, reactive } from 'vue';
import moment from 'moment';
export default{
    setup(){
        const total = reactive({
            zakat: '',
            infaq: '',
            shadaqah: '',
            pengeluaran: '',
            tanggal: moment().format('LL')
        })

        onMounted(() => {
            getSumZIS()
        })

        function getSumZIS(){
            axios.get('/getSumZIS')
            .then((res) => {
                total.zakat = res.data.zakat,
                total.infaq = res.data.infaq,
                total.shadaqah = res.data.shadaqah,
                total.pengeluaran = res.data.pengeluaran

            })
            .catch((err) => {
                console.log(err)
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
</style>