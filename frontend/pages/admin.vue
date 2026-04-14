<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
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

      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Gestió d'Esdeveniments</h2>
          <button @click="mostrarFormulari = true" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Nou Esdeveniment
          </button>
        </div>

        <div class="mb-6 flex gap-4">
          <button 
            @click="categoriaActual = 'pel·lícules'"
            :class="['px-4 py-2 rounded font-bold', categoriaActual === 'pel·lícules' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700']"
          >
            Pel·lícules
          </button>
          <button 
            @click="categoriaActual = 'concerts'"
            :class="['px-4 py-2 rounded font-bold', categoriaActual === 'concerts' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700']"
          >
            Concerts
          </button>
        </div>

        <div v-if="carregant" class="text-center py-8">Carregant...</div>
        
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="item in llistaActual" :key="item.id" class="bg-gray-50 rounded-lg p-4 border">
            <h3 class="text-lg font-bold mb-1">{{ item.titol }}</h3>
            <p v-if="item.artista" class="text-blue-600 font-semibold mb-1">{{ item.artista }}</p>
            <p class="text-gray-600 text-sm mb-2">{{ item.descripcio }}</p>
            <p class="text-gray-500 text-sm mb-1">📅 {{ new Date(item.data).toLocaleDateString('ca-ES') }}</p>
            <p class="text-gray-500 text-sm mb-2">🕒 {{ item.hora ? item.hora.substring(0, 5) : '' }} | {{ item.preu }}€</p>
            <div class="flex gap-2 mt-2">
              <button @click="editarEvent(item)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                Editar
              </button>
              <button @click="eliminarEvent(item)" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="mostrarFormulari" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">{{ eventEditant ? 'Editar' : 'Nou' }} Esdeveniment</h2>
        
        <form @submit.prevent="guardarEvent">
          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Tipus</label>
            <select v-model="form.tipus" class="w-full p-2 border rounded" required>
              <option value="movie">Pel·lícula</option>
              <option value="concert">Concert</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Títol</label>
            <input v-model="form.titol" type="text" class="w-full p-2 border rounded" required>
          </div>

          <div v-if="form.tipus === 'concert'" class="mb-4">
            <label class="block text-sm font-bold mb-2">Artista</label>
            <input v-model="form.artista" type="text" class="w-full p-2 border rounded" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Descripció</label>
            <textarea v-model="form.descripcio" class="w-full p-2 border rounded" rows="3"></textarea>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Data</label>
            <input v-model="form.data" type="date" class="w-full p-2 border rounded" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Hora</label>
            <input v-model="form.hora" type="time" class="w-full p-2 border rounded" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Preu (€)</label>
            <input v-model="form.preu" type="number" step="0.01" min="0" class="w-full p-2 border rounded" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Imatge (nom del fitxer)</label>
            <input v-model="form.imatge_url" type="text" class="w-full p-2 border rounded" placeholder="ex: dune.jpeg">
          </div>

          <div class="flex gap-2 justify-end">
            <button type="button" @click="tancarFormulari" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
              Cancel·lar
            </button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '~/store/auth'

const authStore = useAuthStore()
const usuarisConnectats = ref(0)
const totalUsuaris = ref(0)
let socket = null

const categoriaActual = ref('pel·lícules')
const carregant = ref(true)
const movies = ref([])
const concerts = ref([])

const mostrarFormulari = ref(false)
const eventEditant = ref(null)

const form = ref({
  tipus: 'movie',
  titol: '',
  artista: '',
  descripcio: '',
  data: '',
  hora: '',
  preu: '',
  imatge_url: ''
})

const llistaActual = computed(() => {
  return categoriaActual.value === 'pel·lícules' ? movies.value : concerts.value
})

const carregarDades = async () => {
  try {
    const response = await fetch('/api/admin/total-usuaris', {
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

const carregarEvents = () => {
  carregant.value = true
  Promise.all([
    fetch('/api/movies').then(res => res.json()),
    fetch('/api/concerts').then(res => res.json())
  ])
    .then(([m, c]) => {
      movies.value = m
      concerts.value = c
    })
    .finally(() => {
      carregant.value = false
    })
}

const editarEvent = (item) => {
  eventEditant.value = item
  form.value = {
    tipus: item.artista ? 'concert' : 'movie',
    titol: item.titol,
    artista: item.artista || '',
    descripcio: item.descripcio || '',
    data: item.data,
    hora: item.hora ? item.hora.substring(0, 5) : '',
    preu: item.preu,
    imatge_url: item.imatge_url || ''
  }
  mostrarFormulari.value = true
}

const eliminarEvent = async (item) => {
  if (!confirm('Segur que vols eliminar aquest esdeveniment?')) return
  
  const endpoint = item.artista 
    ? `/api/admin/concerts/${item.id}` 
    : `/api/admin/movies/${item.id}`
  
  try {
    const response = await fetch(endpoint, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${authStore.token}`
      }
    })
    if (response.ok) {
      carregarEvents()
    }
  } catch (e) {
    console.error('Error eliminant event:', e)
  }
}

const guardarEvent = async () => {
  const endpoint = form.value.tipus === 'movie' 
    ? (eventEditant.value ? `/api/admin/movies/${eventEditant.value.id}` : '/api/admin/movies')
    : (eventEditant.value ? `/api/admin/concerts/${eventEditant.value.id}` : '/api/admin/concerts')
  
  const method = eventEditant.value ? 'PUT' : 'POST'
  
  try {
    const response = await fetch(endpoint, {
      method: method,
      headers: {
        'Authorization': `Bearer ${authStore.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form.value)
    })
    
    if (response.ok) {
      tancarFormulari()
      carregarEvents()
    }
  } catch (e) {
    console.error('Error guardant event:', e)
  }
}

const tancarFormulari = () => {
  mostrarFormulari.value = false
  eventEditant.value = null
  form.value = {
    tipus: 'movie',
    titol: '',
    artista: '',
    descripcio: '',
    data: '',
    hora: '',
    preu: '',
    imatge_url: ''
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
  carregarEvents()
  
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