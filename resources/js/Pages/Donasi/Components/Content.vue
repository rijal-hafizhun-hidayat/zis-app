<template>
    <div class="container">
        <div class="row">
            <p><span>DKM Masjid Keramat Megu</span> telah berhasil mengumpulkan total zakat sekitar Rp. dan telah disalurkan ke pihak-pihak yang membutuhkan. Berikut adalah rinciannya</p>
            <div class="table-wrapper">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Organisasi</th>
                            <th scope="col">Jumlah Mustahik</th>
                            <th scope="col">Berat Beras / Kg</th>
                            <th scope="col">Uang</th>
                            <th scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr v-for="(donasi, index) in donasis">
                            <th scope="row">{{ index+1 }}</th>
                            <td>{{ donasi.nama_organisasi }}</td>
                            <td>{{ donasi.jumlah_mustahiq }}</td>
                            <td>{{ donasi.berat_beras }}</td>
                            <td v-if="donasi.nominal">{{ numberWithDots(donasi.nominal) }}</td>
                            <td v-else></td>
                            <td>{{ timezone(donasi.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import moment from 'moment';
import { ref, onMounted } from 'vue'
export default{
    setup(){
        const donasis = ref([])
        onMounted(() => {
            axios.get('/getDonasi')
            .then((res) => {
                donasis.value = res.data.data
            })
            .catch((err) => {
                console.log(err)
            })
        })

        function timezone(value){
            //moment.locale('id')
            return moment(value).locale('id').format('ll') 
        }

        function numberWithDots(x) {
            return 'Rp ' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        return {
            donasis,
            timezone,
            numberWithDots
        }
    }
}
</script>
<style scoped>
    .row{
        padding-bottom: 100px;
    }
    span{
        font-weight: bold;
    }

    .table-wrapper {
        margin-top: 50px;
        max-height: 250px;
        overflow: auto;
    }
</style>