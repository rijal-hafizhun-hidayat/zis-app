<template>
    <Head title="Donasi" />
    <NavBar />
    <Jumbotron />
    <Content />
    <Donasi />
    <div class="bg">
        <Footer />
    </div>
</template>
<script>
import NavBar from '../Home/Components/NavBar.vue';
import Footer from '../Home/Components/Footer.vue';
import Jumbotron from './Components/Jumbotron.vue';
import Content from './Components/Content.vue'
import Donasi from './Components/Donasi.vue'
import { Head } from '@inertiajs/vue3'
export default{
    components: { NavBar, Footer, Jumbotron, Content, Donasi, Head }
}
</script>
<style scoped>
    .bg{
		background-color: #e6e6e6;
		padding-top: 100px;
	}
</style>