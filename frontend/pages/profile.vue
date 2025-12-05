<template>
  <div class="flex grow gap-5 lg:gap-7.5">
    <div class="w-[200px] shrink-0">
      <div
        class="w-[200px]"
        data-sticky="true"
        data-sticky-animation="true"
        data-sticky-class="fixed z-[4] left-auto top-[calc(var(--tw-header-height)+1.875rem)]"
        data-sticky-name="scrollspy"
        data-sticky-offset="200"
      >
        <div
          class="flex flex-col grow relative before:absolute before:left-[11px] before:top-0 before:bottom-0 before:border-l before:border-gray-200"
        >
          <NuxtLink
            class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100"
            to="/profile/general"
            active-class="active"
            data-scrollspy-anchor="true"
          >
            <span
              class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary"
            >
            </span>
            General
          </NuxtLink>
          <NuxtLink
            class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100"
            to="/profile/change-password"
            active-class="active"
            data-scrollspy-anchor="true"
          >
            <span
              class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary"
            >
            </span>
            Ganti Kata Sandi
          </NuxtLink>
          <NuxtLink
            class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 border border-transparent text-2sm text-gray-800 hover:text-primary hover:font-medium scrollspy-active:bg-secondary-active scrollspy-active:text-primary scrollspy-active:font-medium dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100"
            to="/profile/browser-sessions"
            active-class="active"
            data-scrollspy-anchor="true"
          >
            <span
              class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary"
            >
            </span>
            Perangkat
          </NuxtLink>
        </div>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<script setup lang="ts"></script>
