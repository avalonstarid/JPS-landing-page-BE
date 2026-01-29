<template>
  <div class="card-grid card">
    <div class="card-header">
      <h3 class="card-title">Countries</h3>
    </div>

    <div class="card-body">
      <el-table :data="topCountries?.data" v-loading="loading">
        <el-table-column prop="country" label="Country" />
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

const { topCountries, getTopCountries, state, loading } = useDashboards()

onMounted(() => {
  state.end_date = props.filter.tanggal[1]
  state.start_date = props.filter.tanggal[0]

  getTopCountries()
})

watch(
  () => props.filter,
  () => {
    state.end_date = props.filter.tanggal[1]
    state.start_date = props.filter.tanggal[0]

    getTopCountries()
  },
  {
    deep: true,
  },
)
</script>
