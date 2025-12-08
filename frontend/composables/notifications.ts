export default function useNotifications() {
  const sanctumFetch = useSanctumClient()
  const store = useNotificationStore()

  const state = reactive({
    rows: 10,
    page: 1,
    sort: ref<any>(null),
  })

  const getNotifications = async (infinite: boolean = false) => {
    store.loading = true

    try {
      let res = await sanctumFetch('/v1/notifications', {
        params: state,
      })

      if (infinite) {
        store.notifications = store.notifications.data.concat(res.data)
      } else {
        store.notifications = res.data
      }
    } catch (e: any) {
      store.errors = e

      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      store.loading = false
    }
  }

  const getUnreadNotificationCount = async () => {
    store.loading = true

    try {
      let res = await sanctumFetch('/v1/notifications/unread-count')

      store.unreadCount = res.data
    } catch (e: any) {
      store.errors = e

      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      store.loading = false
    }
  }

  const markAsReadNotification = async (id: string) => {
    store.errors = ''
    store.loading = true

    try {
      let res = await sanctumFetch(`/v1/notifications/mark-as-read/${id}`, {
        method: 'PUT',
      })
    } catch (e: any) {
      if (e.response) {
        store.errors = e.response._data
      } else {
        store.errors = e
      }

      ElNotification({
        title: 'Error',
        message: store.errors['message'],
        type: 'error',
      })
    } finally {
      store.loading = false
    }
  }

  const readAllNotifications = async () => {
    store.errors = ''
    store.loading = true

    try {
      let res = await sanctumFetch(`/v1/notifications/read-all`, {
        method: 'POST',
      })
    } catch (e: any) {
      if (e.response) {
        store.errors = e.response._data
      } else {
        store.errors = e
      }

      ElNotification({
        title: 'Error',
        message: store.errors['message'],
        type: 'error',
      })
    } finally {
      store.loading = false
    }
  }

  return {
    getNotifications,
    getUnreadNotificationCount,
    markAsReadNotification,
    readAllNotifications,
    state,
  }
}
