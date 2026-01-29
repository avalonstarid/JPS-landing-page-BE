<template>
  <div class="gap-4 grid grid-cols-3">
    <div class="col-span-3 text-end">
      <el-date-picker
        v-model="filter.tanggal"
        :clearable="false"
        end-placeholder="End date"
        format="YYYY-MM-DD"
        range-separator="To"
        start-placeholder="Start date"
        type="daterange"
        value-format="YYYY-MM-DD"
      />
    </div>

    <DashboardTotalVisitor class="col-span-3" :filter="filter" />

    <DashboardMostVisited :filter="filter" />

    <DashboardTopReferrer :filter="filter" />

    <DashboardTopBrowser :filter="filter" />

    <DashboardTopCountry :filter="filter" />

    <DashboardTopOperatingSystem :filter="filter" />
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  title: 'Dashboard',
})

const dayjs = useDayjs()

const filter = ref<any>({
  tanggal: [
    dayjs().startOf('month').format('YYYY-MM-DD'),
    dayjs().endOf('month').format('YYYY-MM-DD'),
  ],
})
</script>
