import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IFaq {
  id: string
  active: boolean
  answer: IMultiLang
  created_at: string
  question: IMultiLang
  sort_order: number
  updated_at: string
}

export default function useFaqs() {
  const faq = ref<Partial<IFaq>>({
    active: true,
    answer: {
      id: '',
      en: '',
    },
    question: {
      id: '',
      en: '',
    },
  })
  const faqs = ref<ApiResponse<IFaq[]>>()

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

  const getAllFaqs = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFaq[]>>('/v1/faq', {
        params: { all: 1, ...state },
      })

      faqs.value = res
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

  const getFaqs = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFaq[]>>('/v1/faq', {
        params: state,
      })

      faqs.value = res
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

  const getFaq = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFaq>>(`/v1/faq/${id}`)

      faq.value = res.data
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

  const storeFaq = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFaq>>('/v1/faq', {
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

  const updateFaq = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFaq>>(`/v1/faq/${id}`, {
        method: 'PUT',
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

  const destroyFaq = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(`/v1/faq/${id}`, {
        method: 'DELETE',
      })

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

  const bulkDestroyFaq = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>('/v1/faq/bulk-destroy', {
        method: 'DELETE',
        body: data,
      })

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
    faq,
    faqs,
    getAllFaqs,
    getFaqs,
    getFaq,
    storeFaq,
    updateFaq,
    destroyFaq,
    bulkDestroyFaq,
    state,
  }
}
