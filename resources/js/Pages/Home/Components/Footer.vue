<template>
    <div class="container" id="kontak">
        <div class="row">
            <div class="col-sm-4">
                <img class="img-fluid" :src="'/storage/images/Home/logo.jpg'" alt="">
                <p>Dewan Kemakmuran Masjid Keramat Megu</p>
            </div>
            <div class="col-sm-3">
                <h1>Kontak</h1>
                <div class="group-p">
                    <p>+6281393784144</p>
                    <p>rijal.1344@gmail.com</p>
                    <p>Masjid Kramat Megu, Megu Gede, Kec. Weru, Kabupaten Cirebon, Jawa Barat 45154</p>
                </div>
            </div>
            <div class="col-sm-5">
                <h1>Map</h1>
                <div style="overflow:hidden;resize:none;max-width:100%;width:400px;height:300px;"><div id="embed-ded-map-canvas" style="height:100%; width:100%;max-width:100%;"><iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=MASJID+KRAMAT+MEGU,+Megu+Gede,+Cirebon,+West+Java,+Indonesia&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe></div><a class="the-googlemap-enabler" rel="nofollow" href="https://www.bootstrapskins.com/themes" id="get-map-data">premium bootstrap themes</a></div>
            </div>
        </div>
    </div>
</template>

<style scoped>

/* DEKSTOP CSS */
@media (min-width: 992px){
    .row{
        padding-bottom: 30px;
    }

    #embed-ded-map-canvas img.text-marker{
        max-width:none!important;
        background:none!important;
    }
    
    img{
        max-width:none
    }

    .col-sm-3 .group-p{
        margin-top: 50px;
        color: #737373;
    }
    .col-sm-4 .img-fluid{
        width: 200px;
    }

    .col-sm-4 p{
        margin-top: 50px;
        color: #737373;
    }
}

/* MOBILE CSS */
@media (max-width: 992px){
    img{
        width: 200px;
    }
    .col-sm-3 h1{
        margin-top: 30px;
    }
    .col-sm-3 .group-p{
        margin-top: 30px;
        color: #737373;
    }

    .col-sm-4 p{
        margin-top: 20px;
        color: #737373;
    }
    .col-sm-5{
        padding-bottom: 20px;
    }
    .col-sm-5 h1{
        margin-top: 20px;
    }
}

</style>