<template>
  <div class="grid lg:grid-cols-2 grow">
    <div
      class="order-1 lg:order-2 xl:bg-cover bg-no-repeat xxl:bg-center bg-top lg:m-5 lg:border lg:border-gray-200 lg:rounded-xl branded-bg"
    >
      <div class="flex flex-col gap-4 p-8 lg:p-16">
        <a href="#">
          <img
            class="max-w-none h-[28px]"
            src="/icon/web-app-manifest-192x192.png"
          />
        </a>
        <div class="flex flex-col gap-3">
          <h3 class="font-semibold text-gray-900 text-2xl">
            Secure Access Portal
          </h3>
          <div class="font-medium text-gray-600 text-base">
            A robust authentication gateway ensuring
            <br />
            secure
            <span class="font-semibold text-gray-900">
              efficient user access
            </span>
            to the Metronic
            <br />
            Dashboard interface.
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col justify-center items-center order-2 p-8 lg:p-10">
      <div class="w-full max-w-[400px] card">
        <slot />
      </div>

      <div
        class="flex flex-col justify-center items-center gap-1 mt-10 font-normal text-2sm text-gray-500"
      >
        <div>
          {{
            currentYear === parseInt(appCreated.toString())
              ? appCreated
              : `${appCreated} - ${currentYear}`
          }}
          &copy;
          <span class="text-gray-600 hover:text-primary cursor-pointer">
            {{ appName }} </span
          >. All rights reserved.
        </div>
        <div>Made by {{ appCompany }}.</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const appName = import.meta.env.VITE_APP_NAME
const appCreated = import.meta.env.VITE_APP_CREATED || new Date().getFullYear()
const appCompany = import.meta.env.VITE_APP_COMPANY
const currentYear = new Date().getFullYear()
</script>

<style lang="scss">
.branded-bg {
  background-image: url('/media/images/2600x1600/1.png');
}
.dark .branded-bg {
  background-image: url('/media/images/2600x1600/1-dark.png');
}
</style>
