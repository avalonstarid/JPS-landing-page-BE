import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IJobPosting {
  id: string
  address: string
  category_id: string
  closed_at: string
  created_at: string
  desc: IMultiLang
  desc_short: IMultiLang
  location: string
  published_at: string
  title: IMultiLang
  updated_at: string
}

export default function useJobPostings() {
  const jobPosting = ref<Partial<IJobPosting>>({
    desc: {
      en: '',
      id: '',
    },
    desc_short: {
      en: '',
      id: '',
    },
    title: {
      en: '',
      id: '',
    },
  })
  const jobPostings = ref<ApiResponse<IJobPosting[]>>()

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

  const getAllJobPostings = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IJobPosting[]>>(
        '/v1/job-postings',
        {
          params: { all: 1, ...state },
        },
      )

      jobPostings.value = res
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

  const getJobPostings = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IJobPosting[]>>(
        '/v1/job-postings',
        {
          params: state,
        },
      )

      jobPostings.value = res
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

  const getJobPosting = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IJobPosting>>(
        `/v1/job-postings/${id}`,
      )

      jobPosting.value = res.data
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

  const storeJobPosting = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IJobPosting>>(
        '/v1/job-postings',
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

  const updateJobPosting = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IJobPosting>>(
        `/v1/job-postings/${id}`,
        {
          method: 'PUT',
          body: data,
        },
      )

      router.push('/job-postings')

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

  const destroyJobPosting = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/job-postings/${id}`,
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

  const bulkDestroyJobPosting = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/job-postings/bulk-destroy',
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
    jobPosting,
    jobPostings,
    getAllJobPostings,
    getJobPostings,
    getJobPosting,
    storeJobPosting,
    updateJobPosting,
    destroyJobPosting,
    bulkDestroyJobPosting,
    state,
  }
}
