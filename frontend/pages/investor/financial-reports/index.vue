<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'investor-financial-reports-create' }"
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
        :data="financialReports?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="financialReports?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <!-- <el-table-column width="70">
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
        </el-table-column> -->
        <el-table-column prop="tahun" label="Tahun" width="60" />
        <el-table-column prop="name.id" label="Nama (ID)" />
        <el-table-column prop="name.en" label="Nama (EN)" />
        <el-table-column label="Arus Kas Bersih">
          <template #default="{ row }">
            <!-- @vue-ignore -->
            {{ $currency(row.arus_kas_bersih) }}
          </template>
        </el-table-column>
        <el-table-column label="Ekuitas">
          <template #default="{ row }">
            <!-- @vue-ignore -->
            {{ $currency(row.ekuitas) }}
          </template>
        </el-table-column>
        <el-table-column label="Laba Bersih">
          <template #default="{ row }">
            <!-- @vue-ignore -->
            {{ $currency(row.laba_bersih) }}
          </template>
        </el-table-column>
        <el-table-column label="Liabilitas">
          <template #default="{ row }">
            <!-- @vue-ignore -->
            {{ $currency(row.liabilitas) }}
          </template>
        </el-table-column>
        <el-table-column label="Penjualan">
          <template #default="{ row }">
            <!-- @vue-ignore -->
            {{ $currency(row.penjualan) }}
          </template>
        </el-table-column>
        <el-table-column label="Size Dokumen" width="110">
          <template #default="{ row }">
            {{ formatBytes(row.document?.size) }}
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="Dibuat Pada">
          <template #default="{ row }">
            {{ $dayjs(row.created_at).format('DD MMM YYYY HH:mm:ss') }}
          </template>
        </el-table-column>
        <el-table-column label="Download" width="80">
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
                    @click="
                      $router.push(`/investor/financial-reports/${row.id}`)
                    "
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
        :total="financialReports?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useFinancialReports from '@/composables/investor/financial-reports'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'
import { formatBytes } from '@/helpers/helpers'

definePageMeta({
  title: 'Laporan Keuangan',
  breadcrumbs: [{ title: 'Relasi Investor' }],
  authorize: ['document_investor_read'],
})
const basePermission = 'document_investor'

const route = useRoute()
const {
  financialReports,
  getFinancialReports,
  destroyFinancialReport,
  bulkDestroyFinancialReport,
  loading,
  state,
} = useFinancialReports()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyFinancialReport(id)
      await getFinancialReports()
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
      await bulkDestroyFinancialReport({
        data: selectedData.value,
      })
      await getFinancialReports()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  state.include = 'document,featured'
  getFinancialReports()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getFinancialReports()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
