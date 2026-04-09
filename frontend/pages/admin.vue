<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Pàgina d'Admin</h1>
      <NuxtLink to="/public-catalog" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Veure catàleg
      </NuxtLink>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-600 mb-2">Usuaris connectats</h3>
        <p class="text-4xl font-bold text-blue-600">{{ usuarisConnectats }}</p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-600 mb-2">Total de comptes creats</h3>
        <p class="text-4xl font-bold text-green-600">{{ totalUsuaris }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '~/store/auth'

const authStore = useAuthStore()
const usuarisConnectats = ref(0)
const totalUsuaris = ref(0)
let socket = null

const carregarDades = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/admin/total-usuaris', {
      headers: {
        'Authorization': `Bearer ${authStore.token}`
      }
    })
    if (response.ok) {
      const data = await response.json()
      totalUsuaris.value = data.total
    }
  } catch (e) {
    console.error('Error carregant total usuaris:', e)
  }
}

const obtenirUsuarisConnectats = async () => {
  try {
    const response = await fetch('http://localhost:3000/api/usuaris-connectats')
    if (response.ok) {
      const data = await response.json()
      usuarisConnectats.value = data.total
    }
  } catch (e) {
    console.error('Error usuaris connectats:', e)
  }
}

onMounted(async () => {
  let user = authStore.usuari
  
  if (!user && process.client) {
    const storedUser = sessionStorage.getItem('user')
    if (storedUser) {
      try {
        user = JSON.parse(storedUser)
        authStore.usuari = user
        authStore.token = sessionStorage.getItem('token')
        authStore.estaLoguejat = true
      } catch (e) {}
    }
  }
  
  if (!authStore.token) {
    navigateTo('/login')
    return
  }
  
  if (!user || user.role !== 'admin') {
    navigateTo('/')
    return
  }
  
  await carregarDades()
  await obtenirUsuarisConnectats()
  
  if (process.client) {
    const io = await import('socket.io-client')
    socket = io.default('http://localhost:3000')
    socket.on('usuaris_connectats', (total) => {
      usuarisConnectats.value = total
    })
  }
})

onUnmounted(() => {
  if (socket) {
    socket.disconnect()
  }
})
</script>