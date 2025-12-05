export default function useAuditLogs() {
  const auditLog = ref<any>({})
  const auditLogs = ref<any>({})

  const errors = ref<any>({})
  const loading = ref<boolean>(false)
  const router = useRouter()
  const sanctumFetch = useSanctumClient()

  const state = reactive({
    defaultSort: { prop: 'created_at', order: 'descending' } as any,
    page: 1,
    rows: 10,
    sort: '-created_at' as string | null,
  })

  const getAllAuditLogs = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/settings/system/audit-logs', {
        params: { all: 1, ...state },
      })

      auditLogs.value = res
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

  const getAuditLogs = async () => {
    loading.value = true

    try {
      let res = await sanctumFetch('/settings/system/audit-logs', {
        params: state,
      })

      auditLogs.value = res
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

  const getAuditLog = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/settings/system/audit-logs/${id}`)

      auditLog.value = res.data
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

  const destroyAuditLog = async (id: string) => {
    loading.value = true

    try {
      let res = await sanctumFetch(`/settings/system/audit-logs/${id}`, {
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

  const bulkDestroyAuditLog = async (data: object) => {
    loading.value = true

    try {
      let res = await sanctumFetch('/settings/system/audit-logs/bulk-destroy', {
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
    auditLog,
    auditLogs,
    getAllAuditLogs,
    getAuditLogs,
    getAuditLog,
    destroyAuditLog,
    bulkDestroyAuditLog,
    state,
  }
}
