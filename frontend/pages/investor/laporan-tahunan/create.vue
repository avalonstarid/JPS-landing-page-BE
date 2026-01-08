<template>
  <FormInvestorDocument category="laporan-tahunan" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Laporan Tahunan', to: '/investor/laporan-tahunan' },
  ],
  authorize: ['document_investor_create'],
})
</script>
