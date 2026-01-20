<template>
  <el-form
    ref="formRef"
    class="card"
    :model="organization"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-2">
      <InputSelect
        v-model="organization.parent_id"
        class="col-span-2"
        :errors="errors"
        label="Induk Organisasi"
        name="parent_id"
      >
        <el-option
          v-for="item in useParentOrga.organizations?.data"
          :key="item.id"
          :label="item.name.id"
          :value="item.id"
        />
      </InputSelect>
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="organization.name"
          v-model="organization.name.id"
          :errors="errors"
          label="Nama Organisasi (ID)"
          name="name.id"
        />

        <InputBase
          v-if="organization.desc"
          v-model="organization.desc.id"
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
          v-if="organization.name"
          v-model="organization.name.en"
          :errors="errors"
          label="Nama Organisasi (EN)"
          name="name.en"
        />

        <InputBase
          v-if="organization.desc"
          v-model="organization.desc.en"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Deskripsi (EN)"
          name="desc.en"
          type="textarea"
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
import useOrganizations from '@/composables/master/organisasi'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  organization,
  getOrganization,
  storeOrganization,
  updateOrganization,
} = useOrganizations()
const useParentOrga = reactive(useOrganizations())

const copyFromId = () => {
  organization.value.desc!.en = organization.value.desc!.id
  organization.value.name!.en = organization.value.name!.id
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
        await updateOrganization(props.id, { ...organization.value })
      } else {
        await storeOrganization({ ...organization.value })
      }
    }
  })
}

onMounted(() => {
  useParentOrga.getAllOrganizations()

  if (props.id) {
    getOrganization(props.id)
  }
})
</script>
