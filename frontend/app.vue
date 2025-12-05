<template>
  <Head>
    <Title v-if="route.meta.title">
      {{ `${route.meta.title} | ${appName}` }}
    </Title>
    <Title v-else>{{ appName }}</Title>
  </Head>
  <NuxtRouteAnnouncer />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
import KTLayout from '@/metronic/app/layouts/demo1.js'
import KTComponent from '@/metronic/core/index'

const route = useRoute()
const appName = import.meta.env.VITE_APP_NAME

onMounted(() => {
  nextTick(() => {
    KTComponent.init()
    KTLayout.init()
  })
})
</script>

<style>
@import './metronic/vendors/keenicons/duotone/style.css';
@import './metronic/vendors/keenicons/filled/style.css';
@import './metronic/vendors/keenicons/solid/style.css';
@import './metronic/vendors/keenicons/outline/style.css';

@import './metronic/css/styles.css';

#app {
  display: contents;
}
</style>
