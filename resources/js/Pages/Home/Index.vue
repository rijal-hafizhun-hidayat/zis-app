<template>
	<Head title="Selamat Datang"/>
	<NavBar />
	<Jumbotron />
	<Content />
	<div class="bg">
		<Footer />
	</div>
</template>
<script>
import Footer from './Components/Footer.vue';
import Jumbotron from './Components/Jumbotron.vue'
import NavBar from './Components/NavBar.vue';
import Content from './Components/Content.vue'
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
export default {
	components: { Link, Head, NavBar, Jumbotron, Content, Footer }
}
</script>
<style scoped>
	.bg{
		background-color: #e6e6e6;
		padding-top: 100px;
	}
</style>