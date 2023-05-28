<template>
    <nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <img class="img-fluid" :src="'/storage/images/Home/logo.jpg'" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto center">
                    <li class="nav-item divided-home">
                        <a class="nav-link" @click="home()">Home</a>
                    </li>
                    <!-- <li class="nav-item divided-profile">
                        <a class="nav-link" href="#">Profile</a>
                    </li> -->
                    <li class="nav-item divided-donasi">
                        <a class="nav-link" @click="donasi()" href="#">Donasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import { router } from '@inertiajs/vue3'
export default{
    setup(){
        function donasi(){
            router.get('/donasi')
        }

        function home(){
            router.get('/')
        }

        return {
            donasi,
            home
        }
    }
}
</script>
<style scoped>

    /* DEKSTOP CSS */
    @media (min-width: 992px) {
        li .nav-link{
            cursor: pointer;
        }
        
        .img-fluid{
            width: 80px;
        }

        nav{
            background-color: white;
            padding: 30px 0px;
        }

        .divided-home{
            margin: 0px 40px;
        }

        .divided-donasi{
            margin-right: 40px;
        }

        .navbar-nav .nav-item a{
            font-size: 15px;
            font-weight: bold;
            transition: 0.5 ease;
            color: black;
        }

        .navbar-nav .nav-item a:hover{
            color: rgb(255, 59, 0, 1);
        }
    }

    /* MOBILE CSS */
    @media (max-width: 992px) {
        nav{
            background-color: white !important;
        }

        li .nav-link{
            cursor: pointer;
        }

        .img-fluid{
            width: 80px;
        }

        .center{
            text-align: center;
            margin-top: 10px;
        }

        .navbar-nav .nav-item a{
            margin-bottom: 10px;
            font-weight: bold;
            color: black;
            transition: 0.5 ease;
            border-radius: 10px;
        }

        .navbar-nav .nav-item a:active{
            color: white;
            background-color: #ff6600;
            border-radius: 10px;
        }
    }
</style>