<template>
  <FormMasterVisionMission :id="route.params.id.toString()" />
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Ubah',
  breadcrumbs: [
    { title: 'Master' },
    { title: 'Visi & Misi', to: '/master/vision-missions' },
  ],
  authorize: ['vision_mission_update'],
})

const route = useRoute()
</script>
