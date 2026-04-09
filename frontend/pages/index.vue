<template>
  <Catalogo />
</template>

<script setup>
if (process.client && !sessionStorage.getItem('token')) {
  navigateTo('/login')
}
</script>
