import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IBusinessLine {
  id: string
  created_at: string
  desc: IMultiLang
  featured: File | string
  featured_remove: number
  images: (string | File | any)[]
  images_remove?: string[]
  sort_order: number
  title: IMultiLang
  updated_at: string
}

export default function useBusinessLines() {
  const businessLine = ref<Partial<IBusinessLine>>({
    desc: {
      id: '',
      en: '',
    },
    title: {
      id: '',
      en: '',
    },
  })
  const businessLines = ref<ApiResponse<IBusinessLine[]>>()

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

  const getAllBusinessLines = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IBusinessLine[]>>(
        '/v1/business-lines',
        {
          params: { all: 1, ...state },
        },
      )

      businessLines.value = res
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

  const getBusinessLines = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IBusinessLine[]>>(
        '/v1/business-lines',
        {
          params: state,
        },
      )

      businessLines.value = res
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

  const getBusinessLine = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IBusinessLine>>(
        `/v1/business-lines/${id}`,
      )

      businessLine.value = res.data
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

  const storeBusinessLine = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IBusinessLine>>(
        '/v1/business-lines',
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

  const updateBusinessLine = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IBusinessLine>>(
        `/v1/business-lines/${id}`,
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

  const destroyBusinessLine = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/business-lines/${id}`,
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

  const bulkDestroyBusinessLine = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/business-lines/bulk-destroy',
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
    businessLine,
    businessLines,
    getAllBusinessLines,
    getBusinessLines,
    getBusinessLine,
    storeBusinessLine,
    updateBusinessLine,
    destroyBusinessLine,
    bulkDestroyBusinessLine,
    state,
  }
}
