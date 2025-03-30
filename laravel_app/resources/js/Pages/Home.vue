<script setup>
import { ref, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import { router } from '@inertiajs/vue3'

const meses = ref([])
const diferenciaAcumulada = ref(0)
const mostrarHistorico = ref(false)  // Controla si se muestra el historial
const mostrarIngreso = ref(false)
const mostrarEgreso = ref(false)
const mostrarTarjeta = ref(false)
const mostrarCerrarMes = ref(false)
const tarjetas = ref([])
const historial = ref([])

const seleccion = ref(null)
const mensaje = ref('')
const error = ref('')

const nuevoIngreso = ref({
  monto: '',
  descripcion: '',
  fecha: new Date().toISOString().substr(0, 10)
})

const nuevoEgreso = ref({
  nombre: '',
  monto_cuota: '',
  cuota_actual: 1,
  cuota_final: 1,
  tipo: 'único',
  tarjeta_id: null,
  fecha: new Date().toISOString().substr(0, 10)
})

const nuevaTarjeta = ref({ nombre: '' })

const cargarDatos = async () => {
  const token = localStorage.getItem('token')
  if (!token) return router.visit('/login')

  const res = await axios.post('/api/home', {}, {
    headers: { Authorization: `Bearer ${token}` }
  })

  meses.value = res.data.meses
  diferenciaAcumulada.value = res.data.diferencia_acumulada

  const tarjetasRes = await axios.post('/api/tarjetas/listar', {}, {
    headers: { Authorization: `Bearer ${token}` }
  })

  tarjetas.value = tarjetasRes.data

  const historialRes = await axios.post('/api/cierres-mensuales/historial', {}, {
    headers: { Authorization: `Bearer ${token}` }
  })
  historial.value = historialRes.data

  // Preseleccionar primer mes
  if (meses.value.length) {
    seleccion.value = meses.value[0]
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
    mostrarCerrarMes.value = false
    await cargarDatos()
  } catch (e) {
    error.value = '❌ Error al cerrar el mes.'
    mensaje.value = ''
  }
}

const guardarIngreso = async () => {
  const token = localStorage.getItem('token')
  await axios.post('/api/ingresos', nuevoIngreso.value, {
    headers: { Authorization: `Bearer ${token}` }
  })
  mostrarIngreso.value = false
  await cargarDatos()
}

const guardarEgreso = async () => {
  const token = localStorage.getItem('token')
  await axios.post('/api/egresos', nuevoEgreso.value, {
    headers: { Authorization: `Bearer ${token}` }
  })
  mostrarEgreso.value = false
  await cargarDatos()
}

const guardarTarjeta = async () => {
  const token = localStorage.getItem('token')
  await axios.post('/api/tarjetas', nuevaTarjeta.value, {
    headers: { Authorization: `Bearer ${token}` }
  })
  mostrarTarjeta.value = false
  await cargarDatos()
}

onMounted(cargarDatos)
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 p-4 font-sans">
    <div class="max-w-6xl mx-auto">
      <!-- ENCABEZADO -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-0">📖 Libro Diario</h1>
        <div class="space-x-2">
          <button @click="mostrarIngreso = true" class="btn-green">+ Ingreso</button>
          <button @click="mostrarEgreso = true" class="btn-red">+ Egreso</button>
          <button @click="mostrarTarjeta = true" class="btn-indigo">+ Tarjeta</button>
          <button @click="mostrarCerrarMes = true" class="btn-blue">Cerrar Mes</button>
          <button @click="mostrarHistorico = !mostrarHistorico" class="btn-gray">
            {{ mostrarHistorico ? 'Ocultar' : 'Ver históricos' }}
          </button>
        </div>
      </div>

      <!-- DIFERENCIA ACUMULADA -->
      <div class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded shadow text-sm mb-4">
        <strong>Diferencia acumulada de cierres:</strong>
        <span class="text-lg font-bold ml-2">${{ Number(diferenciaAcumulada).toFixed(2) }}</span>
      </div>

      <!-- TABLAS POR MES -->
      <div v-if="!mostrarHistorico" v-for="m in meses" :key="`${m.mes}-${m.anio}`" class="mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">📆 {{ m.mes }}/{{ m.anio }}</h2>
        <div class="bg-white shadow rounded overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="text-left px-3 py-2">Fecha</th>
                <th class="text-left px-3 py-2">Detalle</th>
                <th class="text-right px-3 py-2">Ingreso</th>
                <th class="text-right px-3 py-2">Egreso</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="ing in m.ingresos" :key="'ingreso-' + ing.id">
                <tr class="border-t">
                  <td class="px-3 py-1.5">{{ ing.fecha }}</td>
                  <td class="px-3 py-1.5">{{ ing.descripcion || 'Ingreso' }}</td>
                  <td class="px-3 py-1.5 text-right text-green-600 font-medium">${{ ing.monto }}</td>
                  <td class="px-3 py-1.5 text-right">-</td>
                </tr>
              </template>
              <template v-for="eg in m.egresos" :key="'egreso-' + eg.id">
                <tr class="border-t">
                  <td class="px-3 py-1.5">{{ eg.fecha }}</td>
                  <td class="px-3 py-1.5">
                    {{ eg.nombre }}
                    <span v-if="eg.tipo === 'cuotas'">({{ eg.cuota_actual }}/{{ eg.cuota_final }})</span>
                  </td>
                  <td class="px-3 py-1.5 text-right">-</td>
                  <td class="px-3 py-1.5 text-right text-red-600 font-medium">${{ eg.monto_cuota }}</td>
                </tr>
              </template>
              <tr class="bg-gray-50 font-semibold border-t">
                <td colspan="2" class="px-3 py-2 text-right">Diferencia mensual</td>
                <td colspan="2" class="px-3 py-2 text-right text-blue-700">
                  {{
                    (
                      m.ingresos.reduce((s, i) => s + parseFloat(i.monto), 0) -
                      m.egresos.reduce((s, e) => s + parseFloat(e.monto_cuota), 0)
                    ).toFixed(2)
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- HISTÓRICO -->
      <div v-if="mostrarHistorico" class="bg-white rounded shadow p-4 mt-6">
        <h2 class="text-lg font-semibold mb-2 text-gray-800">📚 Historial de Cierres Mensuales</h2>
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-100">
              <th class="text-left px-3 py-2">Mes/Año</th>
              <th class="text-right px-3 py-2">Ingresos</th>
              <th class="text-right px-3 py-2">Egresos</th>
              <th class="text-right px-3 py-2">Diferencia</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="h in historial" :key="h.id" class="border-t">
              <td class="px-3 py-2">{{ h.mes }}/{{ h.anio }}</td>
              <td class="px-3 py-2 text-right">${{ Number(h.total_ingresos).toFixed(2) }}</td>
              <td class="px-3 py-2 text-right">${{ Number(h.total_egresos).toFixed(2) }}</td>
              <td class="px-3 py-2 text-right text-blue-700 font-medium">
                ${{ Number(h.diferencia).toFixed(2) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL: CERRAR MES -->
    <Modal v-if="mostrarCerrarMes" @close="mostrarCerrarMes = false" title="Cerrar mes">
      <div v-if="meses.length">
        <label class="text-sm text-gray-700 mb-1">Selecciona un mes:</label>
        <select v-model="seleccion" class="input mt-1">
          <option v-for="m in meses" :key="`${m.mes}-${m.anio}`" :value="m">{{ m.mes }}/{{ m.anio }}</option>
        </select>
        <button @click="cerrarMes" class="btn-blue w-full mt-3">Cerrar</button>
      </div>
      <p v-else class="text-gray-600">No hay meses disponibles para cerrar.</p>
      <p v-if="mensaje" class="text-green-600 mt-2">{{ mensaje }}</p>
      <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
    </Modal>

    <!-- MODALES: INGRESO / EGRESO / TARJETA -->
    <Modal v-if="mostrarIngreso" @close="mostrarIngreso = false" title="Nuevo Ingreso">
      <input v-model="nuevoIngreso.descripcion" placeholder="Descripción" class="input" />
      <input v-model="nuevoIngreso.monto" type="number" placeholder="Monto" class="input" />
      <input v-model="nuevoIngreso.fecha" type="date" class="input" />
      <div class="modal-footer">
        <button @click="mostrarIngreso = false" class="btn-gray">Cancelar</button>
        <button @click="guardarIngreso" class="btn-green">Guardar</button>
      </div>
    </Modal>

    <Modal v-if="mostrarEgreso" @close="mostrarEgreso = false" title="Nuevo Egreso">
      <input v-model="nuevoEgreso.nombre" placeholder="Nombre" class="input" />
      <input v-model="nuevoEgreso.monto_cuota" type="number" placeholder="Monto cuota" class="input" />
      <div class="flex gap-2 mb-2">
        <input v-model="nuevoEgreso.cuota_actual" type="number" class="input" placeholder="Cuota actual" />
        <input v-model="nuevoEgreso.cuota_final" type="number" class="input" placeholder="Cuota total" />
      </div>
      <select v-model="nuevoEgreso.tipo" class="input">
        <option value="único">Único</option>
        <option value="cuotas">Cuotas</option>
        <option value="mensual">Mensual</option>
      </select>
      <select v-model="nuevoEgreso.tarjeta_id" class="input">
        <option value="">-- Tarjeta (opcional) --</option>
        <option v-for="t in tarjetas" :key="t.id" :value="t.id">{{ t.nombre }}</option>
      </select>
      <input v-model="nuevoEgreso.fecha" type="date" class="input" />
      <div class="modal-footer">
        <button @click="mostrarEgreso = false" class="btn-gray">Cancelar</button>
        <button @click="guardarEgreso" class="btn-red">Guardar</button>
      </div>
    </Modal>

    <Modal v-if="mostrarTarjeta" @close="mostrarTarjeta = false" title="Nueva Tarjeta">
      <input v-model="nuevaTarjeta.nombre" placeholder="Nombre de la tarjeta" class="input" />
      <div class="modal-footer">
        <button @click="mostrarTarjeta = false" class="btn-gray">Cancelar</button>
        <button @click="guardarTarjeta" class="btn-indigo">Guardar</button>
      </div>
    </Modal>
  </div>
</template>

<style scoped>
.input {
  @apply w-full mb-2 p-2 border border-gray-300 rounded-md text-sm;
}
.modal-footer {
  @apply flex justify-end gap-2 mt-2;
}
.btn-gray {
  @apply px-3 py-1.5 bg-gray-300 hover:bg-gray-400 rounded text-sm;
}
.btn-green {
  @apply px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-sm;
}
.btn-red {
  @apply px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-sm;
}
.btn-indigo {
  @apply px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm;
}
.btn-blue {
  @apply px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm;
}
</style>
