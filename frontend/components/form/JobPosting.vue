<template>
  <div class="flex flex-col gap-4">
    <el-form
      ref="formRef"
      class="card"
      :model="jobPosting"
      :rules="rules"
      label-position="top"
      require-asterisk-position="right"
      scroll-to-error
      status-icon
      @submit.native.prevent="onSubmit(formRef)"
    >
      <div class="card-group gap-4 grid grid-cols-4">
        <InputSelect
          v-model="jobPosting.category_id"
          :errors="errors"
          label="Kategori"
          name="category_id"
        >
          <el-option
            v-for="item in useCategory.categories?.data"
            :key="item.id"
            :label="item.name.id"
            :value="item.id"
          />
        </InputSelect>

        <InputBase
          v-model="jobPosting.location"
          :errors="errors"
          label="Lokasi"
          name="location"
        />

        <InputBase
          v-model="jobPosting.address"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Alamat"
          name="address"
          type="textarea"
        />

        <InputDate
          v-model="jobPosting.closed_at"
          :errors="errors"
          format="YYYY-MM-DD HH:mm"
          label="Tanggal Tutup"
          name="closed_at"
          type="datetime"
          value-format="YYYY-MM-DD HH:mm"
        />
      </div>

      <div class="card-group gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="jobPosting.title"
            v-model="jobPosting.title.id"
            :errors="errors"
            label="Judul (ID)"
            name="title.id"
          />

          <InputBase
            v-if="jobPosting.desc_short"
            v-model="jobPosting.desc_short.id"
            :errors="errors"
            :autosize="{ minRows: 2 }"
            label="Deskripsi Singkat (ID)"
            name="desc_short.id"
            type="textarea"
          />

          <InputBase
            v-if="jobPosting.desc"
            v-model="jobPosting.desc.id"
            :errors="errors"
            :autosize="{ minRows: 2 }"
            label="Deskripsi (ID)"
            name="desc.id"
            type="textarea"
          />
        </div>

        <div
          class="relative flex flex-col gap-4 p-4 border border-gray-200 rounded-lg"
        >
          <div class="flex justify-between items-center">
            <div class="font-bold text-gray-700">English</div>
            <button
              class="btn btn-sm btn-light-primary"
              type="button"
              title="Copy from Indonesia"
              @click="copyFromId"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="jobPosting.title"
            v-model="jobPosting.title.en"
            :errors="errors"
            label="Judul (EN)"
            name="title.en"
          />

          <InputBase
            v-if="jobPosting.desc_short"
            v-model="jobPosting.desc_short.en"
            :errors="errors"
            :autosize="{ minRows: 2 }"
            label="Deskripsi Singkat (EN)"
            name="desc_short.en"
            type="textarea"
          />

          <InputBase
            v-if="jobPosting.desc"
            v-model="jobPosting.desc.en"
            :errors="errors"
            :autosize="{ minRows: 2 }"
            label="Deskripsi (EN)"
            name="desc.en"
            type="textarea"
          />
        </div>
      </div>

      <div class="justify-start gap-3 card-footer">
        <button
          class="btn btn-sm btn-secondary"
          type="button"
          @click="$router.push('/job-postings')"
        >
          <i class="ki-left ki-filled"></i> Kembali
        </button>
        <BtnIndicator class="btn-sm" :loading="loading">
          <i class="ki-arrow-circle-right ki-filled"></i> Submit
        </BtnIndicator>
      </div>
    </el-form>

    <div class="card-grid card">
      <div class="card-header">
        <div class="card-title">Detail Pelamar</div>
      </div>
      <div class="card-body">
        <el-table
          :data="useJobApplicantion.jobApplications?.data"
          v-loading="useJobApplicantion.loading"
          size="small"
          header-row-class-name="fw-bold fs-7 text-uppercase gs-0"
          row-class-name="fw-semibold"
        >
          <el-table-column
            :index="useJobApplicantion.jobApplications?.from"
            align="right"
            class-name="text-nowrap"
            type="index"
            width="60"
          />
          <el-table-column prop="created_at" label="Tanggal">
            <template #default="{ row }">
              {{ $dayjs(row.created_at).format('DD MMM YYYY HH:mm:ss') }}
            </template>
          </el-table-column>
          <el-table-column prop="name" label="Nama" />
          <el-table-column prop="email" label="Email" />
          <el-table-column prop="phone" label="No. Telp" />
          <el-table-column prop="school_name" label="Asal Sekolah" />
          <el-table-column prop="jurusan" label="Jurusan" />
          <el-table-column prop="age" label="Umur" width="60" />
          <el-table-column
            prop="gender.name"
            label="Jenis Kelamin"
            width="110"
          />
          <el-table-column prop="status_kawin.name" label="Status Kawin" />
          <el-table-column prop="reason" label="Alasan" />
          <el-table-column label="Resume" width="80">
            <template #default="{ row }">
              <a
                class="btn-outline btn btn-sm btn-primary"
                :href="row.resume?.original_url"
                target="_blank"
              >
                <i class="ki-filled ki-cloud-download"></i>
              </a>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="card-footer">
        <el-pagination
          v-model:currentPage="useJobApplicantion.state.page"
          v-model:page-size="useJobApplicantion.state.rows"
          class="flex flex-wrap w-full"
          :disabled="useJobApplicantion.loading"
          :total="useJobApplicantion.jobApplications?.total ?? 0"
          layout="sizes, ->, total, prev, pager, next"
          @size-change="handleEvTable('rows', $event)"
          @current-change="handleEvTable('page', $event)"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import usejobApplications from '@/composables/job-applications'
