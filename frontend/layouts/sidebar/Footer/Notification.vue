<template>
  <div class="grow">
    <div class="flex flex-col">
      <div
        class="scrollable-y-auto"
        data-scrollable="true"
        data-scrollable-dependencies="#header"
        data-scrollable-max-height="auto"
        data-scrollable-offset="200px"
      >
        <div class="flex flex-col divider-y divider-gray-200">
          <template v-for="item in notifStore.notifications">
            <NuxtLink :to="item.data.url" @click="clickNotification(item)">
              <div class="flex grow gap-2.5 px-5 py-3 relative">
                <div class="relative shrink-0">
                  <i class="text-2xl ki-filled ki-information text-info"></i>
                </div>
                <div class="flex flex-col w-full gap-2">
                  <div
                    class="card shadow-none flex flex-col gap-2 p-3.5 rounded-lg bg-light-active text-2sm font-medium text-gray-700 mb-px"
                  >
                    <div class="font-semibold text-gray-900">
                      {{ item.data.title }}
                    </div>
                    {{ item.data.message }}
                  </div>
                  <div
                    class="flex items-center font-medium text-gray-500 capitalize text-3xs"
                  >
                    {{ $dayjs(item.created_at).fromNow() }}
                    <span class="badge badge-circle bg-gray-500 size-1 mx-1.5">
                    </span>
                    {{ $dayjs(item.created_at).format('HH:mm') }}
                    <span class="badge badge-circle bg-gray-500 size-1 mx-1.5">
                    </span>
                    {{ item.type }}
                  </div>
                </div>
                <div
                  v-if="item.read_at === null"
                  class="absolute top-0 bottom-0 left-0 right-0 w-full h-full overflow-hidden bg-fixed bg-primary opacity-5"
                ></div>
              </div>
            </NuxtLink>
            <div class="my-1 border-b border-b-gray-200"></div>
          </template>
        </div>
      </div>
      <div id="notifications_all_footer" class="p-5">
        <button
          class="justify-center w-full btn btn-sm btn-light"
          :disabled="notifStore.loading"
          @click="readAllNotification"
        >
          Tandai semua telah dibaca
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import useNotifications from '@/composables/notifications'
import type { IUser } from '@/composables/user-management/users'

const user = useSanctumUser<IUser>()
const notifStore = useNotificationStore()
// const echo = useEcho()

const {
  getNotifications,
  getUnreadNotificationCount,
  markAsReadNotification,
  readAllNotifications,
} = useNotifications()

onMounted(() => {
  getNotifications()
  getUnreadNotificationCount()
  // subsChannelNotifications()
})

const clickNotification = (notification: any) => {
  markAsReadNotification(notification.id).then(() => {
    getNotifications()
    getUnreadNotificationCount()
  })
}

const readAllNotification = () => {
  readAllNotifications().then(() => {
    getNotifications()
    getUnreadNotificationCount()
  })
}

// const subsChannelNotifications = () => {
//   echo
//     .private(`users.${user.value?.id}`)
//     .listen('.notifications.created', (item: any) => {
//       getNotifications()
//       getUnreadNotificationCount()

//       ElNotification({
//         customClass: 'cursor-pointer',
//         message: item.message,
//         position: 'bottom-right',
//         title: item.title,
//         onClick() {
//           navigateTo({ path: item.url })
//           clickNotification(item)
//         },
//       })
//     })
//     .error((e: object) => {
//       console.error('Private channel error', e)
//     })
// }
</script>
