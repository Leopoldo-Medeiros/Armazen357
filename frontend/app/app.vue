<template>
  <div>
    <NuxtRouteAnnouncer />
    <NuxtPage />
  </div>
</template>

<script setup>
// Configurações globais da aplicação
useHead({
  title: 'Armazém 357 - Grãos de Café Premium',
  meta: [
    { name: 'description', content: 'Armazém 357 - A melhor seleção de grãos de café do Brasil para atacado e varejo' }
  ]
})
</script>
