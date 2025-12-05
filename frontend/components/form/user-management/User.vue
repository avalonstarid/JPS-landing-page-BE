<template>
  <el-form
    ref="formRef"
    class="card"
    :model="user"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="flex items-center justify-center card-group">
      <InputImage
        :previewImage="user.preview_image"
        @change="handleImageUpload"
        @remove="removeImage"
      />
    </div>

    <div class="grid grid-cols-3 gap-4 card-group">
      <InputBase
        v-model="user.name"
        :errors="errors"
        label="Nama Lengkap"
        name="name"
      />
    </div>

    <div class="grid grid-cols-3 gap-4 card-group">
      <InputBase
        v-model="user.email"
        :errors="errors"
        autocomplete="off"
        label="Alamat Email"
        name="email"
        type="email"
      />

      <InputBase
        v-model="user.password"
        :errors="errors"
        :show-helptext="user.id ? true : false"
        autocomplete="off"
        help-text="Kosongkan kata sandi jika tidak ingin mengubah."
        label="Kata Sandi"
        name="password"
        type="password"
        show-password
      />

      <InputBase
        v-model="user.password_confirmation"
        :errors="errors"
        autocomplete="off"
        label="Konfirmasi Kata Sandi"
        name="password_confirmation"
        type="password"
        show-password
      />

      <InputSelect
        v-model="user.roles"
        :errors="errors"
        label="Roles"
        name="roles"
        collapse-tags
        collapse-tags-tooltip
        multiple
      >
        <el-option
          v-for="item in roles.data"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </InputSelect>

      <InputSelect
        v-model="user.permissions"
        :errors="errors"
        label="Permissions"
        name="permissions"
        collapse-tags
        collapse-tags-tooltip
        multiple
      >
        <el-option
          v-for="item in permissions.data"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </InputSelect>
    </div>

    <div class="card-group">
      <InputSwitch
        v-model="user.active"
        :errors="errors"
        active-text="User Aktif?"
        name="active"
      />
    </div>

    <div class="justify-start gap-3 card-footer">
      <button
        class="btn btn-sm btn-secondary"
        type="button"
        @click="$router.back()"
      >
        <i class="ki-filled ki-left"></i> Kembali
      </button>
      <BtnIndicator class="btn-sm" :loading="loading">
        <i class="ki-filled ki-arrow-circle-right"></i> Submit
      </BtnIndicator>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import usePermissions from '@/composables/user-management/permissions'
import useRoles from '@/composables/user-management/roles'
import useUsers from '@/composables/user-management/users'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, user, getUser, storeUser, updateUser } = useUsers()
const { permissions, getAllPermissions } = usePermissions()
const { roles, getAllRoles } = useRoles()

// Create Rules Form
const rules = reactive<FormRules>({
  email: [
    {
      required: true,
      message: 'Email wajib diisi.',
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
  password: [
    {
      required: true,
      message: 'Kata Sandi wajib diisi.',
      trigger: 'blur',
    },
    { min: 8, message: 'Minimal 8 huruf.', trigger: 'blur' },
  ],
  password_confirmation: [
    { min: 8, message: 'Minimal 8 huruf.', trigger: 'blur' },
    {
      message: 'Kata Sandi tidak cocok.',
      trigger: 'blur',
      validator: (rule, value) => value === user.value.password,
    },
  ],
  roles: [
    {
      required: true,
      message: 'Roles wajib dipilih.',
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
        await updateUser(props.id, { ...user.value })
      } else {
        await storeUser({ ...user.value })
      }
    }
  })
}

onMounted(() => {
  getAllRoles()
  getAllPermissions()

  if (props.id) {
    getUser(props.id).then(() => {
      user.value.preview_image = user.value.avatar

      rules.password = [
        { min: 8, message: 'Minimal 8 huruf.', trigger: 'blur' },
      ]
    })
  }
})

const handleImageUpload = (e) => {
  let files = e.target.files || e.dataTransfer.files
  if (!files.length) return

  const reader = new FileReader()

  reader.onload = (e) => {
    user.value.image = e.target?.result
    user.value.preview_image = URL.createObjectURL(files[0])
  }
  reader.readAsDataURL(files[0])
}
const removeImage = () => {
  user.value.image = null
  user.value.preview_image = null
}
</script>
