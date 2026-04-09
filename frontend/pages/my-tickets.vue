<template>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Les meves entrades</h2>
                <NuxtLink to="/" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tornar al catàleg
                </NuxtLink>
            </div>

            <div v-if="carregant" class="text-center py-8">
                <p class="text-gray-500">Carregant...</p>
            </div>

            <div v-else-if="tickets.length === 0" class="text-center py-8 bg-white rounded-lg shadow">
                <p class="text-gray-500 mb-4">No tens cap entrada comprada</p>
                <NuxtLink to="/" class="text-blue-600 hover:underline">Veure esdeveniments</NuxtLink>
            </div>

            <div v-else class="space-y-4">
                <div v-for="ticket in tickets" :key="ticket.id" class="bg-white rounded-lg shadow-md p-4 flex justify-between items-center">
                    <div>
                        <span :class="ticket.type === 'movie' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'" class="px-2 py-1 rounded text-xs font-medium">
                            {{ ticket.type === 'movie' ? 'Pel·lícula' : 'Concert' }}
                        </span>
                        <h3 class="text-lg font-semibold mt-2">{{ ticket.event }}</h3>
                        <p class="text-gray-600">Data: {{ new Date(ticket.date).toLocaleDateString('ca-ES') }}</p>
                        <p class="text-gray-600">Seient: {{ ticket.row }}{{ ticket.number }}</p>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <p>Comprat: {{ new Date(ticket.purchased_at).toLocaleDateString('ca-ES') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/store/auth'

const authStore = useAuthStore()
const tickets = ref([])
const carregant = ref(true)

onMounted(async () => {
    if (!authStore.estaLoguejat || !authStore.token) {
        navigateTo('/login')
        return
    }

    try {
        const response = await fetch('http://localhost:8000/api/my-tickets', {
            headers: {
                'Authorization': `Bearer ${authStore.token}`
            }
        })

        if (response.ok) {
            const data = await response.json()
            tickets.value = data.tickets
        }
    } catch (e) {
        console.error('Error carregant entrades:', e)
    } finally {
        carregant.value = false
    }
})
</script>
