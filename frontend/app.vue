<!-- Punt d'entrada refactoritzat per utilitzar components i composables. -->
<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    
    <!-- HEADER: Totosala -->
    <header class="bg-white border-b p-4 shadow-sm sticky top-0 z-10">
      <h1 class="text-2xl font-black text-blue-900 text-center tracking-tight">
        TOTOSALA
      </h1>
    </header>

    <!-- CONTINGUT PRINCIPAL -->
    <main class="flex-1 overflow-y-auto p-4 pb-24">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-6 text-gray-800 border-l-4 border-blue-600 pl-3 capitalize">
          {{ categoriaActual }}
        </h2>

        <!-- Component de llista refactoritzat -->
        <LlistaCatalogo :llista="llistaActual" />
      </div>
    </main>

    <!-- FOOTER: Navegació -->
    <footer class="bg-white border-t p-3 fixed bottom-0 w-full flex justify-around items-center shadow-lg">
      <button @click="canviarCategoria('pel·lícules')"
              :class="['flex-1 py-3 px-2 rounded-xl text-sm font-bold transition-all', 
                       categoriaActual === 'pel·lícules' ? 'bg-blue-100 text-blue-800 scale-105' : 'text-gray-500']">
        Pel·lícules
      </button>
      <div class="w-px h-6 bg-gray-200 mx-2"></div>
      <button @click="canviarCategoria('concerts')"
              :class="['flex-1 py-3 px-2 rounded-xl text-sm font-bold transition-all', 
                       categoriaActual === 'concerts' ? 'bg-blue-100 text-blue-800 scale-105' : 'text-gray-500']">
        Concerts
      </button>
    </footer>

  </div>
</template>

<script>
import { useEntrades } from './composables/useEntrades';

export default {
  setup: function() {
    // A. Ús del composable modular
    var entrades = useEntrades();
    
    // B. Retorn de variables i funcions a la plantilla
    return {
      categoriaActual: entrades.categoriaActual,
      llistaActual: entrades.llistaActual,
      canviarCategoria: entrades.canviarCategoria
    };
  }
}
</script>

<style>
body {
  margin: 0;
  -webkit-tap-highlight-color: transparent;
}
</style>
