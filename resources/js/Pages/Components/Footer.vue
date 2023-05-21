<template>
  <div class="">
    <div class="container">
      <footer class="py-3">
        <p class="text-center text-muted">© {{ year }} DKM Masjid Keramat Megu Cirebon</p>
      </footer>
    </div>
  </div>
</template>
<script>
import { ref } from 'vue'
export default {
  setup() {
    const year = ref(new Date().getFullYear())
  
    return {
      year
    }
  },
}
</script>