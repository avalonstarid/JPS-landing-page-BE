<template>
  <FormInvestorDocument category="prospektus" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Prospektus', to: '/investor/prospektus' },
  ],
  authorize: ['document_investor_create'],
})
</script>
