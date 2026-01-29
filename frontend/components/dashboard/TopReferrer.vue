<template>
  <div class="card-grid card">
    <div class="card-header">
      <h3 class="card-title">Referrers</h3>
    </div>

    <div class="card-body">
      <el-table :data="topReferrers?.data" v-loading="loading">
        <el-table-column prop="pageReferrer" label="Referrer">
          <template #default="{ row }">
            <el-link :href="withProtocol(row.pageReferrer)" target="_blank">
              {{ row.pageReferrer }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column prop="screenPageViews" label="Views" width="100" />
      </el-table>
    </div>
  </div>
</template>

<script setup lang="ts">
import useDashboards from '@/composables/dashboards'

const props = defineProps({
  filter: {
    type: Object,
    required: true,
  },
})

const { topReferrers, getTopReferrers, state, loading } = useDashboards()

const withProtocol = (url: string) => {
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }

  return `https://${url}`
}

onMounted(() => {
  state.end_date = props.filter.tanggal[1]
  state.start_date = props.filter.tanggal[0]

  getTopReferrers()
})

watch(
  () => props.filter,
  () => {
    state.end_date = props.filter.tanggal[1]
    state.start_date = props.filter.tanggal[0]

    getTopReferrers()
  },
  {
    deep: true,
  },
)
</script>
