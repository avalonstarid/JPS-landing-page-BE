<template>
  <div class="card card-grid">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          class="btn btn-sm btn-primary"
          :to="{ name: 'user-management-permissions-create' }"
        >
          <i class="ki-filled ki-plus-squared"></i>
          Tambah
        </NuxtLink>
      </div>
      <div v-else class="flex items-center gap-2">
        <span class="font-medium">{{ selectedData.length }} Terpilih</span>

        <button
          v-if="checkScope(['permission_delete'])"
          class="btn btn-sm btn-danger"
          type="button"
          @click="deleteSelectedData"
        >
          <i class="ki-filled ki-trash"></i> Hapus Terpilih
        </button>
      </div>

      <el-input
        v-model="state['filter[search]']"
        class="max-w-[250px]"
        placeholder="Search..."
        clearable
        @input="handleSearch('filter[search]', $event)"
      >
        <template #prefix>
          <i class="leading-none text-gray-500 ki-filled ki-magnifier text-md">
          </i>
        </template>
      </el-input>
    </div>
    <div class="card-body">
      <el-table
        :data="permissions.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="permissions?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column
          prop="name"
          label="Nama Permission"
          min-width="130"
          sortable
        />
        <el-table-column prop="guard_name" label="Nama Guard" min-width="100" />
        <el-table-column fixed="right" width="110">
          <template #default="{ row }">
            <el-dropdown
              v-if="checkScope(['permission_delete', 'permission_update'])"
              trigger="click"
            >
              <button class="btn btn-sm btn-outline btn-primary">
                Opsi <i class="ki-filled ki-down"></i>
              </button>
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item
                    v-if="checkScope(['permission_update'])"
                    @click="
                      $router.push(`/user-management/permissions/${row.id}`)
                    "
                  >
                    <i class="ki-filled ki-notepad-edit"></i> Ubah
                  </el-dropdown-item>
                  <el-dropdown-item
                    v-if="checkScope(['permission_delete'])"
                    @click="deleteData(row.id)"
                  >
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
        :total="permissions.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import usePermissions from '@/composables/user-management/permissions'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Permissions',
  breadcrumbs: [{ title: 'User Management' }],
  authorize: ['permission_read'],
})

const route = useRoute()
const {
  permissions,
  getPermissions,
  destroyPermission,
  bulkDestroyPermission,
  loading,
  state,
} = usePermissions()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyPermission(id)
      await getPermissions()
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
      await bulkDestroyPermission({ data: selectedData.value })
      await getPermissions()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  getPermissions()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)
    getPermissions()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
