<!-- Component per mostrar la llista de productes (catàleg). -->
<!-- Rep la llista com a propietat i la renderitza en una quadrícula. -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div v-for="element in llista" :key="element.id" 
         class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:border-blue-300 transition-colors">
      <div class="h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center text-gray-400">
        Imatge no disponible
      </div>
      <h3 class="font-bold text-lg text-gray-900">{{ element.titol }}</h3>
      <p class="text-sm text-gray-500 mt-1">{{ element.descripcio }}</p>
      <div class="mt-4 flex justify-between items-center">
        <span class="text-blue-700 font-bold">{{ element.preu }}€</span>
        <button class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-blue-700 active:scale-95 transition">
          Reservar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    llista: {
      type: Array,
      required: true
    }
  }
}
</script>
