<template>
  <el-form
    ref="formRef"
    class="card"
    :model="permission"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-body">
      <InputBase
        v-model="permission.name"
        :errors="errors"
        :formatter="(value: any) => value.toLowerCase()"
        label="Nama Permission"
        name="name"
      />
    </div>
    <div class="card-footer justify-start gap-3">
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
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  permission,
  getPermission,
  storePermission,
  updatePermission,
} = usePermissions()

// Create Rules Form
const rules = reactive<FormRules>({
  name: [
    {
      required: true,
      message: 'Nama Permission wajib diisi.',
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
        await updatePermission(props.id, { ...permission.value })
      } else {
        await storePermission({ ...permission.value })
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getPermission(props.id)
  }
})
</script>
