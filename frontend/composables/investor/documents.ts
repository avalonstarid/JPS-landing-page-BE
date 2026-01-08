import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IDocInvestor {
  id: string
  created_at: string
  document: string | File
  featured: string | File
  featured_remove: number
  title: IMultiLang
  updated_at: string
}

export default function useDocInvestors() {
  const docInvestor = ref<Partial<IDocInvestor>>({
    title: {
      en: '',
      id: '',
    },
  })
  const docInvestors = ref<ApiResponse<IDocInvestor[]>>()

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

  const getAllDocInvestors = async (category: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDocInvestor[]>>(
        `/v1/investor/document/${category}`,
        {
          params: { all: 1, ...state },
        },
      )

      docInvestors.value = res
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

  const getDocInvestors = async (category: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDocInvestor[]>>(
        `/v1/investor/document/${category}`,
        {
          params: state,
        },
      )

      docInvestors.value = res
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

  const getDocInvestor = async (category: string, id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDocInvestor>>(
        `/v1/investor/document/${category}/${id}`,
      )

      docInvestor.value = res.data
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

  const storeDocInvestor = async (category: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDocInvestor>>(
        `/v1/investor/document/${category}`,
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

  const updateDocInvestor = async (
    category: string,
    id: string,
    data: object,
  ) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IDocInvestor>>(
        `/v1/investor/document/${category}/${id}`,
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

  const destroyDocInvestor = async (category: string, id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/investor/document/${category}/${id}`,
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

  const bulkDestroyDocInvestor = async (category: string, data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/investor/document/${category}/bulk-destroy`,
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
    docInvestor,
    docInvestors,
    getAllDocInvestors,
    getDocInvestors,
    getDocInvestor,
    storeDocInvestor,
    updateDocInvestor,
    destroyDocInvestor,
    bulkDestroyDocInvestor,
    state,
  }
}
