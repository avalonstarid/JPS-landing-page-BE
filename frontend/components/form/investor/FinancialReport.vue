<template>
  <el-form
    ref="formRef"
    class="card"
    :model="financialReport"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-4">
      <InputNumber
        v-model="financialReport.tahun"
        :errors="errors"
        label="Tahun"
        name="tahun"
      />

      <InputCurrency
        v-model="financialReport.arus_kas_bersih"
        :errors="errors"
        label="Arus Kas Bersih"
        name="arus_kas_bersih"
      />

      <InputCurrency
        v-model="financialReport.ekuitas"
        :errors="errors"
        label="Ekuitas"
        name="ekuitas"
      />

      <InputCurrency
        v-model="financialReport.laba_bersih"
        :errors="errors"
        label="Laba Bersih"
        name="laba_bersih"
      />

      <InputCurrency
        v-model="financialReport.liabilitas"
        :errors="errors"
        label="Liabilitas"
        name="liabilitas"
      />

      <InputCurrency
        v-model="financialReport.penjualan"
        :errors="errors"
        label="Penjualan"
        name="penjualan"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="financialReport.name"
          v-model="financialReport.name.id"
          :errors="errors"
          label="Nama (ID)"
          name="name.id"
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
          v-if="financialReport.name"
          v-model="financialReport.name.en"
          :errors="errors"
          label="Nama (EN)"
          name="name.en"
        />
      </div>
    </div>

    <div class="card-group">
      <InputFile
        v-model="financialReport.document"
        :errors="errors"
        :multiple="false"
        label="File Dokumen"
        name="document"
      />
    </div>

    <div class="justify-start gap-3 card-footer">
      <button
        class="btn btn-sm btn-secondary"
        type="button"
        @click="$router.back()"
      >
        <i class="ki-left ki-filled"></i> Kembali
      </button>
      <BtnIndicator class="btn-sm" :loading="loading">
        <i class="ki-arrow-circle-right ki-filled"></i> Submit
      </BtnIndicator>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import useFinancialReports from '@/composables/investor/financial-reports'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  financialReport,
  getFinancialReport,
  storeFinancialReport,
  updateFinancialReport,
} = useFinancialReports()

const copyFromId = () => {
  financialReport.value.name!.en = financialReport.value.name!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  document: [
    {
      required: true,
      message: 'File Dokumen wajib diisi.',
      trigger: 'blur',
    },
  ],
  featured: [
    {
      required: true,
      message: 'Featured wajib diisi.',
      trigger: 'blur',
    },
  ],
  'name.en': [
    {
      required: true,
      message: 'Nama (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'name.id': [
    {
      required: true,
      message: 'Nama (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...financialReport.value,
        document:
          financialReport.value.document instanceof File
            ? financialReport.value.document
            : null,
      }

      const formData = objectToFormData(payload)

      if (props.id) {
        await updateFinancialReport(props.id, formData)
      } else {
        await storeFinancialReport(formData)
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getFinancialReport(props.id)
  }
})
</script>
