<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <img class="img-fluid" id="zakat" :src="image.zakat" alt="">
            </div>
            <div class="col-sm-6">
                <h1 class="mb-5">APA ITU <span class="font-weight">ZAKAT?</span></h1>
                <p>Zakat merupakan pembersih diri dan harta dari kemungkinan diperoleh dengan jalan tidak halal. Membayar zakat juga akan membuat harta semakin tumbuh dan berkembang.</p>
                <p class="fs-3">خُذْ مِنْ اَمْوَالِهِمْ صَدَقَةً تُطَهِّرُهُمْ وَتُزَكِّيْهِمْ بِهَا وَصَلِّ عَلَيْهِمْۗ اِنَّ صَلٰوتَكَ سَكَنٌ لَّهُمْۗ وَاللّٰهُ سَمِيْعٌ عَلِيْمٌ </p>
                <p>“ Ambillah *zakat dari harta mereka (guna) menyucikan dan membersihkan mereka, dan doakanlah mereka karena sesungguhnya doamu adalah ketenteraman bagi mereka. Allah Maha Mendengar lagi Maha Mengetahui.” (Terjemah Kemenag 2019, QS. 9:103). </p>
                <button @click="donasi()">Donasi Sekarang</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <h1 class="mb-5">APA ITU <span class="font-weight">INFAQ?</span></h1>
                <p>Infaq adalah mengeluarkan sebagian harta benda yang dimiliki untuk kepentingan yang mengandung kemaslahatan. Dalam infaq tidak ada nishab. Karna itu infak boleh dikeluarkan oleh orang yang berpenghasilan tinggi atau rendah, disaat lapang atau sempit (QS Ali Imran [3] :134).</p>
                <p class="fs-3">يٰٓاَيُّهَا الَّذِيْنَ اٰمَنُوْٓا اَنْفِقُوْا مِنْ طَيِّبٰتِ مَا كَسَبْتُمْ وَمِمَّآ اَخْرَجْنَا لَكُمْ مِّنَ الْاَرْضِ ۗ وَلَا تَيَمَّمُوا الْخَبِيْثَ مِنْهُ تُنْفِقُوْنَ وَلَسْتُمْ بِاٰخِذِيْهِ اِلَّآ اَنْ تُغْمِضُوْا فِيْهِ ۗ وَاعْلَمُوْٓا اَنَّ اللّٰهَ غَنِيٌّ حَمِيْدٌ</p>
                <p>“ Wahai orang-orang yang beriman, infakkanlah sebagian dari hasil usahamu yang baik-baik dan sebagian dari apa yang Kami keluarkan dari bumi untukmu. Janganlah kamu memilih yang buruk untuk kamu infakkan, padahal kamu tidak mau mengambilnya, kecuali dengan memicingkan mata (enggan) terhadapnya. Ketahuilah bahwa Allah Mahakaya lagi Maha Terpuji.” (Terjemah Kemenag 2019, QS. 2:267).</p>
                <button @click="donasi()">Donasi Sekarang</button>
            </div>
            <div class="col-sm-6 order-first order-sm-last">
                <img class="img-fluid float-end" id="infaq" :src="image.infaq" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <img class="img-fluid" id="sedekah" :src="image.shadaqah" alt="">
            </div>
            <div class="col-sm-6">
                <h1 class="mb-5">APA ITU <span class="font-weight">SEDEKAH?</span></h1>
                <p>Sedekah secara bahasa, berasal dari kata “shidqoh” (bahasa Arab) yang artinya “benar”. Menurut tafsiran para ulama, orang yang suka bersedekah adalah orang yang benar pengakuan imannya.  Jadi, sedekah adalah perwujudan sekaligus cermin keimanan. Pengertian dari sisi terminologi, sedekah berarti pemberian sukarela kepada orang lain (terutama kepada orang-orang miskin) yang tidak ditentukan jenis, jumlah maupun waktunya.</p>
                <p class="fs-3">اِنْ تُبْدُوا الصَّدَقٰتِ فَنِعِمَّا هِيَۚ وَاِنْ تُخْفُوْهَا وَتُؤْتُوْهَا الْفُقَرَاۤءَ فَهُوَ خَيْرٌ لَّكُمْ ۗ وَيُكَفِّرُ عَنْكُمْ مِّنْ سَيِّاٰتِكُمْ ۗ وَاللّٰهُ بِمَا تَعْمَلُوْنَ خَبِيْرٌ</p>
                <p>“Jika kamu menampakkan sedekahmu, itu baik. (akan tetapi,) jika kamu menyembunyikannya dan memberikannya kepada orang-orang fakir, itu lebih baik bagimu. Allah akan menghapus sebagian kesalahanmu. Allah Maha teliti terhadap apa yang kamu kerjakan” (Terjemah Kemenag 2019, QS. 2:271).</p>
                <button @click="donasi()">Donasi Sekarang</button>
            </div>
        </div>
    </div>
</template>
<script>
// import zakatImg from '/storage/images/Home/zakat.png'
// import infaqImg from '/storage/images/Home/infaq.png'
// import shadaqahImg from '/storage/images/Home/shadaqah.png'
import NProgress from 'nprogress';
import { router } from '@inertiajs/vue3'
import { reactive, onMounted } from 'vue'
import axios from 'axios'
export default{
    setup(){
        const image = reactive({
            zakat: '',
            infaq: '',
            shadaqah: ''
        })

        function donasi(){
            router.get('/donasi')
        }

        onMounted(() => {
            getImageContent()
        })

        function getImageContent(){
            NProgress.start()
            axios.get('/getImageHome')
            .then((res) => {
                image.zakat = res.data.data.zakat,
                image.infaq = res.data.data.infaq,
                image.shadaqah = res.data.data.shadaqah
                NProgress.done()
            })
            .catch((err) => {
                console.log(err)
            })
            .finally(() => {
                NProgress.done()
            })
        }

        return {
            donasi,
            getImageContent,
            image
        }
    }
}
</script>
<style scoped>

    /* DEKSTOP CSS */
    @media (min-width: 992px) {
        .row{
            margin-top: 70px;
            margin-bottom: 70px;
        }

        .col-sm-6 .font-weight{
            font-weight: 900;
        }

        /* img#infaq{
            margin-left: 200px;
        } */

        .col-sm-6 p{
            margin-bottom: 50px;
        }

        .col-sm-6 button{
            margin-top: 50px;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            padding: 15px 50px;
            text-decoration: none;
            transition-duration: 0.4s;
            background-color: rgb(255, 59, 0, 1);
        }

        .col-sm-6 button:hover{
            background-color: #cc3300;
        }   
    }
    /* MOBILE CSS */
    @media (max-width: 992px) {
        img{
            width: 350px;
            display:block;
            margin:0 auto;
            margin-bottom: 50px;
        }

        .col-sm-6{
            margin-bottom: 50px;
        }

        .col-sm-6 button{
            margin-top: 50px;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            padding: 15px 50px;
            text-decoration: none;
            transition-duration: 0.4s;
            background-color: rgb(255, 59, 0, 1);
        }

        .col-sm-6 button:active{
            background-color: #cc3300;
        }   
    }
    
</style>