import useJobPostings from '@/composables/job-postings'
import useCategories from '@/composables/master/categories'
import { handleEvTable, syncStateFilter } from '@/helpers/evTable'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const route = useRoute()
const {
  errors,
  loading,
  jobPosting,
  getJobPosting,
  storeJobPosting,
  updateJobPosting,
} = useJobPostings()
const useJobApplicantion = reactive(usejobApplications())
const useCategory = reactive(useCategories())

const copyFromId = () => {
  jobPosting.value.desc!.en = jobPosting.value.desc!.id
  jobPosting.value.desc_short!.en = jobPosting.value.desc_short!.id
  jobPosting.value.title!.en = jobPosting.value.title!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  address: [
    {
      required: true,
      message: 'Alamat wajib diisi.',
      trigger: 'blur',
    },
  ],
  category_id: [
    {
      required: true,
      message: 'Kategori wajib diisi.',
      trigger: 'blur',
    },
  ],
  'desc.id': [
    {
      required: true,
      message: 'Deskripsi (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'desc.en': [
    {
      required: true,
      message: 'Deskripsi (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'desc_short.id': [
    {
      required: true,
      message: 'Deskripsi Singkat (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'desc_short.en': [
    {
      required: true,
      message: 'Deskripsi Singkat (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  location: [
    {
      required: true,
      message: 'Lokasi wajib diisi.',
      trigger: 'blur',
    },
  ],
  'title.id': [
    {
      required: true,
      message: 'Judul (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'title.en': [
    {
      required: true,
      message: 'Judul (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (props.id) {
        await updateJobPosting(props.id, { ...jobPosting.value })
      } else {
        await storeJobPosting({ ...jobPosting.value })
      }
    }
  })
}

onMounted(() => {
  useCategory.state['filter[parent_slug]'] = 'karir'
  useCategory.getAllCategories()

  if (props.id) {
    getJobPosting(props.id)

    useJobApplicantion.state.include = 'gender,resume,statusKawin'
    useJobApplicantion.state['filter[job_posting_id]'] = props.id
    useJobApplicantion.getjobApplications()
  }
})

// Event Table
watch(
  () => route.query,
  () => {
    syncStateFilter(route, useJobApplicantion.state)

    useJobApplicantion.getjobApplications()
  },
)
</script>
