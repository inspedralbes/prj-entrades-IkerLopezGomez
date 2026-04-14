<!-- Component per mostrar la llista de productes (catàleg). -->
<!-- Rep la llista com a propietat i la renderitza en una quadrícula. -->
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div v-for="element in llista" :key="element.id" 
         class="bg-white p-0 rounded-2xl border border-gray-200 shadow-sm hover:border-blue-300 transition-all hover:shadow-md overflow-hidden flex flex-col">
      
      <!-- Imatge amb fallback -->
      <div class="h-48 bg-gray-100 flex items-center justify-center relative overflow-hidden">
        <img v-if="element.imatge_url" 
             :src="getImageUrl(element.imatge_url)" 
             :alt="element.titol"
             class="w-full h-full object-cover">
        <div v-else class="text-gray-400 text-xs font-bold uppercase tracking-widest">
          Imatge no disponible
        </div>
        <!-- Badge de preu -->
        <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-black shadow-lg">
          {{ element.preu }}€
        </div>
      </div>

      <div class="p-5 flex-1 flex flex-col">
        <h3 class="font-black text-xl text-gray-900 leading-tight mb-2">{{ element.titol }}</h3>
        <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">{{ element.descripcio }}</p>
        
        <!-- Detalls de data i hora -->
        <div class="bg-gray-50 rounded-xl p-3 mb-4 flex gap-4 text-xs font-bold text-gray-600">
          <div class="flex items-center gap-1">
            <span class="text-blue-500">📅</span> {{ formatarData(element.data) }}
          </div>
          <div class="flex items-center gap-1">
            <span class="text-blue-500">🕒</span> {{ element.hora.substring(0, 5) }}
          </div>
        </div>

        <button @click="comprarEntrades(element)"
                class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-black hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-100 uppercase tracking-widest">
          Comprar Entrades
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
  },
  methods: {
    getImageUrl: function(url) {
      if (!url) return '';
      // Si la URL viene de la API con el nombre del contenedor, la convertimos en relativa
      if (url.startsWith('http://laravel-web') || url.startsWith('http://laravel')) {
        return url.replace(/^http:\/\/[^\/]+/, '');
      }
      if (url.startsWith('http://')) {
        return url;
      }
      return '/images/' + url;
    },
    formatarData: function(dataStr) {
      if (!dataStr) return '';
      // Retorna data en format DD/MM/YYYY
      var d = new Date(dataStr);
      return d.toLocaleDateString('ca-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    },
    comprarEntrades: function(element) {
      // Gestió de rutes segons el tipus d'esdeveniment
      if (element.artista) {
        this.$router.push('/concerts/' + element.id + '/seats');
      } else {
        this.$router.push('/movies/' + element.id + '/seats');
      }
    }
  }
}
</script>
