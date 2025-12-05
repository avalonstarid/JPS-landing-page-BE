<template>
  <div class="card card-grid">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          class="btn btn-sm btn-primary"
          :to="{ name: 'user-management-users-create' }"
        >
          <i class="ki-filled ki-plus-squared"></i>
          Tambah
        </NuxtLink>
      </div>
      <div v-else class="flex items-center gap-2">
        <span class="font-medium">{{ selectedData.length }} Terpilih</span>

        <button
          class="btn btn-sm btn-danger"
          type="button"
          @click="deleteSelectedData"
        >
          <i class="ki-filled ki-trash"></i> Hapus Terpilih
        </button>
      </div>

      <div class="flex items-center gap-2">
        <el-dropdown trigger="click" @command="handleExport">
          <button class="btn btn-sm btn-outline btn-primary">
            Export <i class="ki-filled ki-down"></i>
          </button>
          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item command="xlsx">
                <i class="ki-filled ki-file-sheet"></i> Excel
              </el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>

        <el-input
          v-model="state['filter[search]']"
          class="max-w-[250px]"
          placeholder="Search..."
          clearable
          @input="handleSearch('filter[search]', $event)"
        >
          <template #prefix>
            <i
              class="leading-none text-gray-500 ki-filled ki-magnifier text-md"
            >
            </i>
          </template>
        </el-input>
      </div>
    </div>
    <div class="card-body">
      <div class="flex flex-wrap items-end gap-2 p-5">
        <InputSelect
          v-model="filter['filter[active]']"
          class="el-form-item--label-top w-[150px]"
          :filterable="false"
          label="Status"
          name="active"
        >
          <el-option label="Aktif" value="1" />
          <el-option label="Tidak Aktif" value="0" />
        </InputSelect>

        <div class="flex gap-2">
          <button
            class="btn btn-sm btn-outline btn-info"
            :disabled="filter.loading"
            type="button"
            @click="applyFilter"
          >
            <i class="ki-filled ki-filter"></i> Filter
          </button>

          <button
            class="btn btn-sm btn-outline btn-danger"
            :disabled="filter.loading"
            type="button"
            @click="resetFilter"
          >
            <i class="ki-filled ki-filter-edit"></i> Reset
          </button>
        </div>
      </div>

      <el-table
        :data="users.data"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="users?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column width="70">
          <template #header>
            <div class="flex items-center justify-center">
              <i class="ki-filled ki-picture"></i>
            </div>
          </template>
          <template #default="{ row }">
            <el-image
              class="w-[50px] h-[50px]"
              :src="row.avatar"
              :preview-src-list="[row.avatar]"
              fit="cover"
              preview-teleported
            />
          </template>
        </el-table-column>
        <el-table-column prop="name" label="Nama" sortable />
        <el-table-column prop="email" label="Email" sortable />
        <el-table-column prop="active" label="Aktif">
          <template #default="{ row }">
            <span
              v-if="row.active"
              class="badge badge-sm badge-outline badge-success"
            >
              Aktif
            </span>
            <span v-else class="badge badge-sm badge-outline badge-danger">
              Tidak Aktif
            </span>
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
                    @click="$router.push(`/user-management/users/${row.id}`)"
                  >
                    <i class="ki-filled ki-notepad-edit"></i> Ubah
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
        :total="users.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useUsers from '@/composables/user-management/users'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  isSameFilter,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Users',
  breadcrumbs: [{ title: 'User Management' }],
  authorize: ['user_read'],
})

const route = useRoute()
const {
  users,
  getUsers,
  destroyUser,
  bulkDestroyUser,
  exportUser,
  loading,
  state,
} = useUsers()
const selectedData = ref([])
const filter = ref<any>({})

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyUser(id)
      await getUsers()
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
      await bulkDestroyUser({ data: selectedData.value })
      await getUsers()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state, filter)

  getUsers()
})

const handleExport = async (type: string) => {
  const loading = ElLoading.service({
    lock: true,
    text: 'Loading',
  })

  const res = await exportUser(type)

  if (res) {
    navigateTo(res.data.url, {
      open: {
        target: '_blank',
      },
    })
  }
  loading.close()
}

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state, filter)
    getUsers()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}

// Filter
const applyFilter = async () => {
  filter.value.loading = true

  let q = {}
  for (const key in filter.value) {
    if (key == 'loading') continue

    state[key] = filter.value[key]
    q[key] = filter.value[key]
  }

  if (isSameFilter(q, route)) {
    await getUsers()
  } else {
    await handleEvTable(q)
  }

  filter.value.loading = false
}
const resetFilter = async () => {
  let q = {}
  for (const key in filter.value) {
    if (key == 'loading') continue

    filter.value[key] = null
    state[key] = filter.value[key]
    q[key] = filter.value[key]
  }

  filter.value.loading = true

  if (isSameFilter(q, route)) {
    await getUsers()
  } else {
    await handleEvTable(q)
  }

  filter.value.loading = false
}
</script>
