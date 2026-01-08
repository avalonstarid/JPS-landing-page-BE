<template>
  <FormInvestorFinancialReport :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Laporan Keuangan', to: '/investor/financial-reports' },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
