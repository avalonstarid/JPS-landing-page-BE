<template>
  <el-form
    ref="formRef"
    class="card"
    :model="dewan"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex justify-center">
      <InputImageList
        v-model="dewan.avatar"
        :errors="errors"
        :multiple="false"
        label="Avatar"
        name="avatar"
        @remove="handleAvatarRemove"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-4">
      <InputSelect
        v-model="dewan.organisasi_id"
        :errors="errors"
        label="Organisasi"
        name="organisasi_id"
      >
        <el-option
          v-for="item in useOrga.organizations?.data"
          :key="item.id"
          :label="item.name.id"
          :value="item.id"
        />
      </InputSelect>

      <InputBase
        v-model="dewan.name"
        :errors="errors"
        label="Nama"
        name="name"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="dewan.jabatan"
          v-model="dewan.jabatan.id"
          :errors="errors"
          label="Jabatan (ID)"
          name="jabatan.id"
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
          v-if="dewan.jabatan"
          v-model="dewan.jabatan.en"
          :errors="errors"
          label="Jabatan (EN)"
          name="jabatan.en"
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
import useDewans from '@/composables/master/dewan'
import useOrganizations from '@/composables/master/organisasi'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, dewan, getDewan, storeDewan, updateDewan } =
  useDewans()
const useOrga = reactive(useOrganizations())

const copyFromId = () => {
  dewan.value.jabatan!.en = dewan.value.jabatan!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  'jabatan.en': [
    {
      required: true,
      message: 'Jabatan (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'jabatan.id': [
    {
      required: true,
      message: 'Jabatan (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  name: [
    {
      required: true,
      message: 'Nama wajib diisi.',
      trigger: 'blur',
    },
  ],
  type_id: [
    {
      required: true,
      message: 'Jenis wajib diisi.',
      trigger: 'blur',
    },
  ],
})

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...dewan.value,
        avatar: dewan.value.avatar instanceof File ? dewan.value.avatar : null,
      }

      const formData = objectToFormData(payload)

      if (props.id) {
        await updateDewan(props.id, formData)
      } else {
        await storeDewan(formData)
      }
    }
  })
}

onMounted(() => {
  useOrga.getOrganizations()

  if (props.id) {
    getDewan(props.id)
  }
})

const handleAvatarRemove = (item: any) => {
  dewan.value.avatar_remove = 1
}
</script>
