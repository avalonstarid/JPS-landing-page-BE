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
      <ElDivider class="!my-4">Section Produk Stock Update</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.commercial_stock_title"
            v-model="generalSetting.commercial_stock_title.id"
            :errors="errors"
            label="Title (ID)"
            name="commercial_stock_title.id"
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
              @click="copyFromId('stock_update')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.commercial_stock_title"
            v-model="generalSetting.commercial_stock_title.en"
            :errors="errors"
            label="Title (EN)"
            name="commercial_stock_title.en"
          />
        </div>
      </div>

      <div class="card-body">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold text-lg">Stock Update</h3>
          <button
            class="btn btn-sm btn-primary"
            type="button"
            @click="addStockUpdate"
          >
            <i class="ki-outline ki-plus"></i> Tambah Stock Update
          </button>
        </div>

        <div
          v-for="(stat, index) in generalSetting.commercial_stock_products"
          :key="index"
          class="relative mb-4 p-4 border rounded-lg"
        >
          <button
            class="-top-2 -right-2 absolute btn btn-icon btn-sm btn-icon-danger"
            type="button"
            @click="removeStockUpdate(index)"
          >
            <i class="ki-outline ki-trash"></i>
          </button>

          <div class="gap-4 grid grid-cols-5">
            <InputBase
              v-model="stat.title.id"
              class="col-span-2"
              :errors="errors"
              :name="`commercial_stock_products.${index}.title.id`"
              label="Title (ID)"
            />

            <InputBase
              v-model="stat.title.en"
              class="col-span-2"
              :errors="errors"
              :name="`commercial_stock_products.${index}.title.en`"
              label="Title (EN)"
            />

            <InputBase
              v-model="stat.stat"
              :errors="errors"
              :name="`commercial_stock_products.${index}.stat`"
              label="Stat"
            />
          </div>
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Produk Komersial</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.commercial_title"
            v-model="generalSetting.commercial_title.id"
            :errors="errors"
            label="Title (ID)"
            name="commercial_title.id"
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
              @click="copyFromId('commercial')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.commercial_title"
            v-model="generalSetting.commercial_title.en"
            :errors="errors"
            label="Title (EN)"
            name="commercial_title.en"
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

      await storeLandingSetting('landing-produk', formData)
    }
  })
}

onMounted(async () => {
  await getGeneralSetting('landing_produk')

  if (!generalSetting.value.commercial_stock_products) {
    generalSetting.value.commercial_stock_products = []
  }
})

const copyFromId = (type: string) => {
  if (type === 'commercial') {
    generalSetting.value.commercial_title!.en =
      generalSetting.value.commercial_title!.id
  } else if (type === 'hero') {
    generalSetting.value.hero_subtitle!.en =
      generalSetting.value.hero_subtitle!.id
    generalSetting.value.hero_title!.en = generalSetting.value.hero_title!.id
  } else if (type === 'stock_update') {
    generalSetting.value.commercial_stock_title!.en =
      generalSetting.value.commercial_stock_title!.id
  }
}

const addStockUpdate = () => {
  if (!generalSetting.value.commercial_stock_products) {
    generalSetting.value.commercial_stock_products = []
  }
  generalSetting.value.commercial_stock_products.push({
    title: {
      en: '',
      id: '',
    },
    stat: 0,
  })
}

const removeStockUpdate = (index: string | number) => {
  generalSetting.value.commercial_stock_products.splice(index, 1)
}
</script>
