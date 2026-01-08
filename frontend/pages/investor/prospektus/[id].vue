<template>
  <FormInvestorDocument
    :id="route.params.id.toString()"
    category="prospektus"
  />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'Prospektus', to: '/investor/prospektus' },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
