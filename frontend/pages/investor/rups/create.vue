<template>
  <FormInvestorDocument category="rups" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'RUPS', to: '/investor/rups' },
  ],
  authorize: ['document_investor_create'],
})
</script>
