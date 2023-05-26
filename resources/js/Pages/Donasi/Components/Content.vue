<template>
    <div class="container">
        <div class="row">
            <p><span>DKM Masjid Keramat Megu</span> telah berhasil mengumpulkan total dana sekitar Rp. dan telah digunakan dan disalurkan ke pihak-pihak yang membutuhkan dan kegiatan yang bermanfaat. Berikut adalah rinciannya</p>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pengeluaran Zakat</h5>
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
                                <tr v-for="(zakat, index) in zakats">
                                    <th scope="row">{{ index+1 }}</th>
                                    <td>{{ zakat.nama_organisasi }}</td>
                                    <td>{{ zakat.jumlah_mustahiq }}</td>
                                    <td>{{ zakat.berat_beras }}</td>
                                    <td v-if="zakat.nominal">{{ numberWithDots(zakat.nominal) }}</td>
                                    <td v-else></td>
                                    <td>{{ timezone(zakat.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Pengeluaran Infaq</h5>
                    <div class="table-wrapper">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kebutuhan</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <tr v-for="(infaq, index) in infaqs">
                                    <th scope="row">{{ index+1 }}</th>
                                    <td>{{ infaq.kebutuhan }}</td>
                                    <td v-if="infaq.nominal">{{ numberWithDots(infaq.nominal) }}</td>
                                    <td v-else></td>
                                    <td>{{ timezone(infaq.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Pengeluaran Shadaqah</h5>
                    <div class="table-wrapper">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kebutuhan</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <tr v-for="(shadaqah, index) in shadaqahs">
                                    <th scope="row">{{ index+1 }}</th>
                                    <td>{{ shadaqah.kebutuhan }}</td>
                                    <td v-if="shadaqah.nominal">{{ numberWithDots(shadaqah.nominal) }}</td>
                                    <td v-else></td>
                                    <td>{{ timezone(shadaqah.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
        const zakats = ref([])
        const infaqs = ref([])
        const shadaqahs = ref([])
        
        onMounted(() => {
            axios.get('/getDonasi')
            .then((res) => {
                zakats.value = res.data.data.zakat
                infaqs.value = res.data.data.infaq
                shadaqahs.value = res.data.data.shadaqah
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
            zakats,
            infaqs,
            shadaqahs,
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

    /* .table-wrapper {
        max-height: 250px;
        overflow: auto;
    } */
</style>