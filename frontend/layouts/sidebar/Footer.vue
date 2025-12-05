<template>
  <div class="menu" data-menu="true">
    <div
      class="menu-item"
      data-menu-item-offset="-10px, 15px"
      data-menu-item-placement="right-end"
      data-menu-item-toggle="dropdown"
      data-menu-item-trigger="click|lg:click"
    >
      <div class="rounded-full menu-toggle btn btn-icon">
        <img
          class="justify-center border border-gray-500 rounded-full size-8 shrink-0"
          :alt="user?.name"
          :src="user?.avatar ?? '/media/avatars/blank.png'"
        />
      </div>
      <KTSidebarFooterUserMenu />
    </div>
  </div>

  <div class="flex items-center gap-1.5">
    <div
      class="dropdown"
      title="Notifikasi"
      data-dropdown="true"
      data-dropdown-offset="10px, 15px"
      data-dropdown-placement="right-end"
      data-dropdown-trigger="click|lg:click"
    >
      <button
        class="relative text-gray-600 dropdown-toggle btn btn-icon btn-icon-lg size-8 hover:bg-light hover:text-primary dropdown-open:bg-gray-200"
      >
        <i
          :class="`ki-filled ki-notification${notifStore.unreadCount > 0 ? '-on' : ''}`"
        ></i>
        <span
          v-if="notifStore.unreadCount > 0"
          class="badge badge-dot badge-danger size-[5px] absolute top-0.5 right-0.5 transform translate-y-1/2"
        ></span>
      </button>
      <div class="dropdown-content light:border-gray-300 w-full max-w-[460px]">
        <div
          id="notifications_header"
          class="flex items-center justify-between gap-2.5 text-sm text-gray-900 font-semibold px-5 py-2.5 border-b border-b-gray-200"
        >
          Notifikasi ({{ notifStore.unreadCount }})
          <button
            class="btn btn-sm btn-icon btn-light btn-clear shrink-0"
            data-dropdown-dismiss="true"
          >
            <i class="ki-filled ki-cross"></i>
          </button>
        </div>
        <KTSidebarFooterNotif />
      </div>
    </div>
    <button
      class="text-gray-600 btn btn-icon btn-icon-lg size-8 hover:bg-light hover:text-primary dark:hidden"
      title="Dark Mode"
      data-theme-toggle="true"
    >
      <i class="ki-filled ki-moon"></i>
    </button>
    <button
      class="hidden text-gray-600 btn btn-icon btn-icon-lg size-8 hover:bg-light hover:text-primary dark:flex"
      title="Light Mode"
      data-theme-toggle="true"
    >
      <i class="ki-filled ki-sun"></i>
    </button>
    <button
      class="text-gray-600 btn btn-icon btn-icon-lg size-8 hover:bg-light hover:text-primary"
      :disabled="loadingLogout"
      title="Keluar"
      @click="handleLogout"
    >
      <i class="ki-filled ki-exit-right"></i>
    </button>
  </div>
</template>

<script setup lang="ts">
import type { IUser } from '@/composables/user-management/users'
import KTSidebarFooterNotif from '@/layouts/sidebar/Footer/Notification.vue'
import KTSidebarFooterUserMenu from '@/layouts/sidebar/Footer/UserMenu.vue'

const notifStore = useNotificationStore()
const user = useSanctumUser<IUser>()
const { logout } = useSanctumAuth()
const loadingLogout = ref<boolean>(false)

const handleLogout = async () => {
  loadingLogout.value = true
  await logout()
  loadingLogout.value = false
}
</script>
