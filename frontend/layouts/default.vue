<!-- Layout per defecte amb el header de Totosala. -->
<template>
  <div>
    <header class="bg-white border-b p-4 shadow-sm sticky top-0 z-20">
      <h1 class="text-2xl font-black text-blue-900 text-center tracking-tight">
        TOTOSALA
      </h1>
    </header>
    
    <!-- Contingut de la pàgina -->
    <slot />
  </div>
</template>

<script>
export default {
}
</script>
