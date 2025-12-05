<template>
  <footer class="footer">
    <div class="container-fluid">
      <div
        class="flex flex-col items-center justify-center gap-3 py-5 md:flex-row md:justify-between"
      >
        <div class="flex font-normal text-gray-500 text-2sm">
          <span class="me-1">
            {{
              currentYear === parseInt(appCreated.toString())
                ? appCreated
                : `${appCreated} - ${currentYear}`
            }}
            &copy;
          </span>
          <span class="text-gray-600 cursor-pointer hover:text-primary">
            {{ appName }} </span
          >. All rights reserved.
        </div>
        <div class="flex font-normal text-gray-600 text-2sm">
          Made by {{ appCompany }}.
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const appName = import.meta.env.VITE_APP_NAME
const appCreated = import.meta.env.VITE_APP_CREATED || new Date().getFullYear()
const appCompany = import.meta.env.VITE_APP_COMPANY
const currentYear = new Date().getFullYear()
</script>
