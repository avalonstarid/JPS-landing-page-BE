<template>
  <FormInvestorDocument category="keterbukaan-informasi" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Keterbukaan Informasi', to: '/investor/keterbukaan-informasi' },
  ],
  authorize: ['document_investor_create'],
})
</script>
