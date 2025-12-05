<template>
  <!-- Base -->
  <div class="flex flex-col grow">
    <!-- Header -->
    <KTHeader />
    <!-- End of Header -->
    <!-- Wrapper -->
    <div class="flex grow flex-col pt-[--tw-header-height] lg:pt-0">
      <!-- Sidebar -->
      <KTSidebar />
      <!-- End of Sidebar -->

      <!-- Main -->
      <main
        class="scrollable-y-auto [scrollbar-width:auto] [--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] flex flex-col grow items-stretch rounded-xl bg-[--tw-content-bg] dark:bg-[--tw-content-bg-dark] border border-gray-300 dark:border-gray-200 lg:ms-[--tw-sidebar-width] pt-5 mt-0 lg:mt-[15px] m-[15px]"
        role="content"
        data-scrollable="true"
        data-scrollable-height="false|lg:auto"
        data-scrollable-offset="40px"
      >
        <!-- Toolbar -->
        <KTToolbar />
        <!-- End of Toolbar -->

        <!-- Container -->
        <div class="container-fluid">
          <slot />
        </div>
        <!-- End of Container -->

        <!-- Footer -->
        <KTFooter />
        <!-- End of Footer -->
      </main>
      <!-- End of Main -->
    </div>
    <!-- End of Wrapper -->
  </div>
  <!-- End of Base -->
</template>

<script setup lang="ts">
import useMenus from '@/composables/settings/menu'
import KTFooter from '@/layouts/footer/Footer.vue'
import KTHeader from '@/layouts/header/Header.vue'
import KTSidebar from '@/layouts/sidebar/Sidebar.vue'
import KTToolbar from '@/layouts/toolbar/Toolbar.vue'

const { getSideMenus } = useMenus()

onMounted(() => {
  getSideMenus()
})
</script>
