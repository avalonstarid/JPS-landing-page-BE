<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'investor-rups-create' }"
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
        :data="docInvestors?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="docInvestors?.from"
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
              class="w-[50px] h-[50px]"
              :src="row.featured?.thumb_url"
              :preview-src-list="[row.featured?.original_url]"
              fit="cover"
              preview-teleported
            />
          </template>
        </el-table-column>
        <el-table-column prop="title.id" label="Judul (ID)" />
        <el-table-column prop="title.en" label="Judul (EN)" />
        <el-table-column label="Size Dokumen">
          <template #default="{ row }">
            {{ formatBytes(row.document?.size) }}
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="Dibuat Pada">
          <template #default="{ row }">
            {{ $dayjs(row.created_at).format('DD MMM YYYY HH:mm:ss') }}
          </template>
        </el-table-column>
        <el-table-column label="Download">
          <template #default="{ row }">
            <a
              class="btn-outline btn btn-sm btn-primary"
              :href="row.document?.original_url"
              target="_blank"
            >
              <i class="ki-filled ki-cloud-download"></i>
            </a>
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
                    @click="$router.push(`/investor/rups/${row.id}`)"
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
        :total="docInvestors?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useDocInvestors from '@/composables/investor/documents'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'
import { formatBytes } from '@/helpers/helpers'

definePageMeta({
  title: 'RUPS',
  breadcrumbs: [{ title: 'Relasi Investor' }],
  authorize: ['document_investor_read'],
})
const basePermission = 'document_investor'
const categoryInvestor = 'rups'

const route = useRoute()
const {
  docInvestors,
  getDocInvestors,
  destroyDocInvestor,
  bulkDestroyDocInvestor,
  loading,
  state,
} = useDocInvestors()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyDocInvestor(categoryInvestor, id)
      await getDocInvestors(categoryInvestor)
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
      await bulkDestroyDocInvestor(categoryInvestor, {
        data: selectedData.value,
      })
      await getDocInvestors(categoryInvestor)
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  state.include = 'document,featured'
  getDocInvestors(categoryInvestor)
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getDocInvestors(categoryInvestor)
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
