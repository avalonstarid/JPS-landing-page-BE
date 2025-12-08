<template>
  <div
    id="sidebar"
    class="hidden top-0 bottom-0 z-20 fixed lg:flex flex-col bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] w-[--tw-sidebar-width] shrink-0"
    data-drawer="true"
    data-drawer-class="top-0 bottom-0 flex drawer drawer-start"
    data-drawer-enable="true|lg:false"
  >
    <!-- Sidebar Header -->
    <div id="sidebar_header">
      <div class="flex justify-center items-center px-3.5 h-[70px]">
        <NuxtLink to="/">
          <img class="dark:hidden h-[40px]" src="/icon/Logo-Janu-Putra-2.png" />
          <img
            class="hidden dark:inline-block h-[40px]"
            src="/icon/Logo-Janu-Putra-2.png"
          />
        </NuxtLink>
      </div>
      <div class="mb-1 px-3.5 pt-2.5">
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
      class="flex justify-center items-stretch my-5 grow shrink-0"
      v-loading="loadingSideMenu"
    >
      <div
        class="light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] scrollable-y-auto grow"
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
      class="flex flex-center justify-between mb-3.5 ps-4 pe-3.5 shrink-0"
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
