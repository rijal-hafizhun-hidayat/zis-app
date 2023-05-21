<template>
    <Head title="Profile" />
    <NavBar />
    <ChangeUsername />
    <ChangePassword />
    <Footer />
    
</template>

<script>
import { Head } from '@inertiajs/vue3'
import ChangeUsername from './Components/ChangeUsername.vue'
import ChangePassword from './Components/ChangePassword.vue'
import NavBar from '../Components/Navbar.vue'
import Footer from '../Components/Footer.vue'

export default {
    components: { NavBar, Footer, ChangeUsername, Head, ChangePassword },
}
</script>
