<template>
  <el-form
    ref="formRef"
    class="card"
    :model="businessLine"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex justify-center">
      <InputImageList
        v-model="businessLine.featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="featured"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-6">
      <InputNumber
        v-model="businessLine.sort_order"
        :errors="errors"
        label="Urutan"
        name="sort_order"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="businessLine.title"
          v-model="businessLine.title.id"
          :errors="errors"
          label="Judul (ID)"
          name="title.id"
        />

        <InputBase
          v-if="businessLine.desc"
          v-model="businessLine.desc.id"
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
          v-if="businessLine.title"
          v-model="businessLine.title.en"
          :errors="errors"
          label="Judul (EN)"
          name="title.en"
        />

        <InputBase
          v-if="businessLine.desc"
          v-model="businessLine.desc.en"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Deskripsi (EN)"
          name="desc.en"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group">
      <InputImageList
        v-model="businessLine.images"
        :errors="errors"
        name="images"
        @remove="handleImageRemove"
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
import useBusinessLines from '@/composables/business-lines'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  businessLine,
  getBusinessLine,
  storeBusinessLine,
  updateBusinessLine,
} = useBusinessLines()

const copyFromId = () => {
  businessLine.value.desc!.en = businessLine.value.desc!.id
  businessLine.value.title!.en = businessLine.value.title!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
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
  sort_order: [
    {
      required: true,
      message: 'Urutan wajib diisi.',
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

const handleImageRemove = (item: any) => {
  if (item && typeof item === 'object' && item.id) {
    if (!businessLine.value.images_remove) {
      businessLine.value.images_remove = []
    }
    businessLine.value.images_remove.push(item.id)
  }
}

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const formData = new FormData()
      formData.append('desc[en]', businessLine.value.desc!.en)
      formData.append('desc[id]', businessLine.value.desc!.id)
      formData.append(
        'sort_order',
        businessLine.value.sort_order?.toString() ?? '',
      )
      formData.append('title[en]', businessLine.value.title!.en)
      formData.append('title[id]', businessLine.value.title!.id)

      if (businessLine.value.featured instanceof File) {
        formData.append('featured', businessLine.value.featured)
      } else if (businessLine.value.featured === null) {
        formData.append('featured_remove', '1')
      }

      if (businessLine.value.images instanceof Array) {
        for (let i = 0; i < businessLine.value.images.length; i++) {
          const img = businessLine.value.images[i]
          if (img instanceof File) {
            formData.append('images[]', img)
          }
        }
      }

      if (
        businessLine.value.images_remove &&
        businessLine.value.images_remove.length > 0
      ) {
        for (let i = 0; i < businessLine.value.images_remove.length; i++) {
          formData.append(
            'images_remove[]',
            businessLine.value.images_remove[i].toString(),
          )
        }
      }

      if (props.id) {
        await updateBusinessLine(props.id, formData)
      } else {
        await storeBusinessLine(formData)
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getBusinessLine(props.id)
  }
})
</script>
