<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const nombre = ref('')
const email = ref('')
const password = ref('')
const error = ref(null)
const loading = ref(false)

const registrar = async () => {
    error.value = null
    loading.value = true

    try {
        await axios.post('/api/registro', {
            nombre: nombre.value,
            email: email.value,
            password: password.value
        })

        router.visit('/login')
    } catch (err) {
        error.value = err.response?.data?.message || 'Error en el registro'
    } finally {
        loading.value = false
    }
}

const irALogin = () => {
    router.visit('/login')
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-700">Crear cuenta</h1>

            <div class="space-y-4">
                <input
                    v-model="nombre"
                    type="text"
                    placeholder="Nombre completo"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <input
                    v-model="email"
                    type="email"
                    placeholder="Correo electrónico"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
                <input
                    v-model="password"
                    type="password"
                    placeholder="Contraseña"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>

            <button
                @click="registrar"
                :disabled="loading"
                class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded transition duration-200"
            >
                {{ loading ? 'Registrando...' : 'Registrarse' }}
            </button>

            <p v-if="error" class="text-red-600 text-sm mt-3 text-center">{{ error }}</p>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">¿Ya tienes una cuenta?</p>
                <button
                    @click="irALogin"
                    class="mt-2 text-blue-600 hover:underline text-sm font-medium"
                >
                    Iniciar sesión
                </button>
            </div>
        </div>
    </div>
</template>
