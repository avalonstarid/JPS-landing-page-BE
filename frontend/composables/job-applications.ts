import type { ApiResponse, IState } from '@/helpers/interfaces'

export interface IjobApplication {
  id: string
  age: number
  created_at: string
  email: string
  gender_id: string
  job_posting_id: string
  jurusan: string
  name: string
  phone: string
  school_name: string
  reason: string
  status_kawin_id: string
  updated_at: string
}

export default function usejobApplications() {
  const jobApplication = ref<Partial<IjobApplication>>({})
  const jobApplications = ref<ApiResponse<IjobApplication[]>>()

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

  const getAlljobApplications = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IjobApplication[]>>(
        '/v1/job-applications',
        {
          params: { all: 1, ...state },
        },
      )

      jobApplications.value = res
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

  const getjobApplications = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IjobApplication[]>>(
        '/v1/job-applications',
        {
          params: state,
        },
      )

      jobApplications.value = res
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

  const getjobApplication = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IjobApplication>>(
        `/v1/job-applications/${id}`,
      )

      jobApplication.value = res.data
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

  const storejobApplication = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IjobApplication>>(
        '/v1/job-applications',
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

  const updatejobApplication = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IjobApplication>>(
        `/v1/job-applications/${id}`,
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

  const destroyjobApplication = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/job-applications/${id}`,
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

  const bulkDestroyjobApplication = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/job-applications/bulk-destroy',
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
    jobApplication,
    jobApplications,
    getAlljobApplications,
    getjobApplications,
    getjobApplication,
    storejobApplication,
    updatejobApplication,
    destroyjobApplication,
    bulkDestroyjobApplication,
    state,
  }
}
