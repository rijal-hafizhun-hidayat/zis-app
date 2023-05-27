<template>
    <nav v-if="links.length > 3" aria-label="Page navigation example">
        <ul  class="pagination justify-content-center">
            <li v-for="(link, key) in links" class="page-item">
                <Link v-if="link.url === null" :key="key" class="page-link" v-html="link.label" />
                <Link v-else class="page-link" :key="`link-${key}`" :href="link.url" v-html="link.label" :class="{ 'active':link.active }"/>
            </li>
        </ul>
    </nav>
</template>
<script>
    import { Link } from '@inertiajs/vue3'
    export default {
        components: { Link },
        props: {
            links: Array
        },
    }
</script>