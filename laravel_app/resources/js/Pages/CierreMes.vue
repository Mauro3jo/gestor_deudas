<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const opciones = ref([])
const seleccion = ref(null)
const mensaje = ref('')
const error = ref('')

const cargarOpciones = async () => {
  const token = localStorage.getItem('token')

  try {
    const res = await axios.post('/api/home', {}, {
      headers: { Authorization: `Bearer ${token}` }
    })

    opciones.value = res.data.meses.map(m => ({
      mes: m.mes,
      anio: m.anio,
      label: `${m.mes}/${m.anio}`
    }))

    if (opciones.value.length) {
      seleccion.value = opciones.value[0]
    }
  } catch (e) {
    error.value = 'Error al cargar meses disponibles'
  }
}

const cerrarMes = async () => {
  const token = localStorage.getItem('token')

  if (!seleccion.value) return

  try {
    await axios.post('/api/cierres-mensuales', {
      mes: seleccion.value.mes,
      anio: seleccion.value.anio
    }, {
      headers: { Authorization: `Bearer ${token}` }
    })

    mensaje.value = '✅ Mes cerrado correctamente.'
    error.value = ''
    setTimeout(() => router.visit('/home'), 1500)
  } catch (e) {
    error.value = '❌ Error al cerrar el mes.'
    mensaje.value = ''
  }
}

onMounted(cargarOpciones)
</script>

<template>
  <div class="min-h-screen bg-blue-50 p-6 font-sans">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
      <h1 class="text-xl font-bold mb-4 text-gray-800">📅 Cerrar Mes</h1>

      <div v-if="opciones.length">
        <label class="text-sm text-gray-700">Selecciona un mes:</label>
        <select v-model="seleccion" class="input mt-1">
          <option v-for="op in opciones" :key="`${op.mes}-${op.anio}`" :value="op">
            {{ op.label }}
          </option>
        </select>
        <button @click="cerrarMes" class="btn-blue mt-4 w-full">Cerrar</button>
      </div>
      <p v-else class="text-gray-600">No hay meses disponibles para cerrar.</p>

      <p v-if="mensaje" class="mt-4 text-green-600 font-semibold">{{ mensaje }}</p>
      <p v-if="error" class="mt-4 text-red-600 font-semibold">{{ error }}</p>
    </div>
  </div>
</template>

<style scoped>
.input {
  @apply w-full p-2 border border-gray-300 rounded text-sm;
}
.btn-blue {
  @apply px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm;
}
</style>
