import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface ITestimonial {
  id: string
  client_name: string
  client_role: string
  created_at: string
  desc: IMultiLang
  title: IMultiLang
  updated_at: string
}

export default function useTestimonials() {
  const testimonial = ref<Partial<ITestimonial>>({
    desc: {
      en: '',
      id: '',
    },
    title: {
      en: '',
      id: '',
    },
  })
  const testimonials = ref<ApiResponse<ITestimonial[]>>()

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

  const getAllTestimonials = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITestimonial[]>>(
        '/v1/testimonials',
        {
          params: { all: 1, ...state },
        },
      )

      testimonials.value = res
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

  const getTestimonials = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITestimonial[]>>(
        '/v1/testimonials',
        {
          params: state,
        },
      )

      testimonials.value = res
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

  const getTestimonial = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITestimonial>>(
        `/v1/testimonials/${id}`,
      )

      testimonial.value = res.data
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

  const storeTestimonial = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITestimonial>>(
        '/v1/testimonials',
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

  const updateTestimonial = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITestimonial>>(
        `/v1/testimonials/${id}`,
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

  const destroyTestimonial = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/testimonials/${id}`,
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

  const bulkDestroyTestimonial = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/testimonials/bulk-destroy',
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
    testimonial,
    testimonials,
    getAllTestimonials,
    getTestimonials,
    getTestimonial,
    storeTestimonial,
    updateTestimonial,
    destroyTestimonial,
    bulkDestroyTestimonial,
    state,
  }
}
