import type { ApiResponse } from '@/helpers/interfaces'

export interface IMedia {
  image: File | string
  media_id: string
  url: string
}

export default function useMedias() {
  const media = ref<Partial<IMedia>>({})
  const medias = ref<ApiResponse<IMedia[]>>()

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const sanctumFetch = useSanctumClient()

  const storeMedia = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IMedia>>('/v1/media', {
        method: 'POST',
        body: data,
      })

      ElNotification({
        title: 'Success',
        message: res.message,
        type: 'success',
      })

      return res
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

  const destroyMedia = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(`/v1/media/${id}`, {
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

  return {
    errors,
    loading,
    media,
    medias,
    storeMedia,
    destroyMedia,
  }
}
