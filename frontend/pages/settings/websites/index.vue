<template>
  <ElTabs tab-position="left">
    <ElTabPane label="Profile Perusahaan">
      <FormSettingsGeneralCompanyProfile />
    </ElTabPane>
    <ElTabPane label="Landing Page Beranda">
      <FormSettingsGeneralLandingBeranda />
    </ElTabPane>
  </ElTabs>
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Pengaturan Situs',
  breadcrumbs: [{ title: 'Settings' }],
  authorize: [],
})
</script>
