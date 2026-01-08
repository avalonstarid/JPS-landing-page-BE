<template>
  <FormInvestorDocument
    :id="route.params.id.toString()"
    category="laporan-tahunan"
  />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Laporan Tahunan', to: '/investor/laporan-tahunan' },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
