<template>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" :data-bs-target="'#exampleModal'+id"><i class="fa-solid fa-image"></i></button>

    <!-- Modal -->
    <div class="modal fade" :id="'exampleModal'+id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Pembayaran</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <img :src="image" class="img-fluid" alt="">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
</template>
<script>
import axios from 'axios'
import { onMounted, ref } from 'vue'
export default{
    props: {
        image: String,
        path: String,
        id: Number
    },
    setup(props){
        const image = ref('')

        onMounted(() => {
            getImageBuktiPembayaran()
        })

        function showImage(){
            return '/storage/images/'+props.path+'/'+ props.image
        }

        function getImageBuktiPembayaran(){
            axios.get(`/getImageBuktiPembayaran/${props.path}/${props.image}`)
            .then((res) => {
                image.value = res.data.data
            })
            .catch((err) => {
                console.log(err)
            })
        }

        return {
            showImage,
            getImageBuktiPembayaran,
            image
        }
    }
}
</script>