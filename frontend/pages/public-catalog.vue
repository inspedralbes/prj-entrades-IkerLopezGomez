<template>
  <div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Catàleg Púlic</h1>
        <NuxtLink to="/admin" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
          Tornar a Admin
        </NuxtLink>
      </div>

      <div class="mb-6 flex gap-4">
        <button 
          @click="categoriaActual = 'pel·lícules'"
          :class="['px-4 py-2 rounded font-bold', categoriaActual === 'pel·lícules' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700']"
        >
          Pel·lícules
        </button>
        <button 
          @click="categoriaActual = 'concerts'"
          :class="['px-4 py-2 rounded font-bold', categoriaActual === 'concerts' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700']"
        >
          Concerts
        </button>
      </div>

      <div v-if="carregant" class="text-center py-8">Carregant...</div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="item in llistaActual" :key="item.id" class="bg-white rounded-lg shadow p-6">
          <h3 class="text-xl font-bold mb-2">{{ item.titol }}</h3>
          <p v-if="item.artista" class="text-blue-600 font-semibold mb-1">{{ item.artista }}</p>
          <p class="text-gray-600 mb-2">{{ item.descripcio }}</p>
          <p class="text-gray-500 mb-1">📅 {{ new Date(item.data).toLocaleDateString('ca-ES') }}</p>
          <p class="text-gray-500 mb-1">🕒 {{ item.hora ? item.hora.substring(0, 5) : '' }}</p>
          <p class="text-blue-600 font-bold text-lg mt-2">{{ item.preu }}€</p>
        </div>
      </div>

      <div v-if="!carregant && llistaActual.length === 0" class="text-center py-8 text-gray-500">
        No hi ha {{ categoriaActual }} disponibles
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const categoriaActual = ref('pel·lícules')
const carregant = ref(true)
const movies = ref([])
const concerts = ref([])

const llistaActual = computed(() => {
  return categoriaActual.value === 'pel·lícules' ? movies.value : concerts.value
})

onMounted(() => {
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
})
</script>