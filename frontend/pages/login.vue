<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Iniciar Sessió</h2>
            
            <form @submit.prevent="iniciarSessio">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correu electrònic
                    </label>
                    <input 
                        v-model="form.email"
                        type="email" 
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="correu@exemple.com"
                        required
                    />
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Contrasenya
                    </label>
                    <input 
                        v-model="form.password"
                        type="password" 
                        id="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <div v-if="error" class="mb-4 text-red-500 text-sm text-center">
                    {{ error }}
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50"
                    :disabled="carregant"
                >
                    {{ carregant ? 'Iniciant sessió...' : 'Iniciar Sessió' }}
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                No tens compte? 
                <NuxtLink to="/register" class="text-blue-600 hover:underline">Registra't</NuxtLink>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '~/store/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
    email: '',
    password: ''
})

const carregant = ref(false)
const error = ref('')

const iniciarSessio = async () => {
    carregant.value = true
    error.value = ''

    try {
        const response = await fetch('http://localhost:8000/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(form.value)
        })

        const data = await response.json()

        if (!response.ok) {
            throw new Error(data.message || data.email?.[0] || 'Error al iniciar sessió')
        }

        authStore.iniciarSessio(data)
        sessionStorage.setItem('token', data.token)
        return navigateTo('/')
    } catch (err) {
        error.value = err.message
    } finally {
        carregant.value = false
    }
}
</script>
