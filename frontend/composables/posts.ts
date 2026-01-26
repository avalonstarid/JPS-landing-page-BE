import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IPost {
  id: string
  content: IMultiLang
  headline: IMultiLang
  created_at: string
  featured: File | string
  featured_remove: number
  is_published: boolean
  likes: number
  published_at: string
  slug: string
  temp_media_ids: string[]
  title: IMultiLang
  type: string
  updated_at: string
  views: number
}

export default function usePosts() {
  const post = ref<Partial<IPost>>({
    content: {
      en: '',
      id: '',
    },
    headline: {
      en: '',
      id: '',
    },
    is_published: false,
    title: {
      en: '',
      id: '',
    },
    temp_media_ids: [],
  })
  const posts = ref<ApiResponse<IPost[]>>()

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

  const getAllPosts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost[]>>('/v1/posts', {
        params: { all: 1, ...state },
      })

      posts.value = res
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

  const getPosts = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost[]>>('/v1/posts', {
        params: state,
      })

      posts.value = res
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

  const getPost = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost>>(`/v1/posts/${id}`)

      post.value = res.data
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

  const storePost = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost>>('/v1/posts', {
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

  const updatePost = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost>>(`/v1/posts/${id}`, {
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

  const destroyPost = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(`/v1/posts/${id}`, {
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

  const bulkDestroyPost = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/posts/bulk-destroy',
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

  const getPostKeberlanjutanTinjauan = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost>>(
        `/v1/keberlanjutan/tinjauan`,
      )

      if (res.data) {
        post.value = res.data
      }
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

  const storePostKeberlanjutanTinjauan = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IPost>>(
        '/v1/keberlanjutan/tinjauan',
        {
          method: 'POST',
          body: data,
        },
      )

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

  return {
    errors,
    loading,
    post,
    posts,
    getAllPosts,
    getPosts,
    getPost,
    storePost,
    updatePost,
    destroyPost,
    bulkDestroyPost,
    getPostKeberlanjutanTinjauan,
    storePostKeberlanjutanTinjauan,
    state,
  }
}
