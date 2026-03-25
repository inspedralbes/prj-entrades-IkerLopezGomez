<!-- Component contenidor que agrupa tota la selecció de pel·lícules i concerts amb Sidebar. -->
<template>
  <div class="flex flex-col md:flex-row min-h-[calc(100vh-65px)] bg-gray-50">
    
    <!-- SIDEBAR DE FILTRES -->
    <aside class="w-full md:w-80 bg-white border-r p-6 space-y-8 sticky top-[65px] h-fit md:h-[calc(100vh-65px)] overflow-y-auto z-10">
      
      <!-- NAVEGACIÓ CATEGORIES -->
      <div class="space-y-3">
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Estàs buscant...</p>
        <div class="flex flex-col gap-2">
          <button @click="canviarCategoria('pel·lícules')"
                  :class="['flex items-center gap-3 p-4 rounded-2xl text-sm font-black transition-all transform', 
                           categoriaActual === 'pel·lícules' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 scale-[1.02]' : 'bg-gray-50 text-gray-500 hover:bg-gray-100']">
            <span>🎬</span> Pel·lícules
          </button>
          <button @click="canviarCategoria('concerts')"
                  :class="['flex items-center gap-3 p-4 rounded-2xl text-sm font-black transition-all transform', 
                           categoriaActual === 'concerts' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 scale-[1.02]' : 'bg-gray-50 text-gray-500 hover:bg-gray-100']">
            <span>🎸</span> Concerts
          </button>
        </div>
      </div>

      <!-- FILTRES -->
      <div class="space-y-6 pt-6 border-t border-gray-100">
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Filtres de cerca</p>
        
        <!-- Cercador -->
        <div class="space-y-2">
          <label class="text-xs font-bold text-gray-600 ml-1">Cerca per nom</label>
          <input v-model="cerca" 
                 type="text" 
                 placeholder="Ex: Oppenheimer..." 
                 class="w-full p-4 rounded-xl border-0 bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-bold shadow-sm">
        </div>

        <!-- Filtre Mes (només Concerts) -->
        <div v-if="categoriaActual === 'concerts'" class="space-y-2">
          <label class="text-xs font-bold text-gray-600 ml-1">Mes del concert</label>
          <select v-model="filtreMes"
                  class="w-full p-4 rounded-xl border-0 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500 shadow-sm appearance-none">
            <option value="">Tots els mesos</option>
            <option v-for="(mes, i) in nomsMesos" :key="i" :value="i">{{ mes }}</option>
          </select>
        </div>

        <!-- Filtre Dia (només Pel·lícules) -->
        <div v-if="categoriaActual === 'pel·lícules'" class="space-y-2">
          <label class="text-xs font-bold text-gray-600 ml-1">Filtra per data</label>
          <input v-model="filtreDia" 
                 type="date" 
                 class="w-full p-4 rounded-xl border-0 bg-gray-50 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
        </div>

        <button v-if="cerca || filtreMes !== '' || filtreDia" 
                @click="resetFiltres"
                class="w-full py-2 text-xs font-bold text-blue-600 hover:underline">
          Netejar filtres
        </button>
      </div>
    </aside>

    <!-- CONTINGUT PRINCIPAL -->
    <main class="flex-1 p-6 md:p-10">
      <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Info ràpida -->
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-2xl font-black text-gray-900 capitalize tracking-tight flex items-center gap-3">
            {{ categoriaActual }}
            <span class="text-xs font-bold bg-gray-200 py-1 px-3 rounded-full text-gray-500">{{ llistaFiltrada.length }}</span>
          </h2>
        </div>

        <div v-if="carregant" class="flex flex-col items-center justify-center py-40 animate-pulse text-gray-300">
          <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-4"></div>
          <p class="text-xs font-black uppercase tracking-widest">Sincronitzant dades...</p>
        </div>

        <div v-else>
          <!-- Component de llista refactoritzat -->
          <LlistaCatalogo :llista="llistaFiltrada" />
          
          <div v-if="llistaFiltrada.length === 0" class="flex flex-col items-center justify-center py-32 text-center opacity-40">
            <span class="text-6xl mb-4">🔍</span>
            <p class="text-lg font-black text-gray-900">No hi ha resultats</p>
            <p class="text-sm font-bold text-gray-500">Prova d'ajustar els filtres de la barra lateral.</p>
          </div>
        </div>
      </div>
    </main>

  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useEntrades } from '../composables/useEntrades';

export default {
  setup: function() {
    var entrades = useEntrades();
    
    var cerca = ref('');
    var filtreMes = ref('');
    var filtreDia = ref('');

    var nomsMesos = ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];

    var resetFiltres = function() {
        cerca.value = '';
        filtreMes.value = '';
        filtreDia.value = '';
    };

    var llistaFiltrada = computed(function () {
        var base = entrades.categoriaActual.value === 'pel·lícules' 
            ? entrades.dadesPelicules.value 
            : entrades.dadesConcerts.value;

        return base.filter(function (item) {
            var matchNom = item.title.toLowerCase().includes(cerca.value.toLowerCase());
            if (!matchNom) return false;

            if (entrades.categoriaActual.value === 'concerts') {
                if (filtreMes.value !== '') {
                    var mesItem = new Date(item.date).getMonth();
                    if (mesItem != filtreMes.value) return false;
                }
            } else {
                if (filtreDia.value !== '') {
                    var dataItem = new Date(item.date).toISOString().split('T')[0];
                    if (dataItem !== filtreDia.value) return false;
                }
            }
            return true;
        });
    });

    return {
      categoriaActual: entrades.categoriaActual,
      carregant: entrades.carregant,
      llistaFiltrada: llistaFiltrada,
      canviarCategoria: entrades.canviarCategoria,
      cerca: cerca,
      filtreMes: filtreMes,
      filtreDia: filtreDia,
      nomsMesos: nomsMesos,
      resetFiltres: resetFiltres
    };
  }
}
</script>

<style scoped>
/* Estils específics per a la barra lateral */
aside {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 transparent;
}
aside::-webkit-scrollbar {
  width: 4px;
}
aside::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
</style>
