<template>
  <header
    id="header"
    class="flex lg:hidden items-center fixed z-10 top-0 start-0 end-0 shrink-0 bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] h-[--tw-header-height]"
  >
    <!-- Container -->
    <div
      class="flex flex-wrap items-center justify-between gap-3 container-fixed"
    >
      <NuxtLink to="/">
        <img
          class="dark:hidden min-h-[30px]"
          src="/media/app/mini-logo-gray.svg"
        />
        <img
          class="hidden dark:block min-h-[30px]"
          src="/media/app/mini-logo-gray-dark.svg"
        />
      </NuxtLink>
      <button
        class="btn btn-icon btn-light btn-clear btn-sm -me-2"
        data-drawer-toggle="#sidebar"
      >
        <i class="ki-filled ki-menu"> </i>
      </button>
    </div>
    <!-- End of Container -->
  </header>
</template>

<script setup lang="ts"></script>
