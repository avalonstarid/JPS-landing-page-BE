<template>
  <FormInvestorDocument
    :id="route.params.id.toString()"
    category="keterbukaan-informasi"
  />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Keterbukaan Informasi', to: '/investor/keterbukaan-informasi' },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
