import type { ApiResponse } from '@/helpers/interfaces'

export interface IMostVisited {
  fullPageUrl: string
  pageTitle: string
  screenPageViews: number
}

export interface ITopBrowser {
  browser: string
  screenPageViews: number
}

export interface ITopCountry {
  country: string
  screenPageViews: number
}

export interface ITopOperatingSystem {
  operatingSystem: string
  screenPageViews: number
}

export interface ITopReferrer {
  pageReferrer: string
  screenPageViews: number
}

export interface ITotalVisitor {
  date: string
  activeUsers: number
  screenPageViews: number
}

export default function useDashboards() {
  const mostVisiteds = ref<ApiResponse<IMostVisited[]>>()
  const topBrowsers = ref<ApiResponse<ITopBrowser[]>>()
  const topCountries = ref<ApiResponse<ITopCountry[]>>()
  const topOperatingSystems = ref<ApiResponse<ITopOperatingSystem[]>>()
  const topReferrers = ref<ApiResponse<ITopReferrer[]>>()
  const totalVisitors = ref<ApiResponse<ITotalVisitor[]>>()

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const sanctumFetch = useSanctumClient()

  const state = reactive({
    start_date: '',
    end_date: '',
  })

  const getMostVisiteds = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<IMostVisited[]>>(
        '/v1/dashboard/most-visited',
        {
          params: state,
        },
      )

      mostVisiteds.value = res
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

  const getTopBrowsers = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITopBrowser[]>>(
        '/v1/dashboard/top-browsers',
        {
          params: state,
        },
      )

      topBrowsers.value = res
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

  const getTopCountries = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITopCountry[]>>(
        '/v1/dashboard/top-countries',
        {
          params: state,
        },
      )

      topCountries.value = res
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

  const getTopOperatingSystems = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITopOperatingSystem[]>>(
        '/v1/dashboard/top-operating-systems',
        {
          params: state,
        },
      )

      topOperatingSystems.value = res
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

  const getTopReferrers = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITopReferrer[]>>(
        '/v1/dashboard/top-referrers',
        {
          params: state,
        },
      )

      topReferrers.value = res
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

  const getTotalVisitors = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch<ApiResponse<ITotalVisitor[]>>(
        '/v1/dashboard/total-visitors',
        {
          params: state,
        },
      )

      totalVisitors.value = res
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

  return {
    errors,
    loading,
    mostVisiteds,
    topBrowsers,
    topCountries,
    topOperatingSystems,
    topReferrers,
    totalVisitors,
    getMostVisiteds,
    getTopBrowsers,
    getTopCountries,
    getTopOperatingSystems,
    getTopReferrers,
    getTotalVisitors,
    state,
  }
}
