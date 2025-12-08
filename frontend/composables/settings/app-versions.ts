export default function useAppVersions() {
  const appVersion = ref<any>({})

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const sanctumFetch = useSanctumClient()

  const getAppVersion = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/settings/app-versions`)
      console.log(res.data)

      appVersion.value = res.data ?? {}
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

  const getCurrAppVersion = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/app-version`)

      appVersion.value = res.data ?? {}
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

  const storeAppVersion = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/settings/app-versions', {
        method: 'POST',
        body: data,
      })

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })
    } catch (e: any) {
      if (e.response) {
        errors.value = e.response._data
      } else {
        errors.value = e
      }

      ElNotification({
        title: 'Error',
        message: errors.value['message'],
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  return {
    errors,
    loading,
    appVersion,
    getAppVersion,
    getCurrAppVersion,
    storeAppVersion,
  }
}
