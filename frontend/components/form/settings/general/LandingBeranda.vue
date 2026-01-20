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

      <div class="gap-4 grid grid-cols-4">
        <InputBase
          v-model="generalSetting.hero_cta_link"
          :errors="errors"
          label="CTA Link"
          name="hero_cta_link"
        />
      </div>

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

          <InputBase
            v-if="generalSetting.hero_cta_text"
            v-model="generalSetting.hero_cta_text.id"
            :errors="errors"
            label="CTA Text (ID)"
            name="hero_cta_text.id"
          />

          <InputTag
            v-if="generalSetting.hero_rotation_words"
            v-model="generalSetting.hero_rotation_words.id"
            :errors="errors"
            label="Rotation Words (ID)"
            name="hero_rotation_words.id"
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

          <InputBase
            v-if="generalSetting.hero_cta_text"
            v-model="generalSetting.hero_cta_text.en"
            :errors="errors"
            label="CTA Text (EN)"
            name="hero_cta_text.en"
          />

          <InputTag
            v-if="generalSetting.hero_rotation_words"
            v-model="generalSetting.hero_rotation_words.en"
            :errors="errors"
            label="Rotation Words (EN)"
            name="hero_rotation_words.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Produk</ElDivider>

      <div class="gap-4 grid grid-cols-4">
        <InputBase
          v-model="generalSetting.product_cta_link"
          :errors="errors"
          label="CTA Link"
          name="product_cta_link"
        />
      </div>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.product_title"
            v-model="generalSetting.product_title.id"
            :errors="errors"
            label="Title (ID)"
            name="product_title.id"
          />

          <InputBase
            v-if="generalSetting.product_subtitle"
            v-model="generalSetting.product_subtitle.id"
            :errors="errors"
            label="Subtitle (ID)"
            name="product_subtitle.id"
          />

          <InputBase
            v-if="generalSetting.product_cta_text"
            v-model="generalSetting.product_cta_text.id"
            :errors="errors"
            label="CTA Text (ID)"
            name="product_cta_text.id"
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
              @click="copyFromId('product')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.product_title"
            v-model="generalSetting.product_title.en"
            :errors="errors"
            label="Title (EN)"
            name="product_title.en"
          />

          <InputBase
            v-if="generalSetting.product_subtitle"
            v-model="generalSetting.product_subtitle.en"
            :errors="errors"
            label="Subtitle (EN)"
            name="product_subtitle.en"
          />

          <InputBase
            v-if="generalSetting.product_cta_text"
            v-model="generalSetting.product_cta_text.en"
            :errors="errors"
            label="CTA Text (EN)"
            name="product_cta_text.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Standar</ElDivider>

      <InputImageList
        v-model="generalSetting.standard_featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="standard_featured"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.standard_title"
            v-model="generalSetting.standard_title.id"
            :errors="errors"
            label="Title (ID)"
            name="standard_title.id"
          />

          <InputBase
            v-if="generalSetting.standard_subtitle"
            v-model="generalSetting.standard_subtitle.id"
            :errors="errors"
            label="Subtitle (ID)"
            name="standard_subtitle.id"
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
              @click="copyFromId('standard')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.standard_title"
            v-model="generalSetting.standard_title.en"
            :errors="errors"
            label="Title (EN)"
            name="standard_title.en"
          />

          <InputBase
            v-if="generalSetting.standard_subtitle"
            v-model="generalSetting.standard_subtitle.en"
            :errors="errors"
            label="Subtitle (EN)"
            name="standard_subtitle.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Testimonial</ElDivider>

      <InputImageList
        v-model="generalSetting.testimonial_background"
        :errors="errors"
        :multiple="false"
        label="Background"
        name="testimonial_background"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.testimonial_title"
            v-model="generalSetting.testimonial_title.id"
            :errors="errors"
            label="Title (ID)"
            name="testimonial_title.id"
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
              @click="copyFromId('testimonial')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.testimonial_title"
            v-model="generalSetting.testimonial_title.en"
            :errors="errors"
            label="Title (EN)"
            name="testimonial_title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section FAQ</ElDivider>

      <InputImageList
        v-model="generalSetting.faq_featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="faq_featured"
      />

      <div class="gap-4 grid grid-cols-4">
        <InputBase
          v-model="generalSetting.faq_cta_link"
          :errors="errors"
          label="CTA Link"
          name="faq_cta_link"
        />
      </div>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.faq_title"
            v-model="generalSetting.faq_title.id"
            :errors="errors"
            label="Title (ID)"
            name="faq_title.id"
          />

          <InputBase
            v-if="generalSetting.faq_cta_lead"
            v-model="generalSetting.faq_cta_lead.id"
            :errors="errors"
            label="CTA Lead (ID)"
            name="faq_cta_lead.id"
          />

          <InputBase
            v-if="generalSetting.faq_cta_text"
            v-model="generalSetting.faq_cta_text.id"
            :errors="errors"
            label="CTA Text (ID)"
            name="faq_cta_text.id"
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
              @click="copyFromId('faq')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.faq_title"
            v-model="generalSetting.faq_title.en"
            :errors="errors"
            label="Title (EN)"
            name="faq_title.en"
          />

          <InputBase
            v-if="generalSetting.faq_cta_lead"
            v-model="generalSetting.faq_cta_lead.en"
            :errors="errors"
            label="CTA Lead (EN)"
            name="faq_cta_lead.en"
          />

          <InputBase
            v-if="generalSetting.faq_cta_text"
            v-model="generalSetting.faq_cta_text.en"
            :errors="errors"
            label="CTA Text (EN)"
            name="faq_cta_text.en"
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
        standard_featured:
          generalSetting.value.standard_featured instanceof File
            ? generalSetting.value.standard_featured
            : null,
        testimonial_background:
          generalSetting.value.testimonial_background instanceof File
            ? generalSetting.value.testimonial_background
            : null,
        faq_featured:
          generalSetting.value.faq_featured instanceof File
            ? generalSetting.value.faq_featured
            : null,
      }

      const formData = objectToFormData(payload)

      await storeLandingSetting('landing-beranda', formData)
    }
  })
}

onMounted(() => {
  getGeneralSetting('landing_beranda')
})

const copyFromId = (type: string) => {
  if (type === 'faq') {
    generalSetting.value.faq_title!.en = generalSetting.value.faq_title!.id
    generalSetting.value.faq_cta_lead!.en =
      generalSetting.value.faq_cta_lead!.id
    generalSetting.value.faq_cta_text!.en =
      generalSetting.value.faq_cta_text!.id
  } else if (type === 'hero') {
    generalSetting.value.hero_cta_text!.en =
      generalSetting.value.hero_cta_text!.id
    generalSetting.value.hero_rotation_words!.en =
      generalSetting.value.hero_rotation_words!.id
    generalSetting.value.hero_subtitle!.en =
      generalSetting.value.hero_subtitle!.id
    generalSetting.value.hero_title!.en = generalSetting.value.hero_title!.id
  } else if (type === 'product') {
    generalSetting.value.product_cta_text!.en =
      generalSetting.value.product_cta_text!.id
    generalSetting.value.product_subtitle!.en =
      generalSetting.value.product_subtitle!.id
    generalSetting.value.product_title!.en =
      generalSetting.value.product_title!.id
  } else if (type === 'standard') {
    generalSetting.value.standard_subtitle!.en =
      generalSetting.value.standard_subtitle!.id
    generalSetting.value.standard_title!.en =
      generalSetting.value.standard_title!.id
  } else if (type === 'testimonial') {
    generalSetting.value.testimonial_title!.en =
      generalSetting.value.testimonial_title!.id
  }
}
</script>
