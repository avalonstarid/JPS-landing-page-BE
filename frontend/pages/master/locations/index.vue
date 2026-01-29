<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'master-locations-create' }"
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
        :data="locations?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="locations?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column width="70">
          <template #header>
            <div class="flex justify-center items-center">
              <i class="ki-filled ki-picture"></i>
            </div>
          </template>
          <template #default="{ row }">
            <el-image
              v-if="row.media"
              class="w-[50px] h-[50px]"
              :src="row.media[0].thumb_url"
              :preview-src-list="row.media.map((item) => item.original_url)"
              fit="cover"
              preview-teleported
            >
              <template #error>
                <el-image
                  class="w-[50px] h-[50px]"
                  :src="row.media[0].original_url"
                  :preview-src-list="row.media.map((item) => item.original_url)"
                  fit="cover"
                  preview-teleported
                />
              </template>
            </el-image>
          </template>
        </el-table-column>
        <el-table-column prop="business_line.title.id" label="Lini Bisnis" />
        <el-table-column prop="address" label="Alamat" />
        <el-table-column prop="phone" label="No. Telp" />
        <el-table-column label="Titik Lokasi">
          <template #default="{ row }">
            <a
              class="text-primary hover:underline"
              :href="`https://www.google.com/maps/search/?api=1&query=${row.lat},${row.lng}`"
              target="_blank"
            >
              {{ row.lat }}, {{ row.lng }}
            </a>
          </template>
        </el-table-column>
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
                    @click="$router.push(`/master/locations/${row.id}`)"
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
        :total="locations?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useLocations from '@/composables/master/locations'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Lokasi',
  breadcrumbs: [{ title: 'Master' }],
  authorize: ['location_read'],
})
const basePermission = 'location'

const route = useRoute()
const {
  locations,
  getLocations,
  destroyLocation,
  bulkDestroyLocation,
  loading,
  state,
} = useLocations()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyLocation(id)
      await getLocations()
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
      await bulkDestroyLocation({ data: selectedData.value })
      await getLocations()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  state.include = 'businessLine,media'
  getLocations()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getLocations()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
