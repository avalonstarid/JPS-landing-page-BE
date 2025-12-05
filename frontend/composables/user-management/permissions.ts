export default function usePermissions() {
  const permission = ref<any>({})
  const permissions = ref<any>({
    data: [],
  })

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const router = useRouter()
  const sanctumFetch = useSanctumClient()

  const state = reactive({
    defaultSort: undefined,
    page: 1,
    rows: 10,
    sort: null as string | null,
  })

  const getAllPermissions = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/permissions', {
        params: { all: 1, ...state },
      })

      permissions.value = res
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

  const getPermissions = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/permissions', {
        params: state,
      })

      permissions.value = res
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

  const getPermission = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/user-management/permissions/${id}`)

      permission.value = res.data
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

  const storePermission = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/user-management/permissions', {
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

  const updatePermission = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch(`/user-management/permissions/${id}`, {
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

  const destroyPermission = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/user-management/permissions/${id}`, {
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

  const bulkDestroyPermission = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch(
        '/user-management/permissions/bulk-destroy',
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
    permission,
    permissions,
    getAllPermissions,
    getPermissions,
    getPermission,
    storePermission,
    updatePermission,
    destroyPermission,
    bulkDestroyPermission,
    state,
  }
}
