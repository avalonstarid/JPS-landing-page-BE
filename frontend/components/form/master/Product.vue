<template>
  <el-form
    ref="formRef"
    class="card"
    :model="product"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex justify-center">
      <InputImageList
        v-model="product.featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="featured"
        @remove="handleFeaturedRemove"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="gap-4 grid grid-cols-3 align-bottom">
        <InputBase
          v-model="product.slug"
          class="col-span-2"
          :errors="errors"
          label="Slug"
          name="slug"
        />

        <InputNumber
          v-model="product.sort_order"
          :errors="errors"
          label="Urutan"
          name="sort_order"
        />
      </div>
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="product.title"
          v-model="product.title.id"
          :errors="errors"
          label="Title (ID)"
          name="title.id"
        />

        <InputBase
          v-if="product.short_desc"
          v-model="product.short_desc.id"
          :autosize="{ minRows: 3 }"
          :errors="errors"
          label="Deskripsi Singkat (ID)"
          name="short_desc.id"
          type="textarea"
        />

        <InputBase
          v-if="product.full_desc"
          v-model="product.full_desc.id"
          :autosize="{ minRows: 6 }"
          :errors="errors"
          label="Deskripsi Lengkap (ID)"
          name="full_desc.id"
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
          v-if="product.title"
          v-model="product.title.en"
          :errors="errors"
          label="Title (EN)"
          name="title.en"
        />

        <InputBase
          v-if="product.short_desc"
          v-model="product.short_desc.en"
          :autosize="{ minRows: 3 }"
          :errors="errors"
          label="Deskripsi Singkat (EN)"
          name="short_desc.en"
          type="textarea"
        />

        <InputBase
          v-if="product.full_desc"
          v-model="product.full_desc.en"
          :autosize="{ minRows: 6 }"
          :errors="errors"
          label="Deskripsi Lengkap (EN)"
          name="full_desc.en"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group">
      <InputImageList
        v-model="product.images"
        :errors="errors"
        :limit="2"
        name="images"
        @remove="handleImageRemove"
      />
    </div>

    <div class="card-group">
      <InputSwitch
        v-model="product.active"
        :errors="errors"
        active-text="Aktif?"
        name="active"
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
import useProducts from '@/composables/master/products'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, product, getProduct, storeProduct, updateProduct } =
  useProducts()

const copyFromId = () => {
  product.value.full_desc!.en = product.value.full_desc!.id
  product.value.short_desc!.en = product.value.short_desc!.id
  product.value.title!.en = product.value.title!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  sort_order: [
    {
      required: true,
      message: 'Sort Order wajib diisi.',
      trigger: 'blur',
    },
  ],
  title: [
    {
      required: true,
      message: 'Title wajib diisi.',
      trigger: 'blur',
    },
  ],
})

const handleFeaturedRemove = (item: any) => {
  console.log('Featured Remove')
  product.value.featured_remove = 1
}

const handleImageRemove = (item: any) => {
  if (item && typeof item === 'object' && item.id) {
    if (!product.value.images_remove) {
      product.value.images_remove = []
    }
    product.value.images_remove.push(item.id)
  }
}

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...product.value,
        featured:
          product.value.featured instanceof File
            ? product.value.featured
            : null,
        images: product.value.images?.filter((img) => img instanceof File),
      }

      const formData = objectToFormData(payload)

      if (props.id) {
        await updateProduct(props.id, formData)
      } else {
        await storeProduct(formData)
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getProduct(props.id)
  }
})
</script>
