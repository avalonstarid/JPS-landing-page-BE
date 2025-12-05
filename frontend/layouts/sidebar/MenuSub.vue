<template>
  <template v-for="(item, i) in items" :key="i">
    <div
      v-if="!item.children"
      class="menu-item"
      :class="{ active: hasActiveChildren(item.to) }"
    >
      <NuxtLink
        class="menu-link gap-2.5 py-2 px-2.5 rounded-md border border-transparent menu-item-active:border-gray-200 menu-item-active:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200"
        :target="item.newTab ? '_blank' : ''"
        :to="item.to"
      >
        <span
          v-if="item.icon && !hideIcon"
          class="items-start text-lg text-gray-600 menu-icon menu-item-active:text-gray-800 menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-active:text-gray-900 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900"
        >
          <i class="ki-filled" :class="`ki-${item.icon}`"></i>
        </span>
        <span
          v-if="hideIcon"
          class="menu-bullet flex w-[6px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-x-1/2 before:-translate-y-1/2 menu-item-active:before:bg-primary menu-item-hover:before:bg-primary"
        >
        </span>
        <span
          class="text-sm font-medium text-gray-800 menu-title menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900"
        >
          {{ item.title }}
        </span>
      </NuxtLink>
    </div>
    <div
      v-else
      class="menu-item"
      :class="{ show: hasActiveChildren(item.to) }"
      data-menu-item-toggle="accordion"
      data-menu-item-trigger="click"
    >
      <div
        class="menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] pl-[10px] pr-[10px] py-[6px]"
        tabindex="0"
      >
        <span
          class="items-start text-lg text-gray-600 menu-icon menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-show:text-gray-900 dark:menu-link-hover:text-gray-900"
        >
          <i class="ki-filled" :class="`ki-${item.icon}`"></i>
        </span>
        <span
          class="text-sm font-medium text-gray-800 menu-title menu-item-here:text-gray-900 menu-item-show:text-gray-900 menu-link-hover:text-gray-900"
        >
          {{ item.title }}
        </span>
        <span
          class="text-gray-600 menu-arrow menu-item-here:text-gray-800 menu-item-show:text-gray-800 menu-link-hover:text-gray-800"
        >
          <i class="ki-filled ki-plus text-2xs menu-item-show:hidden"> </i>
          <i
            class="hidden ki-filled ki-minus text-2xs menu-item-show:inline-flex"
          >
          </i>
        </span>
      </div>
      <div
        class="menu-accordion gap-0.5 pl-[10px] relative before:absolute before:left-[20px] before:top-0 before:bottom-0 before:border-l before:border-gray-400"
      >
        <KTSidebarMenuSub :items="item.children" :hide-icon="true" />
      </div>
    </div>
  </template>
</template>

<script setup lang="ts">
import type { IMenu } from '@/composables/settings/menu'
import KTSidebarMenuSub from '@/layouts/sidebar/MenuSub.vue'

const props = defineProps({
  items: Array<IMenu>,
  hideIcon: Boolean,
})

const route = useRoute()

const hasActiveChildren = (match: any) => {
  if (match === '/') {
    return route.path === '/'
  }
  return route.path.indexOf(match) !== -1
}
</script>
