export const useNotificationStore = defineStore('notifications', () => {
  const notifications = ref<any>([])
  const unreadCount = ref<any>(0)

  const errors = ref({})
  const loading = ref<boolean>(false)

  return { errors, notifications, unreadCount, loading }
})
