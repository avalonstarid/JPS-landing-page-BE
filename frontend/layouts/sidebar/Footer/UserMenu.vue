<template>
  <div
    class="menu-dropdown menu-default light:border-gray-300 w-screen max-w-[250px]"
  >
    <div class="flex items-center justify-between px-5 py-1.5 gap-1.5">
      <div class="flex items-center gap-2">
        <img
          class="border-2 rounded-full size-9 border-success"
          :alt="user?.name"
          :src="user?.avatar ?? '/media/avatars/blank.png'"
        />
        <div class="flex flex-col gap-1.5">
          <span class="text-sm font-semibold leading-none text-gray-800">
            {{ user?.name }}
          </span>
          <a
            class="text-xs font-medium leading-none text-gray-600 hover:text-primary"
            href="#"
          >
            {{ user?.email }}
          </a>
        </div>
      </div>
      <span class="badge badge-xs badge-primary badge-outline"> Pro </span>
    </div>
    <div class="menu-separator"></div>
    <div class="flex flex-col">
      <div class="menu-item">
        <NuxtLink class="menu-link" :to="{ name: 'profile-general' }">
          <span class="menu-icon">
            <i class="ki-filled ki-badge"> </i>
          </span>
          <span class="menu-title">Profil</span>
        </NuxtLink>
      </div>
      <div
        class="menu-item"
        data-menu-item-offset="-10px, 0"
        data-menu-item-placement="left-start"
        data-menu-item-toggle="dropdown"
        data-menu-item-trigger="click|lg:hover"
      >
        <div class="menu-link">
          <span class="menu-icon">
            <i class="ki-filled ki-icon"> </i>
          </span>
          <span class="menu-title">Bahasa</span>
          <div
            class="flex items-center gap-1.5 rounded-md border border-gray-300 text-gray-600 p-1.5 text-2xs font-medium shrink-0"
          >
            Indonesia
            <img
              class="inline-block size-3.5 rounded-full"
              alt=""
              src="/media/flags/indonesia.svg"
            />
          </div>
        </div>
        <div
          class="menu-dropdown menu-default light:border-gray-300 w-full max-w-[170px]"
        >
          <div class="menu-item active">
            <a class="h-10 menu-link" href="#">
              <span class="menu-icon">
                <img
                  class="inline-block rounded-full size-4"
                  alt=""
                  src="/media/flags/indonesia.svg"
                />
              </span>
              <span class="menu-title">Indonesia</span>
              <span class="menu-badge">
                <i class="text-base ki-solid ki-check-circle text-success"> </i>
              </span>
            </a>
          </div>
          <!-- <div class="menu-item">
                <a class="h-10 menu-link" href="#">
                  <span class="menu-icon">
                    <img
                      class="inline-block rounded-full size-4"
                      alt=""
                      src="/media/flags/united-states.svg"
                    />
                  </span>
                  <span class="menu-title">English</span>
                </a>
              </div> -->
        </div>
      </div>
    </div>
    <div class="menu-separator"></div>
    <div class="flex flex-col">
      <div class="menu-item px-4 py-1.5">
        <button
          class="justify-center btn btn-sm btn-light"
          :disabled="loadingLogout"
          @click="handleLogout"
        >
          <i v-if="loadingLogout" class="ki-filled ki-loading animate-spin"></i>
          Keluar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { IUser } from '@/composables/user-management/users'

const user = useSanctumUser<IUser>()
const { logout } = useSanctumAuth()
const loadingLogout = ref<boolean>(false)

const handleLogout = async () => {
  loadingLogout.value = true
  await logout()
  loadingLogout.value = false
}
</script>
