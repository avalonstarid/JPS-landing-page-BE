<template>
  <div class="card-grid card">
    <div class="card-header">
      <h3 class="card-title">Operating Systems</h3>
    </div>

    <div class="card-body">
      <el-table :data="topOperatingSystems?.data" v-loading="loading">
        <el-table-column prop="operatingSystem" label="Operating System" />
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

const { topOperatingSystems, getTopOperatingSystems, state, loading } =
  useDashboards()

onMounted(() => {
  state.end_date = props.filter.tanggal[1]
  state.start_date = props.filter.tanggal[0]

  getTopOperatingSystems()
})

watch(
  () => props.filter,
  () => {
    state.end_date = props.filter.tanggal[1]
    state.start_date = props.filter.tanggal[0]

    getTopOperatingSystems()
  },
  {
    deep: true,
  },
)
</script>
