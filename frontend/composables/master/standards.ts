import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IStandard {
  id: string
  created_at: string
  desc: IMultiLang
  icon: string
  icon_custom: boolean
  sort_order: number
  title: IMultiLang
  updated_at: string
}

export default function useStandards() {
  const standard = ref<Partial<IStandard>>({
    desc: {
      id: '',
      en: '',
    },
    title: {
      id: '',
      en: '',
    },
  })
  const standards = ref<ApiResponse<IStandard[]>>()

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

  const getAllStandards = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IStandard[]>>(
        '/v1/master/standards',
        {
          params: { all: 1, ...state },
        },
      )

      standards.value = res
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

  const getStandards = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IStandard[]>>(
        '/v1/master/standards',
        {
          params: state,
        },
      )

      standards.value = res
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

  const getStandard = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IStandard>>(
        `/v1/master/standards/${id}`,
      )

      standard.value = res.data
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

  const storeStandard = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IStandard>>(
        '/v1/master/standards',
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

  const updateStandard = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IStandard>>(
        `/v1/master/standards/${id}`,
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

  const destroyStandard = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/standards/${id}`,
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

  const bulkDestroyStandard = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/standards/bulk-destroy',
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
    standard,
    standards,
    getAllStandards,
    getStandards,
    getStandard,
    storeStandard,
    updateStandard,
    destroyStandard,
    bulkDestroyStandard,
    state,
  }
}
