<template>
  <FormInvestorFinancialReport />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Laporan Keuangan', to: '/investor/financial-reports' },
  ],
  authorize: ['document_investor_create'],
})
</script>
