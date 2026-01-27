<template>
  <div class="card-grid card">
    <div class="flex-wrap gap-2 py-5 card-header">
      <div v-if="selectedData.length === 0">
        <!-- <NuxtLink
          v-if="checkScope([`${basePermission}_create`])"
          class="btn btn-sm btn-primary"
          :to="{ name: 'faq-create' }"
        >
          <i class="ki-filled ki-plus-squared"></i>
          Tambah
        </NuxtLink> -->
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
        :data="contacts?.data"
        :default-sort="state.defaultSort"
        v-loading="loading"
        size="small"
        header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
        row-class-name="fw-semibold"
        @sort-change="handleSortChange(state, $event)"
        @selection-change="handleSelectionChange"
      >
        <el-table-column
          :index="contacts?.from"
          align="right"
          class-name="text-nowrap"
          type="index"
          width="60"
        />
        <el-table-column type="selection" width="30" />
        <el-table-column prop="name" label="Nama" />
        <el-table-column prop="email" label="Email" />
        <el-table-column prop="phone" label="No. Telp" />
        <el-table-column prop="location" label="Lokasi" />
        <el-table-column prop="message" label="Pesan" />
        <el-table-column prop="created_at" label="Tanggal" sortable>
          <template #default="{ row }">
            {{ $dayjs(row.created_at).format('DD MMM YYYY HH:mm:ss') }}
          </template>
        </el-table-column>
        <!-- <el-table-column fixed="right" width="110">
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
                    @click="$router.push(`/contact-us/${row.id}`)"
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
        </el-table-column> -->
      </el-table>
    </div>
    <div class="card-footer">
      <el-pagination
        v-model:currentPage="state.page"
        v-model:page-size="state.rows"
        class="flex flex-wrap w-full"
        :disabled="loading"
        :total="contacts?.total ?? 0"
        layout="sizes, ->, total, prev, pager, next"
        @size-change="handleEvTable('rows', $event)"
        @current-change="handleEvTable('page', $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import useContacts from '@/composables/contact-us'
import { checkScope } from '@/helpers/checkScope'
import {
  handleEvTable,
  handleSearch,
  handleSortChange,
  syncStateFilter,
} from '@/helpers/evTable'

definePageMeta({
  title: 'Contact Us',
  breadcrumbs: [],
  authorize: ['contact_us_read'],
})
const basePermission = 'contact_us'

const route = useRoute()
const {
  contacts,
  getContacts,
  destroyContact,
  bulkDestroyContact,
  loading,
  state,
} = useContacts()
const selectedData = ref([])

const deleteData = (id) => {
  ElMessageBox.confirm('Do you want to delete this record?', 'Warning', {
    closeOnClickModal: false,
    type: 'warning',
  })
    .then(async () => {
      await destroyContact(id)
      await getContacts()
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
      await bulkDestroyContact({ data: selectedData.value })
      await getContacts()
    })
    .catch(() => {})
}

onMounted(() => {
  syncStateFilter(route, state)

  getContacts()
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, state)

    getContacts()
  },
)
const handleSelectionChange = (val: any) => {
  selectedData.value = val
}
</script>
