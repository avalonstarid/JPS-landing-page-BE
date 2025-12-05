<template>
  <el-form
    ref="formRef"
    class="card w-full"
    :model="model"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-header">
      <h3 class="card-title">General</h3>
    </div>

    <div class="flex items-center justify-center card-group">
      <InputImage
        :previewImage="model.preview_image"
        @change="handleImageUpload"
        @remove="removeImage"
      />
    </div>

    <div class="card-group grid grid-cols-2 gap-4">
      <InputBase
        v-model="model.name"
        :errors="errors"
        label="Nama Lengkap"
        name="name"
      />

      <InputBase
        v-model="model.email"
        :errors="errors"
        autocomplete="off"
        label="Alamat Email"
        name="email"
        type="email"
      />
    </div>

    <div class="card-footer justify-start gap-3">
      <BtnIndicator class="btn-sm" :loading="loading">
        <i class="ki-filled ki-arrow-circle-right"></i> Submit
      </BtnIndicator>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import useUsers, { type IUser } from '@/composables/user-management/users'
import type { FormInstance, FormRules } from 'element-plus'

definePageMeta({
  title: 'General',
  breadcrumbs: [{ title: 'Profil' }],
})

const formRef = ref<FormInstance>()
const { refreshIdentity } = useSanctumAuth()
const user = useSanctumUser<IUser>()
const { errors, loading, updateProfile } = useUsers()
const model = ref<any>({})

// Create Rules Form
const rules = reactive<FormRules>({
  email: [
    {
      required: true,
      message: 'Alamat Email wajib diisi.',
      trigger: 'blur',
    },
    {
      type: 'email',
      message: 'Silakan masukkan alamat email yang benar.',
      trigger: ['blur', 'change'],
    },
  ],
  name: [
    {
      required: true,
      message: 'Nama Lengkap wajib diisi.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      await updateProfile({ ...model.value })
      await refreshIdentity()
    }
  })
}

onMounted(() => {
  if (typeof user.value === 'object') {
    model.value = { ...user.value }
    model.value.preview_image = user.value?.avatar
  }
})

const handleImageUpload = (e) => {
  let files = e.target.files || e.dataTransfer.files
  if (!files.length) return

  const reader = new FileReader()

  reader.onload = (e) => {
    model.value.image = e.target?.result
    model.value.preview_image = URL.createObjectURL(files[0])
  }
  reader.readAsDataURL(files[0])
}
const removeImage = () => {
  model.value.image = null
  model.value.preview_image = null
}
</script>
