<template>
  <div
    class="menu flex flex-col w-full gap-1.5 px-3.5"
    data-menu="true"
    data-menu-accordion-expand-all="true"
  >
    <template v-for="(item, i) in sideMenus" :key="i">
      <div class="border-b border-gray-300 mt-1 mx-1.5"></div>
      <div
        class="menu-item show"
        data-menu-item-toggle="accordion"
        data-menu-item-trigger="click"
      >
        <div class="flex items-center justify-between menu-label">
          <div
            class="menu-toggle cursor-pointer pb-2 pt-3 ps-[14.5px] rounded-md border border-transparent"
          >
            <span class="menu-arrow me-2.5">
              <i
                class="text-sm text-gray-500 ki-filled ki-right menu-item-show:hidden"
              >
              </i>
              <i
                class="hidden text-sm text-gray-500 ki-filled ki-down menu-item-show:inline-flex"
              >
              </i>
            </span>
            <span class="text-sm font-medium text-gray-800 menu-title">
              {{ item.title }}
            </span>
          </div>
        </div>
        <div class="menu-accordion">
          <KTSidebarMenuSub :items="item.children" />
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import KTSidebarMenuSub from '@/layouts/sidebar/MenuSub.vue'

const menuStore = useMenuStore()

const sideMenus = computed(() => {
  return menuStore.menus
})
</script>
