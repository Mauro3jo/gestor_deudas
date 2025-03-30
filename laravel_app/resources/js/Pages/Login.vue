<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const email = ref('')
const password = ref('')
const error = ref(null)
const loading = ref(false)

const login = async () => {
    error.value = null
    loading.value = true

    try {
        const res = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        })

        localStorage.setItem('token', res.data.token)
     router.visit('/home')

    } catch (err) {
        error.value = err.response?.data?.error || 'Credenciales inválidas'
    } finally {
        loading.value = false
    }
}

const irARegistro = () => {
    router.visit('/registro')
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-700">Iniciar sesión</h1>

            <div class="space-y-4">
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
                @click="login"
                :disabled="loading"
                class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded transition duration-200"
            >
                {{ loading ? 'Cargando...' : 'Ingresar' }}
            </button>

            <p v-if="error" class="text-red-600 text-sm mt-3 text-center">{{ error }}</p>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">¿No tienes una cuenta?</p>
                <button
                    @click="irARegistro"
                    class="mt-2 text-blue-600 hover:underline text-sm font-medium"
                >
                    Registrarse
                </button>
            </div>
        </div>
    </div>
</template>
