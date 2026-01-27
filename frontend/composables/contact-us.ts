import type { ApiResponse, IState } from '@/helpers/interfaces'

export interface IContact {
  id: string
  created_at: string
  email: string
  location: string
  message: string
  name: string
  phone: string
  updated_at: string
}

export default function useContacts() {
  const contact = ref<Partial<IContact>>({})
  const contacts = ref<ApiResponse<IContact[]>>()

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

  const getAllContacts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IContact[]>>('/v1/contact-us', {
        params: { all: 1, ...state },
      })

      contacts.value = res
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

  const getContacts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IContact[]>>('/v1/contact-us', {
        params: state,
      })

      contacts.value = res
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

  const getContact = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IContact>>(
        `/v1/contact-us/${id}`,
      )

      contact.value = res.data
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

  const storeContact = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IContact>>('/v1/contact-us', {
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

  const updateContact = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IContact>>(
        `/v1/contact-us/${id}`,
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

  const destroyContact = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(`/v1/contact-us/${id}`, {
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

  const bulkDestroyContact = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/contact-us/bulk-destroy',
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
    contact,
    contacts,
    getAllContacts,
    getContacts,
    getContact,
    storeContact,
    updateContact,
    destroyContact,
    bulkDestroyContact,
    state,
  }
}
