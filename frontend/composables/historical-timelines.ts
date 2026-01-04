import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IHistoricalTimeline {
  id: string
  created_at: string
  desc: IMultiLang
  icon: string
  icon_custom: boolean
  title: IMultiLang
  updated_at: string
  year: number
}

export default function useHistoricalTimelines() {
  const historicalTimeline = ref<Partial<IHistoricalTimeline>>({
    desc: {
      id: '',
      en: '',
    },
    title: {
      id: '',
      en: '',
    },
  })
  const historicalTimelines = ref<ApiResponse<IHistoricalTimeline[]>>()

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

  const getAllHistoricalTimelines = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IHistoricalTimeline[]>>(
        '/v1/historical-timelines',
        {
          params: { all: 1, ...state },
        },
      )

      historicalTimelines.value = res
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

  const getHistoricalTimelines = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IHistoricalTimeline[]>>(
        '/v1/historical-timelines',
        {
          params: state,
        },
      )

      historicalTimelines.value = res
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

  const getHistoricalTimeline = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IHistoricalTimeline>>(
        `/v1/historical-timelines/${id}`,
      )

      historicalTimeline.value = res.data
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

  const storeHistoricalTimeline = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IHistoricalTimeline>>(
        '/v1/historical-timelines',
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

  const updateHistoricalTimeline = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IHistoricalTimeline>>(
        `/v1/historical-timelines/${id}`,
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

  const destroyHistoricalTimeline = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/historical-timelines/${id}`,
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

  const bulkDestroyHistoricalTimeline = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/historical-timelines/bulk-destroy',
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
    historicalTimeline,
    historicalTimelines,
    getAllHistoricalTimelines,
    getHistoricalTimelines,
    getHistoricalTimeline,
    storeHistoricalTimeline,
    updateHistoricalTimeline,
    destroyHistoricalTimeline,
    bulkDestroyHistoricalTimeline,
    state,
  }
}
