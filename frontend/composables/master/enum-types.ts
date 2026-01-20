import type { ApiResponse, IState } from '@/helpers/interfaces'

export interface IEnumType {
  id: string
  code: string
  created_at: string
  desc: string
  name: string
  updated_at: string
}

export default function useEnumTypes() {
  const enumType = ref<Partial<IEnumType>>({})
  const enumTypes = ref<ApiResponse<IEnumType[]>>()

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

  const getAllEnumTypes = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IEnumType[]>>(
        '/v1/master/enum-types',
        {
          params: { all: 1, ...state },
        },
      )

      enumTypes.value = res
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

  const getEnumTypes = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IEnumType[]>>(
        '/v1/master/enum-types',
        {
          params: state,
        },
      )

      enumTypes.value = res
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

  const getEnumType = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IEnumType>>(
        `/v1/master/enum-types/${id}`,
      )

      enumType.value = res.data
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

  const storeEnumType = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IEnumType>>(
        '/v1/master/enum-types',
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

  const updateEnumType = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IEnumType>>(
        `/v1/master/enum-types/${id}`,
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

  const destroyEnumType = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/enum-types/${id}`,
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

  const bulkDestroyEnumType = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/enum-types/bulk-destroy',
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
    enumType,
    enumTypes,
    getAllEnumTypes,
    getEnumTypes,
    getEnumType,
    storeEnumType,
    updateEnumType,
    destroyEnumType,
    bulkDestroyEnumType,
    state,
  }
}
