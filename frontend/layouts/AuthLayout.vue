<template>
  <div class="grid lg:grid-cols-2 grow">
    <div
      class="order-1 bg-top bg-no-repeat lg:rounded-xl lg:border lg:border-gray-200 lg:m-5 lg:order-2 xxl:bg-center xl:bg-cover branded-bg"
    >
      <div class="flex flex-col gap-4 p-8 lg:p-16">
        <a href="#">
          <img class="h-[28px] max-w-none" src="/media/app/mini-logo.svg" />
        </a>
        <div class="flex flex-col gap-3">
          <h3 class="text-2xl font-semibold text-gray-900">
            Secure Access Portal
          </h3>
          <div class="text-base font-medium text-gray-600">
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
    <div class="flex flex-col items-center justify-center order-2 p-8 lg:p-10">
      <div class="card max-w-[400px] w-full">
        <slot />
      </div>

      <div
        class="flex flex-col items-center justify-center gap-1 mt-10 font-normal text-gray-500 text-2sm"
      >
        <div>
          {{
            currentYear === parseInt(appCreated.toString())
              ? appCreated
              : `${appCreated} - ${currentYear}`
          }}
          &copy;
          <span class="text-gray-600 cursor-pointer hover:text-primary">
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
