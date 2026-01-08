import type { ApiResponse, IMultiLang, IState } from '@/helpers/interfaces'

export interface IFinancialReport {
  id: string
  arus_kas_bersih: number
  created_at: string
  document: string | File
  ekuitas: number
  featured: string | File
  featured_remove: number
  laba_bersih: number
  liabilitas: number
  name: IMultiLang
  penjualan: number
  tahun: number
  updated_at: string
}

export default function useFinancialReports() {
  const financialReport = ref<Partial<IFinancialReport>>({
    name: {
      en: '',
      id: '',
    },
  })
  const financialReports = ref<ApiResponse<IFinancialReport[]>>()

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

  const getAllFinancialReports = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFinancialReport[]>>(
        '/v1/investor/financial-reports',
        {
          params: { all: 1, ...state },
        },
      )

      financialReports.value = res
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

  const getFinancialReports = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFinancialReport[]>>(
        '/v1/investor/financial-reports',
        {
          params: state,
        },
      )

      financialReports.value = res
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

  const getFinancialReport = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFinancialReport>>(
        `/v1/investor/financial-reports/${id}`,
      )

      financialReport.value = res.data
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

  const storeFinancialReport = async (data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFinancialReport>>(
        '/v1/investor/financial-reports',
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

  const updateFinancialReport = async (id: string, data: object) => {
    errors.value = ''
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IFinancialReport>>(
        `/v1/investor/financial-reports/${id}`,
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

  const destroyFinancialReport = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        `/v1/investor/financial-reports/${id}`,
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

  const bulkDestroyFinancialReport = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<null>>(
        '/v1/investor/financial-reports/bulk-destroy',
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
    financialReport,
    financialReports,
    getAllFinancialReports,
    getFinancialReports,
    getFinancialReport,
    storeFinancialReport,
    updateFinancialReport,
    destroyFinancialReport,
    bulkDestroyFinancialReport,
    state,
  }
}
