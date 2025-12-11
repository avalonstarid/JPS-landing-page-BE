export interface IUser {
  avatar: string | null
  email: string
  id: number
  name: string
  scope: string[]
}

export default function useUsers() {
  const user = ref<any>({
    active: true,
  })
  const users = ref<any>({
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

  const getAllUsers = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/user-management/users', {
        params: { all: 1, ...state },
      })

      users.value = res
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

  const getUsers = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/user-management/users', {
        params: state,
      })

      users.value = res
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

  const getUser = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/user-management/users/${id}`)

      user.value = res.data
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

  const storeUser = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/user-management/users', {
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

  const updateUser = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/user-management/users/${id}`, {
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

  const destroyUser = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/user-management/users/${id}`, {
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

  const bulkDestroyUser = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/user-management/users/bulk-destroy', {
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

  const exportUser = async (type: string) => {
    try {
      let res = await sanctumFetch(`/v1/user-management/users/export`, {
        params: { all: 1, ...state, type: type },
      })

      return res
    } catch (e: any) {
      ElNotification({
        title: 'Error',
        message: e.response._data.message ?? e.message,
        type: 'error',
      })
    }
  }

  const updateProfile = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/auth/update-profile', {
        method: 'PUT',
        body: data,
      })

      //   await useAuthStore().verifyAuth()

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

  const updatePassword = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/auth/update-password', {
        method: 'PUT',
        body: data,
      })

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

  const checkTokenReset = async (data: object) => {
    try {
      let res = await sanctumFetch('/v1/auth/forgot-password/verify', {
        method: 'POST',
        body: data,
      })
    } catch (e: any) {
      throw createError({
        statusCode: 404,
        statusMessage: 'Page Not Found.',
        fatal: true,
      })
    }
  }

  const checkVerifyEmail = async (query: any) => {
    try {
      let res = await sanctumFetch(
        `/v1/auth/verify-email/${query.id}/${query.hash}`,
        {
          params: {
            expires: query.expires,
            signature: query.signature,
          },
        },
      )

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })
    } catch (e: any) {
      throw createError({
        statusCode: 404,
        statusMessage: 'Page Not Found.',
        fatal: true,
      })
    }
  }

  const resetPassword = async (data: object) => {
    loading.value = true
    errors.value = ''

    try {
      let res = await sanctumFetch('/v1/auth/reset-password', {
        method: 'POST',
        body: data,
      })

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })
    } catch (e: any) {
      if (e.response) {
        errors.value = e.response.data
      } else {
        errors.value = e
      }

      ElNotification({
        title: 'Error',
        message: errors.value['message'],
        type: 'error',
        duration: 3000,
      })
    } finally {
      loading.value = false
    }
  }

  return {
    errors,
    loading,
    user,
    users,
    getAllUsers,
    getUsers,
    getUser,
    storeUser,
    updateUser,
    destroyUser,
    bulkDestroyUser,
    exportUser,
    updateProfile,
    updatePassword,
    checkTokenReset,
    resetPassword,
    checkVerifyEmail,
    state,
  }
}
