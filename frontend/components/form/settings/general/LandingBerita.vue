<template>
  <el-form
    ref="formRef"
    class="card"
    :model="generalSetting"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">SEO</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <InputBase
          v-model="generalSetting.seo_title"
          :errors="errors"
          label="Title"
          name="seo_title"
        />

        <InputBase
          v-model="generalSetting.seo_description"
          :autosize="{ minRows: 2 }"
          :errors="errors"
          label="Description"
          name="seo_description"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Popular</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.popular_title"
            v-model="generalSetting.popular_title.id"
            :errors="errors"
            label="Title (ID)"
            name="popular_title.id"
          />

          <InputBase
            v-if="generalSetting.popular_subtitle"
            v-model="generalSetting.popular_subtitle.id"
            :errors="errors"
            label="Subtitle (ID)"
            name="popular_subtitle.id"
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
              @click="copyFromId('popular')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.popular_title"
            v-model="generalSetting.popular_title.en"
            :errors="errors"
            label="Title (EN)"
            name="popular_title.en"
          />

          <InputBase
            v-if="generalSetting.popular_subtitle"
            v-model="generalSetting.popular_subtitle.en"
            :errors="errors"
            label="Subtitle (EN)"
            name="popular_subtitle.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Terbaru</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.news_title"
            v-model="generalSetting.news_title.id"
            :errors="errors"
            label="Title (ID)"
            name="news_title.id"
          />

          <InputBase
            v-if="generalSetting.news_subtitle"
            v-model="generalSetting.news_subtitle.id"
            :errors="errors"
            label="Subtitle (ID)"
            name="news_subtitle.id"
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
              @click="copyFromId('news')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.news_title"
            v-model="generalSetting.news_title.en"
            :errors="errors"
            label="Title (EN)"
            name="news_title.en"
          />

          <InputBase
            v-if="generalSetting.news_subtitle"
            v-model="generalSetting.news_subtitle.en"
            :errors="errors"
            label="Subtitle (EN)"
            name="news_subtitle.en"
          />
        </div>
      </div>
    </div>

    <div class="justify-start gap-3 card-footer">
      <BtnIndicator class="btn-sm" :loading="loading">
        <i class="ki-arrow-circle-right ki-filled"></i> Submit
      </BtnIndicator>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import useGeneralSettings from '@/composables/settings/general-settings'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance } from 'element-plus'

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  generalSetting,
  getGeneralSetting,
  storeLandingSetting,
} = useGeneralSettings()

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...generalSetting.value,
      }

      const formData = objectToFormData(payload)

      await storeLandingSetting('landing-berita', formData)
    }
  })
}

onMounted(() => {
  getGeneralSetting('landing_berita')
})

const copyFromId = (type: string) => {
  if (type === 'popular') {
    generalSetting.value.popular_title!.en =
      generalSetting.value.popular_title!.id
    generalSetting.value.popular_subtitle!.en =
      generalSetting.value.popular_subtitle!.id
  } else if (type === 'news') {
    generalSetting.value.news_title!.en = generalSetting.value.news_title!.id
    generalSetting.value.news_subtitle!.en =
      generalSetting.value.news_subtitle!.id
  }
}
</script>
