<template>
    <div>
        <canvas id="chart-shadaqah"></canvas>
    </div>
</template>
<script>
import axios from 'axios';
import Chart from 'chart.js/auto';
import NProgress from 'nprogress';
import { onMounted } from 'vue';
export default{
    setup(){
        onMounted(() => {
            getShadaqah()
        })

        function getShadaqah(){
            NProgress.start()
            axios.get('/getShadaqah')
            .then((res) => {
                //parse total
                let jumlah = res.data.data
                let parseTotal = jumlah.map(a => a.total);

                //parse bulan
                let month = res.data.bulan
                let parseMonth = month.map(a => a.bulan);

                chartShadaqah(parseTotal, parseMonth)
            })
            .catch((err) => {
                console.log(err)
            })
            .finally(() => {
                NProgress.done()
            })
        }

        function chartShadaqah(parseTotal, parseMonth){
            const ctx = document.getElementById('chart-shadaqah');
            let plainChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: parseMonth,
                    datasets: [{
                        label: 'Dana',
                        data: parseTotal,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(66, 245, 135, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(66, 245, 135, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
                }
            });

            plainChart;
        }

        return {
            getShadaqah,
            chartShadaqah
        }
    }
}
</script>