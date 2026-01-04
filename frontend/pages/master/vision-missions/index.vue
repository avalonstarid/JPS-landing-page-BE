<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'master-vision-missions-create' }"
        >
          <i class="ki-filled ki-plus-squared"></i>
          Tambah
        </NuxtLink>
      </div>
      <div v-else class="flex items-center gap-2">
        <span class="font-medium">{{ selectedData.length }} Terpilih</span>

        <button
          v-if="checkScope([`${basePermission}_delete`])"
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
        :data="visionMissions?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="visionMissions?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column prop="active" label="Aktif" width="100">
          <template #default="{ row }">
            <span
              class="badge-outline badge badge-sm"
              :class="row.active ? 'badge-success' : 'badge-danger'"
            >
              {{ row.active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
          </template>
        </el-table-column>
        <el-table-column prop="desc.id" label="Deskripsi (ID)" />
        <el-table-column prop="desc.en" label="Deskripsi (EN)" />
        <el-table-column prop="icon" label="Ikon" />
        <el-table-column prop="sort_order" label="Urutan" width="70" />
        <el-table-column fixed="right" width="110">
          <template #default="{ row }">
            <el-dropdown
              v-if="
                checkScope([
                  `${basePermission}_delete`,
                  `${basePermission}_update`,
                ])
              "
              trigger="click"
            >
              <button class="btn-outline btn btn-sm btn-primary">
                Opsi <i class="ki-filled ki-down"></i>
              </button>
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item
                    v-if="checkScope([`${basePermission}_update`])"
                    @click="$router.push(`/master/vision-missions/${row.id}`)"
                  >
                    <i class="ki-filled ki-notepad-edit"></i> Ubah
                  </el-dropdown-item>
                  <el-dropdown-item
                    v-if="checkScope([`${basePermission}_delete`])"
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
        :total="visionMissions?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useVisionMissions from '@/composables/master/vision-missions'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Visi & Misi',
  breadcrumbs: [{ title: 'Master' }],
  authorize: ['vision_mission_read'],
})
const basePermission = 'vision_mission'

const route = useRoute()
const {
  visionMissions,
  getVisionMissions,
  destroyVisionMission,
  bulkDestroyVisionMission,
  loading,
  state,
} = useVisionMissions()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyVisionMission(id)
      await getVisionMissions()
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
      await bulkDestroyVisionMission({ data: selectedData.value })
      await getVisionMissions()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  getVisionMissions()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getVisionMissions()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
