<script setup>
import { onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'

const usuario = ref(null)
const error = ref(null)

onMounted(() => {
    const token = localStorage.getItem('token')

    if (!token) {
        router.visit('/login')
        return
    }

    axios.get('/api/perfil', {
        headers: {
            Authorization: token
        }
    })
    .then(res => {
        usuario.value = res.data
    })
    .catch(() => {
        error.value = 'No autorizado'
        router.visit('/login')
    })
})
</script>
