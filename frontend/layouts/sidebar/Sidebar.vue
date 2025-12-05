<template>
  <div
    id="sidebar"
    class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col shrink-0 w-[--tw-sidebar-width] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]"
    data-drawer="true"
    data-drawer-class="top-0 bottom-0 flex drawer drawer-start"
    data-drawer-enable="true|lg:false"
  >
    <!-- Sidebar Header -->
    <div id="sidebar_header">
      <div class="flex items-center justify-center px-3.5 h-[70px]">
        <NuxtLink to="/">
          <img class="dark:hidden h-[30px]" src="/media/app/default-logo.svg" />
          <img
            class="hidden dark:inline-block h-[30px]"
            src="/media/app/default-logo-dark.svg"
          />
        </NuxtLink>
      </div>
      <div class="pt-2.5 px-3.5 mb-1">
        <el-autocomplete
          v-model="searchMenu"
          :fetch-suggestions="querySearchMenu"
          placeholder="Search Menu"
          size="large"
          clearable
          @select="handleSearchMenu"
        >
          <template #prefix>
            <i class="ki-filled ki-magnifier"></i>
          </template>
          <template #default="{ item }">
            <i
              v-if="item.icon"
              :class="`ki-filled text-lg ki-${item.icon}`"
            ></i>
            {{ item.title }}
          </template>
        </el-autocomplete>
      </div>
    </div>
    <!-- End of Sidebar Header -->
    <!-- Sidebar menu -->
    <div
      id="sidebar_menu"
      class="flex items-stretch justify-center my-5 grow shrink-0"
      v-loading="loadingSideMenu"
    >
      <div
        class="scrollable-y-auto light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] grow"
        data-scrollable="true"
        data-scrollable-dependencies="#sidebar_header, #sidebar_footer"
        data-scrollable-height="auto"
        data-scrollable-offset="0px"
        data-scrollable-wrappers="#sidebar_menu"
      >
        <!-- Primary Menu -->
        <KTSidebarMenu />
        <!-- End of Primary Menu -->
      </div>
    </div>
    <!-- End of Sidebar menu-->
    <!-- Footer -->
    <div
      id="sidebar_footer"
      class="flex flex-center justify-between shrink-0 ps-4 pe-3.5 mb-3.5"
    >
      <KTSidebarFooter />
    </div>
    <!-- End of Footer -->
  </div>
</template>

<script setup lang="ts">
import useMenus from '@/composables/settings/menu'
import KTSidebarFooter from '@/layouts/sidebar/Footer.vue'
import KTSidebarMenu from '@/layouts/sidebar/Menu.vue'

const route = useRoute()
const router = useRouter()
const menuStore = useMenuStore()
const menuSearch = reactive(useMenus())
const searchMenu = ref('')

const loadingSideMenu = computed(() => {
  return menuStore.loading
})

const querySearchMenu = (queryString: string, cb: (arg: any) => void) => {
  menuSearch.state['filter[search]'] = queryString
  menuSearch.getAllMenus().then(() => {
    cb(menuSearch.menus.data)
  })
}
const handleSearchMenu = (item: Record<string, any>) => {
  if (
    route.matched.some(({ path }) => path === item.to) &&
    item.to !== router.currentRoute.value.path
  ) {
    navigateTo(item.to)
  }
}
</script>
