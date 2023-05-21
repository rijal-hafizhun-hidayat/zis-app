<template>
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 text-white">
                    <p class="fw-lighter fs-1">Zakat, Infaq dan Shadaqah</p>
                    <p class="lead">Mari berbagi kebahagian <br/> terhadap sesama dan raih jannah-Nya</p>
                    <button @click="donasi()">Donasi</button>
                </div>
                <div class="col-sm-7">
                    <img class="img-fluid" src="/storage/images/Home/—Gambarpng—zakat lets to people poor_130223.png" alt="">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { router, Link } from '@inertiajs/vue3'
export default{
    components: { Link },
    setup(){
        function donasi(){
            router.get('/donasi')
        }

        return {
            donasi
        }
    }
}
</script>
<style scoped>

    /* DESKTOP CSS */
    @media (min-width: 992px) {
        .col-sm-5{
            margin-top: 40px;
        }
        .row{
            margin-top: 100px;
        }
        .container{
            margin-top: 100px;
        }
        .jumbotron {
            padding: 5rem 1rem;
            margin-bottom: 2rem;
            background-image: linear-gradient(to right, rgb(255, 59, 0, 1) , rgb(255, 127, 0, 1));
        }

        img {
            display: block;
            margin-left: auto;
            margin-top: -100px;
            width: 450px;
        }

        .col-sm-5 button{
            margin-top: 50px;
            border: none;
            color: rgb(255, 59, 0, 1);
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            padding: 15px 50px;
            text-decoration: none;
            transition-duration: 0.4s;
            background-color: white;
        }

        .col-sm-5 button:hover{
            background-color: #ebe8e8;
        }
    }

    /* MOBILE CSS */
    @media (max-width: 992px){
        .col-sm-5{
            margin-top: 40px;
        }
        .container{
            margin-top: 100px;
        }
        .jumbotron {
            padding: 1rem 1rem;
            margin-bottom: 2rem;
            background-image: linear-gradient(to right, rgb(255, 59, 0, 1) , rgb(255, 127, 0, 1));
        }
        .col-sm-5 button{
            margin-top: 50px;
            border: none;
            color: rgb(255, 59, 0, 1);
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            padding: 15px 50px;
            text-decoration: none;
            transition-duration: 0.4s;
            background-color: white;
        }

        .col-sm-5 button:active{
            background-color: #ebe8e8;
        }
    }
</style>