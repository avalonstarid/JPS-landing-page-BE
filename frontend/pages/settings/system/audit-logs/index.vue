<template>
  <div class="card card-grid">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length > 0" class="flex items-center gap-2">
        <span class="font-medium">{{ selectedData.length }} Terpilih</span>

        <button
          class="btn btn-sm btn-danger"
          type="button"
          @click="deleteSelectedData"
        >
          <i class="ki-filled ki-trash"></i> Hapus Terpilih
        </button>
      </div>
    </div>
    <div class="card-body">
      <el-table
        :data="auditLogs.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="auditLogs?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column prop="created_at" label="Tanggal" sortable>
          <template #default="{ row }">
            {{ $dayjs(row.created_at).format('YYYY-MM-DD HH:mm:ss') }}
          </template>
        </el-table-column>
        <el-table-column prop="log_name" label="Nama Log" />
        <el-table-column prop="description" label="Deskripsi" />
        <el-table-column prop="subject_type" label="Tipe Subjek" />
        <el-table-column prop="causer" label="Oleh">
          <template #default="{ row }">
            {{ row.causer ? row.causer.name : 'System' }}
          </template>
        </el-table-column>
        <el-table-column fixed="right" width="110">
          <template #default="{ row }">
            <el-dropdown trigger="click">
              <button class="btn btn-sm btn-outline btn-primary">
                Opsi <i class="ki-filled ki-down"></i>
              </button>
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item
                    @click="
                      $router.push(`/settings/system/audit-logs/${row.id}`)
                    "
                  >
                    <i class="ki-filled ki-eye"></i> Detail
                  </el-dropdown-item>
                  <el-dropdown-item @click="deleteData(row.id)">
                    <i class="ki-filled ki-trash"></i> Hapus
                  </el-dropdown-item>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="card-footer">
      <el-pagination
        v-model:currentPage="state.page"
        v-model:page-size="state.rows"
        class="flex flex-wrap w-full"
        :disabled="loading"
        :total="auditLogs.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useAuditLogs from '@/composables/settings/system/audit-logs'
import {
  handleEvTable,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Audit Logs',
  breadcrumbs: [{ title: 'Settings' }],
  authorize: ['audit_log_read'],
})

const route = useRoute()
const {
  auditLogs,
  getAuditLogs,
  destroyAuditLog,
  bulkDestroyAuditLog,
  loading,
  state,
} = useAuditLogs()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyAuditLog(id)
      await getAuditLogs()
    })
    .catch(() => {})
}

const deleteSelectedData = () => {
  ElMessageBox.confirm(
    'Do you want to delete the selected record?',
    'Warning',
    {
      closeOnClickModal: false,
      type: 'warning',
    },
  )
    .then(async () => {
      await bulkDestroyAuditLog({ data: selectedData.value })
      await getAuditLogs()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  getAuditLogs()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)
    getAuditLogs()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
