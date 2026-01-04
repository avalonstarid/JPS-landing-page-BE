<template>
  <el-form
    ref="formRef"
    class="card"
    :model="category"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div v-if="category.name" class="gap-4 grid grid-cols-2 card-body">
      <InputSelect
        v-model="category.parent_id"
        class="col-span-2"
        :errors="errors"
        label="Induk Kategori"
        name="parent_id"
      >
        <el-option
          v-for="item in useParentCategories.categories?.data"
          :key="item.id"
          :label="item.name.id"
          :value="item.id"
        />
      </InputSelect>

      <div class="p-4 border border-gray-200 rounded-lg">
        <div class="mb-4 font-bold text-gray-700">Bahasa Indonesia</div>
        <InputBase
          v-model="category.name.id"
          :errors="errors"
          label="Nama Kategori"
          name="name.id"
        />
      </div>

      <div class="relative p-4 border border-gray-200 rounded-lg">
        <div class="flex justify-between items-center mb-4">
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
          v-model="category.name.en"
          :errors="errors"
          label="Category Name"
          name="name.en"
        />
      </div>
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
import useCategories from '@/composables/master/categories'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  category,
  getCategory,
  storeCategory,
  updateCategory,
} = useCategories()
const useParentCategories = reactive(useCategories())

const copyFromId = () => {
  if (category.value.name) {
    category.value.name.en = category.value.name.id
  }
}

// Create Rules Form
const rules = reactive<FormRules>({
  'name.id': [
    {
      required: true,
      message: 'Nama Kategori (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'name.en': [
    {
      required: true,
      message: 'Category Name (EN) is required.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (props.id) {
        await updateCategory(props.id, { ...category.value })
      } else {
        await storeCategory({ ...category.value })
      }
    }
  })
}

onMounted(() => {
  useParentCategories.state['filter[parent_id]'] = 'null'
  useParentCategories.getAllCategories()

  if (props.id) {
    getCategory(props.id)
  }
})
</script>
