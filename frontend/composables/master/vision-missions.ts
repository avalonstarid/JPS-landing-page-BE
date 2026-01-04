import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IVisionMission {
  id: string
  active: boolean
  created_at: string
  desc: IMultiLang
  icon: string
  icon_custom: boolean
  sort_order: number
  updated_at: string
}

export default function useVisionMissions() {
  const visionMission = ref<Partial<IVisionMission>>({
    active: true,
    desc: {
      id: '',
      en: '',
    },
  })
  const visionMissions = ref<ApiResponse<IVisionMission[]>>()

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const router = useRouter()
  const sanctumFetch = useSanctumClient()

  const state = reactive<IState>({
    defaultSort: undefined,
    page: 1,
    rows: 10,
    sort: null as string | null,
  })

  const getAllVisionMissions = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IVisionMission[]>>(
        '/v1/master/vision-missions',
        {
          params: { all: 1, ...state },
        },
      )

      visionMissions.value = res
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

  const getVisionMissions = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IVisionMission[]>>(
        '/v1/master/vision-missions',
        {
          params: state,
        },
      )

      visionMissions.value = res
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

  const getVisionMission = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IVisionMission>>(
        `/v1/master/vision-missions/${id}`,
      )

      visionMission.value = res.data
    } catch (e: any) {
      router.back()

      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    } finally {
      loading.value = false
    }
  }

  const storeVisionMission = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IVisionMission>>(
        '/v1/master/vision-missions',
        {
          method: 'POST',
          body: data,
        },
      )

      router.back()

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

  const updateVisionMission = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IVisionMission>>(
        `/v1/master/vision-missions/${id}`,
        {
          method: 'PUT',
          body: data,
        },
      )

      router.back()

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

  const destroyVisionMission = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/vision-missions/${id}`,
        {
          method: 'DELETE',
        },
      )

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

  const bulkDestroyVisionMission = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/vision-missions/bulk-destroy',
        {
          method: 'DELETE',
          body: data,
        },
      )

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
    visionMission,
    visionMissions,
    getAllVisionMissions,
    getVisionMissions,
    getVisionMission,
    storeVisionMission,
    updateVisionMission,
    destroyVisionMission,
    bulkDestroyVisionMission,
    state,
  }
}
