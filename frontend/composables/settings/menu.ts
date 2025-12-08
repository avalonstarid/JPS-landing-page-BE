export interface IMenu {
  children?: IMenu[]
  icon?: string
  newTab?: boolean
  permissions?: string[]
  roles?: string[]
  title: string
  to?: string
}

export default function useMenus() {
  const menu = ref<any>({ order: 1, active: true })
  const menus = ref<any>({
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

  const getAllMenus = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/settings/menu', {
        params: { all: 1, ...state },
      })

      menus.value = res
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

  const getMenus = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/settings/menu', {
        params: state,
      })

      menus.value = res
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

  const getMenu = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/settings/menu/${id}`)

      menu.value = res.data
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

  const getSideMenus = async () => {
    const menuStore = useMenuStore()

    menuStore.setLoading(true)

    try {
      let res = await sanctumFetch('/v1/settings/menu/get-menu')

      menuStore.setMenu(res.data)
    } catch (e: any) {
      errors.value = e
    } finally {
      menuStore.setLoading(false)
    }
  }

  const storeMenu = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/settings/menu', {
        method: 'POST',
        body: data,
      })

      router.back()
      getSideMenus()

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

  const updateMenu = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/settings/menu/${id}`, {
        method: 'PUT',
        body: data,
      })

      router.back()
      getSideMenus()

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

  const destroyMenu = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/settings/menu/${id}`, {
        method: 'DELETE',
      })

      getSideMenus()

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

  const bulkDestroyMenu = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/settings/menu/bulk-destroy', {
        method: 'DELETE',
        body: data,
      })

      getSideMenus()

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
    menu,
    menus,
    getAllMenus,
    getMenus,
    getMenu,
    getSideMenus,
    storeMenu,
    updateMenu,
    destroyMenu,
    bulkDestroyMenu,
    state,
  }
}
