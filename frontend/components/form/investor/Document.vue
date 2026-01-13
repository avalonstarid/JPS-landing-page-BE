<template>
  <el-form
    ref="formRef"
    class="card"
    :model="docInvestor"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex justify-center">
      <InputImageList
        v-model="docInvestor.featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="featured"
        @remove="handleFeaturedRemove"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="docInvestor.title"
          v-model="docInvestor.title.id"
          :errors="errors"
          label="Judul (ID)"
          name="title.id"
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
          v-if="docInvestor.title"
          v-model="docInvestor.title.en"
          :errors="errors"
          label="Judul (EN)"
          name="title.en"
        />
      </div>
    </div>

    <div class="card-group">
      <InputFile
        v-model="docInvestor.document"
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
import useDocInvestors from '@/composables/investor/documents'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  category: {
    required: true,
    type: String,
  },
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  docInvestor,
  getDocInvestor,
  storeDocInvestor,
  updateDocInvestor,
} = useDocInvestors()

const copyFromId = () => {
  docInvestor.value.title!.en = docInvestor.value.title!.id
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
  'title.en': [
    {
      required: true,
      message: 'Judul (EN) wajib diisi.',
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
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...docInvestor.value,
        document:
          docInvestor.value.document instanceof File
            ? docInvestor.value.document
            : null,
        featured:
          docInvestor.value.featured instanceof File
            ? docInvestor.value.featured
            : null,
      }

      const formData = objectToFormData(payload)

      if (props.id) {
        await updateDocInvestor(props.category, props.id, formData)
      } else {
        await storeDocInvestor(props.category, formData)
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getDocInvestor(props.category, props.id)
  }
})

const handleFeaturedRemove = (item: any) => {
  docInvestor.value.featured_remove = 1
}
</script>
