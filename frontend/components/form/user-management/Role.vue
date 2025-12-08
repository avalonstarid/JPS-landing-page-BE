<template>
  <el-form
    ref="formRef"
    class="card"
    :model="role"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="gap-4 grid grid-cols-2 card-body">
      <InputBase
        v-model="role.name"
        :errors="errors"
        :formatter="(value: any) => value.toLowerCase()"
        label="Nama Role"
        name="name"
      />

      <InputBase
        v-model="role.label"
        :errors="errors"
        label="Label"
        name="label"
      />

      <InputSelect
        v-model="role.permissions"
        :errors="errors"
        :max-collapse-tags="5"
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

      <InputBase
        v-model="role.desc"
        :autosize="{ minRows: 2 }"
        :errors="errors"
        label="Deskripsi"
        name="desc"
        type="textarea"
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
import usePermissions from '@/composables/user-management/permissions'
import useRoles from '@/composables/user-management/roles'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, role, getRole, storeRole, updateRole } = useRoles()
const { permissions, getAllPermissions } = usePermissions()

// Create Rules Form
const rules = reactive<FormRules>({
  name: [
    {
      required: true,
      message: 'Nama Role wajib diisi.',
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
        await updateRole(props.id, { ...role.value })
      } else {
        await storeRole({ ...role.value })
      }
    }
  })
}

onMounted(() => {
  getAllPermissions()

  if (props.id) {
    getRole(props.id)
  }
})
</script>
