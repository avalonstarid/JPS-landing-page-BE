import type { IMultiLang, IState } from '@/helpers/interfaces'

export interface ILocation {
  id: string
  active: boolean
  address: string
  business_line_id: string
  created_at: string
  desc: IMultiLang
  images: (string | File | any)[]
  images_remove?: string[]
  lat: string
  lng: string
  phone: string
  updated_at: string
}

export default function useLocations() {
  const location = ref<Partial<ILocation>>({
    active: true,
    desc: {
      en: '',
      id: '',
    },
  })
  const locations = ref<any>({
    data: [] as ILocation[],
  })

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

  const getAllLocations = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/locations', {
        params: { all: 1, ...state },
      })

      locations.value = res
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

  const getLocations = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/locations', {
        params: state,
      })

      locations.value = res
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

  const getLocation = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/locations/${id}`)

      location.value = res.data
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

  const storeLocation = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/locations', {
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

  const updateLocation = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/locations/${id}`, {
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

  const destroyLocation = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/locations/${id}`, {
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

  const bulkDestroyLocation = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/locations/bulk-destroy', {
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
    location,
    locations,
    getAllLocations,
    getLocations,
    getLocation,
    storeLocation,
    updateLocation,
    destroyLocation,
    bulkDestroyLocation,
    state,
  }
}
