<template>
  <ElTabs tab-position="left">
    <ElTabPane label="Profile Perusahaan">
      <FormSettingsGeneralCompanyProfile />
    </ElTabPane>
    <ElTabPane label="Landing Beranda">
      <FormSettingsGeneralLandingBeranda />
    </ElTabPane>
    <ElTabPane label="Landing Hubungi Kami">
      <FormSettingsGeneralLandingHubungiKami />
    </ElTabPane>
    <ElTabPane label="Landing Berita">
      <FormSettingsGeneralLandingBerita />
    </ElTabPane>
    <ElTabPane label="Landing Blog">
      <FormSettingsGeneralLandingBlog />
    </ElTabPane>
    <ElTabPane label="Landing Lini Bisnis">
      <FormSettingsGeneralLandingLiniBisnis />
    </ElTabPane>
    <ElTabPane label="Landing Tentang Perusahaan">
      <FormSettingsGeneralLandingTentangPerusahaan />
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
