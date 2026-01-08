<template>
  <FormInvestorDocument :id="route.params.id.toString()" category="rups" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Relasi Investor' },
    { title: 'RUPS', to: '/investor/rups' },
  ],
  authorize: ['document_investor_update'],
})

const route = useRoute()
</script>
