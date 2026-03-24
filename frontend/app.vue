<!-- Punt d'entrada principal de l'aplicació Nuxt 3. -->
<!-- Proporciona una estructura bàsica i navegació inicial per al sistema de reserves. -->
<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-4 text-center">Sistema de Venda d'Entrades</h1>
      <p class="text-gray-600 text-center mb-6">Benvingut a la plataforma de vendes d'alta concurrència.</p>
      <div class="space-y-4">
        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
          Iniciar Sessió
        </button>
        <button class="w-full border border-blue-600 text-blue-600 py-2 rounded hover:bg-blue-50 transition">
          Registrar-se
        </button>
      </div>
    </div>
  </div>
</template>

<script>
// Ús de l'estat global amb Pinia
import { useAuthStore } from './store/auth';

export default {
  setup: function() {
    var magatzemAutenticacio = useAuthStore();
    
    // Aquí es podrien definir les funcions de la pàgina seguint l'estil ES5.
    
    return {
      magatzem: magatzemAutenticacio
    };
  }
}
</script>

<style>
/* Estils bàsics per a l'aplicació */
</style>
