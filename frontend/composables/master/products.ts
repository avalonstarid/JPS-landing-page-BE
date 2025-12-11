import type { IState } from '@/helpers/interfaces'

export interface IProduct {
  id: string
  active: boolean
  created_at: string
  featured: string | File
  full_desc: string
  images: (string | File | any)[]
  images_remove?: number[]
  short_desc: string
  slug: string
  sort_order: number
  title: string
  updated_at: string
}

export default function useProducts() {
  const product = ref<Partial<IProduct>>({
    active: true,
  })
  const products = ref<any>({
    data: [] as IProduct[],
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

  const getAllProducts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/products', {
        params: { all: 1, ...state },
      })

      products.value = res
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

  const getProducts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/products', {
        params: state,
      })

      products.value = res
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

  const getProduct = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/products/${id}`)

      product.value = res.data
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

  const storeProduct = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/products', {
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

  const updateProduct = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/products/${id}`, {
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

  const destroyProduct = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/v1/master/products/${id}`, {
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

  const bulkDestroyProduct = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch('/v1/master/products/bulk-destroy', {
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
    product,
    products,
    getAllProducts,
    getProducts,
    getProduct,
    storeProduct,
    updateProduct,
    destroyProduct,
    bulkDestroyProduct,
    state,
  }
}
