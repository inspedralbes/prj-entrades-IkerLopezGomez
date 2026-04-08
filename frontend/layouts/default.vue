<!-- Layout per defecte amb el header de Totosala. -->
<template>
  <div>
    <header class="bg-white border-b p-4 shadow-sm sticky top-0 z-20 flex justify-between items-center">
      <NuxtLink to="/" class="text-2xl font-black text-blue-900 tracking-tight">
        TOTOSALA
      </NuxtLink>
      <nav class="flex gap-4">
        <NuxtLink v-if="!authStore.estaLoguejat" to="/login" class="text-blue-600 hover:underline">
          Login
        </NuxtLink>
        <NuxtLink v-if="!authStore.estaLoguejat" to="/register" class="text-blue-600 hover:underline">
          Registrar
        </NuxtLink>
        <NuxtLink v-if="authStore.estaLoguejat" to="/my-tickets" class="text-blue-600 hover:underline">
          Les meves entrades
        </NuxtLink>
        <button v-if="authStore.estaLoguejat" @click="tancarSessio" class="text-gray-600 hover:underline">
          Tancar sessió
        </button>
      </nav>
    </header>
    
    <!-- Contingut de la pàgina -->
    <slot />
  </div>
</template>

<script>
import { useAuthStore } from '~/store/auth'
import { onMounted } from 'vue'

export default {
  setup() {
    const authStore = useAuthStore()
    
    onMounted(() => {
      authStore.carregarToken()
    })

    const tancarSessio = async () => {
      try {
        await fetch('http://localhost:8000/api/auth/logout', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${authStore.token}`
          }
        })
      } catch (e) {
        console.error('Error al tancar sessió:', e)
      }
      authStore.tancarSessio()
      navigateTo('/login')
    }

    return { authStore, tancarSessio }
  }
}
</script>
