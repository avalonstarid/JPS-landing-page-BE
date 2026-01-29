<template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Traffic Overview</h3>
    </div>

    <div class="card-body">
      <VueApexCharts
        v-if="!loading && totalVisitors?.data"
        :options="chartOptions"
        :series="series"
        type="area"
        height="350"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { ITotalVisitor } from '@/composables/dashboards'
import useDashboards from '@/composables/dashboards'

const props = defineProps({
  filter: {
    type: Object,
    required: true,
  },
})

const { totalVisitors, getTotalVisitors, state, loading } = useDashboards()
const dayjs = useDayjs()

onMounted(() => {
  state.end_date = props.filter.tanggal[1]
  state.start_date = props.filter.tanggal[0]

  getTotalVisitors()
})

watch(
  () => props.filter,
  () => {
    state.end_date = props.filter.tanggal[1]
    state.start_date = props.filter.tanggal[0]

    getTotalVisitors()
  },
  {
    deep: true,
  },
)

const series = computed(() => {
  if (!totalVisitors.value?.data) return []

  const data = [...totalVisitors.value.data].reverse()

  return [
    {
      name: 'Active Users',
      data: data.map((item: ITotalVisitor) => item.activeUsers),
    },
    {
      name: 'Page Views',
      data: data.map((item: ITotalVisitor) => item.screenPageViews),
    },
  ]
})

const chartOptions = computed(() => {
  const dates =
    [...(totalVisitors.value?.data || [])]
      .reverse()
      .map((item: ITotalVisitor) => dayjs(item.date).format('DD MMM YYYY')) ||
    []

  return {
    chart: {
      type: 'area',
      height: 350,
      fontFamily: 'inherit',
      toolbar: {
        show: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: 'smooth',
      width: 2,
    },
    xaxis: {
      categories: dates,
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      labels: {
        style: {
          colors: 'var(--tw-gray-500)',
          fontSize: '12px',
        },
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: 'var(--tw-gray-500)',
          fontSize: '12px',
        },
      },
    },
    grid: {
      borderColor: 'var(--tw-gray-200)',
      strokeDashArray: 4,
      xaxis: {
        lines: {
          show: true,
        },
      },
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
        left: 0,
      },
    },
    colors: ['var(--tw-primary)', 'var(--tw-info)'],
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.05,
        stops: [0, 90, 100],
      },
    },
    tooltip: {
      theme: 'light',
    },
  }
})
</script>
