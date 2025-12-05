<template>
  <div class="mb-5 lg:mb-7.5">
    <div class="container-fluid">
      <div class="flex flex-wrap items-center justify-between gap-5 my-5">
        <div class="flex items-center gap-3">
          <h1 class="text-lg font-medium text-gray-900">{{ pageTitle }}</h1>
          <span class="h-[25px] border-gray-300 border-s"></span>
          <div class="flex items-center gap-1 text-2sm">
            <NuxtLink class="text-gray-700 hover:text-primary" to="/">
              Home
            </NuxtLink>
            <template v-for="(item, i) in breadcrumbs" :key="i">
              <i class="text-gray-500 ki-filled ki-right text-3xs"></i>
              <NuxtLink
                class="text-gray-700 hover:text-primary"
                :to="item.to ?? '#'"
              >
                {{ item.title }}
              </NuxtLink>
            </template>
            <i class="text-gray-500 ki-filled ki-right text-3xs"></i>
            <span class="text-gray-900">{{ pageTitle }}</span>
          </div>
        </div>
      </div>
      <div class="border-b border-gray-200 dark:border-coal-100"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Breadcrumb {
  title: string
  to?: string
}

const route = useRoute()

const pageTitle = computed(() => {
  return route.meta.title
})

const breadcrumbs = computed(() => {
  return route.meta.breadcrumbs as Breadcrumb[]
})
</script>
