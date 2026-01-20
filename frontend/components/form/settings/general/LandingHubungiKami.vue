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
      <ElDivider class="!my-4">Section Hero</ElDivider>

      <InputImageList
        v-model="generalSetting.hero_background"
        :errors="errors"
        :multiple="false"
        label="Hero Background"
        name="hero_background"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.hero_title"
            v-model="generalSetting.hero_title.id"
            :errors="errors"
            label="Title (ID)"
            name="hero_title.id"
          />

          <InputBase
            v-if="generalSetting.hero_subtitle"
            v-model="generalSetting.hero_subtitle.id"
            :errors="errors"
            label="Subtitle (ID)"
            name="hero_subtitle.id"
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
              @click="copyFromId('hero')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.hero_title"
            v-model="generalSetting.hero_title.en"
            :errors="errors"
            label="Title (EN)"
            name="hero_title.en"
          />

          <InputBase
            v-if="generalSetting.hero_subtitle"
            v-model="generalSetting.hero_subtitle.en"
            :errors="errors"
            label="Subtitle (EN)"
            name="hero_subtitle.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Contact Form</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <InputBase
          v-model="generalSetting.map_link"
          :errors="errors"
          label="Map Link"
          name="map_link"
        />

        <InputBase
          v-model="generalSetting.map_address"
          :autosize="{ minRows: 2 }"
          :errors="errors"
          label="Map Address"
          name="map_address"
          type="textarea"
        />
      </div>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.title"
            v-model="generalSetting.title.id"
            :errors="errors"
            label="Title (ID)"
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
              @click="copyFromId('contact_form')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.title"
            v-model="generalSetting.title.en"
            :errors="errors"
            label="Title (EN)"
            name="title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Kontak Lebih Lanjut</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.contact_title"
            v-model="generalSetting.contact_title.id"
            :errors="errors"
            label="Title (ID)"
            name="contact_title.id"
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
              @click="copyFromId('more_contact')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.contact_title"
            v-model="generalSetting.contact_title.en"
            :errors="errors"
            label="Title (EN)"
            name="contact_title.en"
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
        hero_background:
          generalSetting.value.hero_background instanceof File
            ? generalSetting.value.hero_background
            : null,
      }

      const formData = objectToFormData(payload)

      await storeLandingSetting('landing-hubungi-kami', formData)
    }
  })
}

onMounted(() => {
  getGeneralSetting('landing_hubungi_kami')
})

const copyFromId = (type: string) => {
  if (type === 'contact_form') {
    generalSetting.value.title!.en = generalSetting.value.title!.id
  } else if (type === 'hero') {
    generalSetting.value.hero_subtitle!.en =
      generalSetting.value.hero_subtitle!.id
    generalSetting.value.hero_title!.en = generalSetting.value.hero_title!.id
  } else if (type === 'more_contact') {
    generalSetting.value.contact_title!.en =
      generalSetting.value.contact_title!.id
  }
}
</script>
