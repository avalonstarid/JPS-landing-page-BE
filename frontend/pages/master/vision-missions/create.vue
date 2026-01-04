<template>
  <FormMasterVisionMission />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Tambah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Visi & Misi', to: '/master/vision-missions' },
  ],
  authorize: ['vision_mission_create'],
})
</script>
