<template>
  <el-form
    ref="formRef"
    class="card"
    :model="post"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-6">
      <div class="flex flex-col gap-4 col-span-5">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="post.title"
            v-model="post.title.id"
            :errors="errors"
            label="Judul (ID)"
            name="title.id"
          />

          <InputTiptap
            v-if="post.content"
            v-model="post.content.id"
            :errors="errors"
            label="Konten (ID)"
            name="content.id"
            @upload="handleUpload"
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
            v-if="post.title"
            v-model="post.title.en"
            :errors="errors"
            label="Judul (EN)"
            name="title.en"
          />

          <InputTiptap
            v-if="post.content"
            v-model="post.content.en"
            :errors="errors"
            label="Konten (EN)"
            name="content.en"
            @upload="handleUpload"
          />
        </div>
      </div>

      <div class="flex flex-col items-center gap-4">
        <InputImageList
          v-model="post.featured"
          :errors="errors"
          :multiple="false"
          label="Featured Image"
          name="featured"
          @remove="handleFeaturedRemove"
        />

        <BtnIndicator class="mt-3 btn-sm" :loading="loading">
          <i class="ki-arrow-circle-right ki-filled"></i> Submit
        </BtnIndicator>
      </div>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import usePosts from '@/composables/posts'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  post,
  getPostKeberlanjutanTinjauan,
  storePostKeberlanjutanTinjauan,
} = usePosts()

const copyFromId = () => {
  post.value.content!.en = post.value.content!.id
  post.value.title!.en = post.value.title!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
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

const handleFeaturedRemove = () => {
  post.value.featured_remove = 1
}

const handleUpload = (media: any) => {
  post.value.temp_media_ids?.push(media.media_id)
}

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...post.value,
        featured:
          post.value.featured instanceof File ? post.value.featured : null,
        slug: undefined,
        type: 'TPST5',
      }

      const formData = objectToFormData(payload)

      await storePostKeberlanjutanTinjauan(formData)
    }
  })
}

onMounted(() => {
  getPostKeberlanjutanTinjauan()
})
</script>
