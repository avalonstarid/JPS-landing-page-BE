<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          class="btn btn-sm btn-primary"
          :to="{ name: 'settings-menu-create' }"
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

      <el-input
        v-model="state['filter[search]']"
        class="max-w-[250px]"
        placeholder="Search..."
        clearable
        @input="handleSearch('filter[search]', $event)"
      >
        <template #prefix>
          <i class="text-gray-500 text-md leading-none ki-filled ki-magnifier">
          </i>
        </template>
      </el-input>
    </div>
    <div class="card-body">
      <el-table
        :data="menus.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="menus?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column prop="parent.title" label="Parent" />
        <el-table-column prop="title" label="Judul" sortable />
        <el-table-column prop="icon" label="Ikon">
          <template #default="{ row }">
            <i v-if="row.icon" :class="`ki-filled text-lg ki-${row.icon}`"></i>
            <span v-if="row.icon" class="ms-2"> ({{ row.icon }}) </span>
          </template>
        </el-table-column>
        <el-table-column prop="active" label="Aktif">
          <template #default="{ row }">
            <span
              v-if="row.active"
              class="badge-outline badge badge-sm badge-success"
            >
              Aktif
            </span>
            <span v-else class="badge-outline badge badge-sm badge-danger">
              Tidak Aktif
            </span>
          </template>
        </el-table-column>
        <el-table-column prop="order" label="Urutan" sortable />
        <el-table-column fixed="right" width="110">
          <template #default="{ row }">
            <el-dropdown trigger="click">
              <button class="btn-outline btn btn-sm btn-primary">
                Opsi <i class="ki-filled ki-down"></i>
              </button>
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item
                    @click="$router.push(`/settings/menu/${row.id}`)"
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
        :total="menus.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useMenus from '@/composables/settings/menu'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Menu Management',
  breadcrumbs: [{ title: 'Settings' }],
  authorize: ['menu_read'],
})

const route = useRoute()
const { menus, getMenus, destroyMenu, bulkDestroyMenu, loading, state } =
  useMenus()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyMenu(id)
      await getMenus()
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
      await bulkDestroyMenu({ data: selectedData.value })
      await getMenus()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  getMenus()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)
    getMenus()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
