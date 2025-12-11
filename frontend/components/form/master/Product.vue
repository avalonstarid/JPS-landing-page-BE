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
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <InputBase
        v-model="product.title"
        :errors="errors"
        label="Title"
        name="title"
      />

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

      <div>
        <InputBase
          v-model="product.short_desc"
          :autosize="{ minRows: 3 }"
          :errors="errors"
          label="Deskripsi Singkat"
          name="short_desc"
          type="textarea"
        />
      </div>

      <InputBase
        v-model="product.full_desc"
        :autosize="{ minRows: 6 }"
        :errors="errors"
        label="Deskripsi Lengkap"
        name="full_desc"
        type="textarea"
      />
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
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, product, getProduct, storeProduct, updateProduct } =
  useProducts()

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
      const formData = new FormData()
      formData.append('active', product.value.active?.toString() ?? '')
      formData.append('full_desc', product.value.full_desc ?? '')
      formData.append('short_desc', product.value.short_desc ?? '')
      formData.append('slug', product.value.slug ?? '')
      formData.append('sort_order', product.value.sort_order?.toString() ?? '')
      formData.append('title', product.value.title ?? '')

      if (product.value.featured instanceof File) {
        formData.append('featured', product.value.featured)
      } else if (product.value.featured === null) {
        formData.append('featured_remove', '1')
      }

      if (product.value.images instanceof Array) {
        for (let i = 0; i < product.value.images.length; i++) {
          const img = product.value.images[i]
          if (img instanceof File) {
            formData.append('images[]', img)
          }
        }
      }

      if (
        product.value.images_remove &&
        product.value.images_remove.length > 0
      ) {
        for (let i = 0; i < product.value.images_remove.length; i++) {
          formData.append(
            'images_remove[]',
            product.value.images_remove[i].toString(),
          )
        }
      }

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
