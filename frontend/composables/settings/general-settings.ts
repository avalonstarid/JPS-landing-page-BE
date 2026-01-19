import type { ApiResponse } from '@/helpers/interfaces'

export default function useGeneralSettings() {
  const generalSetting = ref<any>({})

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const sanctumFetch = useSanctumClient()

  const getGeneralSetting = async (group: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<any>>(
        `/v1/settings/general-settings/${group}`,
      )

      generalSetting.value = res.data
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

  const storeCompanyProfile = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<any>>(
        '/v1/settings/general-settings/company-profile',
        {
          method: 'POST',
          body: data,
        },
      )

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

  const storeLandingBeranda = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<any>>(
        '/v1/settings/general-settings/landing-beranda',
        {
          method: 'POST',
          body: data,
        },
      )

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
    generalSetting,
    getGeneralSetting,
    storeCompanyProfile,
    storeLandingBeranda,
  }
}
