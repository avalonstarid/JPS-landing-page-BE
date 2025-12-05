export default function useSessions() {
  const session = ref<any>({})
  const sessions = ref<any>({})

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const sanctumFetch = useSanctumClient()

  const getSessions = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/sessions')

      sessions.value = res
    } catch (e: any) {
      errors.value = e

      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  const getCurrentSession = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/sessions/current')

      session.value = res.data
    } catch (e: any) {
      errors.value = e

      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  const destroySession = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/user-management/sessions/${id}`, {
        method: 'DELETE',
      })

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })
    } catch (e: any) {
      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  const destroyOtherSession = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/sessions/logout-other', {
        method: 'DELETE',
      })

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })
    } catch (e: any) {
      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  return {
    errors,
    loading,
    session,
    sessions,
    getSessions,
    getCurrentSession,
    destroySession,
    destroyOtherSession,
  }
}
