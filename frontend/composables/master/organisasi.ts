import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IOrganization {
  id: string
  created_at: string
  desc: IMultiLang
  name: IMultiLang
  parent: IOrganization | null
  parent_id: string | null
  updated_at: string
}

export default function useOrganizations() {
  const organization = ref<Partial<IOrganization>>({
    desc: {
      en: '',
      id: '',
    },
    name: {
      en: '',
      id: '',
    },
  })
  const organizations = ref<ApiResponse<IOrganization[]>>()

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

  const getAllOrganizations = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IOrganization[]>>(
        '/v1/master/organisasi',
        {
          params: { all: 1, ...state },
        },
      )

      organizations.value = res
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

  const getOrganizations = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IOrganization[]>>(
        '/v1/master/organisasi',
        {
          params: state,
        },
      )

      organizations.value = res
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

  const getOrganization = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IOrganization>>(
        `/v1/master/organisasi/${id}`,
      )

      organization.value = res.data
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

  const storeOrganization = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IOrganization>>(
        '/v1/master/organisasi',
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

  const updateOrganization = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IOrganization>>(
        `/v1/master/organisasi/${id}`,
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

  const destroyOrganization = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/master/organisasi/${id}`,
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

  const bulkDestroyOrganization = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/master/organisasi/bulk-destroy',
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
    organization,
    organizations,
    getAllOrganizations,
    getOrganizations,
    getOrganization,
    storeOrganization,
    updateOrganization,
    destroyOrganization,
    bulkDestroyOrganization,
    state,
  }
}
