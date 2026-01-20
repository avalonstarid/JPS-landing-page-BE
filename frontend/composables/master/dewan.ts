import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IDewan {
  id: string
  avatar: File | string
  avatar_remove: number
  created_at: string
  jabatan: IMultiLang
  name: string
  organisasi_id: string
  updated_at: string
}

export default function useDewans() {
  const dewan = ref<Partial<IDewan>>({
    jabatan: {
      en: '',
      id: '',
    },
  })
  const dewans = ref<ApiResponse<IDewan[]>>()

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

  const getAllDewans = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDewan[]>>('/v1/master/dewan', {
        params: { all: 1, ...state },
      })

      dewans.value = res
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

  const getDewans = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDewan[]>>('/v1/master/dewan', {
        params: state,
      })

      dewans.value = res
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

  const getDewan = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDewan>>(
        `/v1/master/dewan/${id}`,
      )

      dewan.value = res.data
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

  const storeDewan = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDewan>>('/v1/master/dewan', {
        method: 'POST',
        body: data,
      })

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

  const updateDewan = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDewan>>(
        `/v1/master/dewan/${id}`,
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

  const destroyDewan = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/dewan/${id}`,
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

  const bulkDestroyDewan = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/dewan/bulk-destroy',
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
    dewan,
    dewans,
    getAllDewans,
    getDewans,
    getDewan,
    storeDewan,
    updateDewan,
    destroyDewan,
    bulkDestroyDewan,
    state,
  }
}
