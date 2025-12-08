<template>
  <header
    id="header"
    class="lg:hidden top-0 z-10 fixed flex items-center bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] h-[--tw-header-height] start-0 end-0 shrink-0"
  >
    <!-- Container -->
    <div
      class="container-fixed flex flex-wrap justify-between items-center gap-3"
    >
      <NuxtLink to="/">
        <img
          class="dark:hidden min-h-[30px]"
          src="/icon/web-app-manifest-192x192.png"
        />
        <img
          class="hidden dark:block min-h-[30px]"
          src="/icon/web-app-manifest-192x192.png"
        />
      </NuxtLink>
      <button
        class="btn-clear -me-2 btn btn-icon btn-light btn-sm"
        data-drawer-toggle="#sidebar"
      >
        <i class="ki-filled ki-menu"> </i>
      </button>
    </div>
    <!-- End of Container -->
  </header>
</template>

<script setup lang="ts"></script>
