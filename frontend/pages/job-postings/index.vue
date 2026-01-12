<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'job-postings-create' }"
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
        :data="jobPostings?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="jobPostings?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column prop="category.name.id" label="Kategori" />
        <el-table-column prop="title.id" label="Judul (ID)" />
        <el-table-column prop="title.en" label="Judul (EN)" />
        <el-table-column prop="location" label="Lokasi" />
        <el-table-column prop="address" label="Alamat" />
        <el-table-column
          prop="published_at"
          label="Dipublikasi"
          sortable
          width="170"
        />
        <el-table-column
          prop="closed_at"
          label="Ditutup"
          sortable
          width="170"
        />
        <el-table-column prop="pelamar_count" label="Pelamar" width="80" />
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
                    @click="$router.push(`/job-postings/${row.id}`)"
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
        :total="jobPostings?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useJobPostings from '@/composables/job-postings'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Lowongan Pekerjaan',
  breadcrumbs: [],
  authorize: ['job_posting_read'],
})
const basePermission = 'job_posting'

const route = useRoute()
const {
  jobPostings,
  getJobPostings,
  destroyJobPosting,
  bulkDestroyJobPosting,
  loading,
  state,
} = useJobPostings()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyJobPosting(id)
      await getJobPostings()
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
      await bulkDestroyJobPosting({ data: selectedData.value })
      await getJobPostings()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  state.include = 'category,pelamarCount'
  getJobPostings()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getJobPostings()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
