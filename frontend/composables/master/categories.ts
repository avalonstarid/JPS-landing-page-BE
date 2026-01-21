import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface ICategory {
  id: string
  created_at: string
  desc: IMultiLang
  name: IMultiLang
  parent: ICategory | null
  parent_id: string | null
  slug: string
  updated_at: string
}

export default function useCategories() {
  const category = ref<Partial<ICategory>>({
    desc: {
      en: '',
      id: '',
    },
    name: {
      en: '',
      id: '',
    },
  })
  const categories = ref<ApiResponse<ICategory[]>>()

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

  const getAllCategories = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ICategory[]>>(
        '/v1/master/categories',
        {
          params: { all: 1, ...state },
        },
      )

      categories.value = res
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

  const getCategories = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ICategory[]>>(
        '/v1/master/categories',
        {
          params: state,
        },
      )

      categories.value = res
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

  const getCategory = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ICategory>>(
        `/v1/master/categories/${id}`,
      )

      category.value = res.data
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

  const storeCategory = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ICategory>>(
        '/v1/master/categories',
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

  const updateCategory = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ICategory>>(
        `/v1/master/categories/${id}`,
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

  const destroyCategory = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/categories/${id}`,
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

  const bulkDestroyCategory = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/categories/bulk-destroy',
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
    category,
    categories,
    getAllCategories,
    getCategories,
    getCategory,
    storeCategory,
    updateCategory,
    destroyCategory,
    bulkDestroyCategory,
    state,
  }
}
