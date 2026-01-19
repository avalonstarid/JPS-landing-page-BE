<template>
  <el-form
    ref="formRef"
    class="card"
    :model="generalSetting"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="gap-4 grid grid-cols-2 card-body">
      <InputBase
        v-model="generalSetting.company_name"
        :errors="errors"
        label="Nama Perusahaan"
        name="company_name"
      />

      <InputBase
        v-model="generalSetting.company_phone"
        :errors="errors"
        label="No. Telepon"
        name="company_phone"
      />

      <InputBase
        v-model="generalSetting.company_desc"
        :errors="errors"
        :autosize="{ minRows: 2 }"
        label="Deskripsi"
        name="company_desc"
        type="textarea"
      />

      <InputBase
        v-model="generalSetting.company_address"
        :errors="errors"
        :autosize="{ minRows: 2 }"
        label="Alamat"
        name="company_address"
        type="textarea"
      />
    </div>

    <div class="card-body">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-lg">Social Media</h3>
        <button class="btn btn-sm btn-primary" type="button" @click="addSocial">
          <i class="ki-outline ki-plus"></i> Tambah Social Media
        </button>
      </div>

      <div
        v-for="(social, index) in generalSetting.company_social"
        :key="index"
        class="relative mb-4 p-4 border rounded-lg"
      >
        <button
          class="-top-2 -right-2 absolute btn btn-icon btn-sm btn-icon-danger"
          type="button"
          @click="removeSocial(index)"
        >
          <i class="ki-outline ki-trash"></i>
        </button>

        <div class="gap-4 grid grid-cols-4">
          <InputBase
            v-model="social.key"
            :errors="errors"
            :name="`company_social.${index}.key`"
            label="Platform Key (e.g. instagram)"
            placeholder="instagram"
          />

          <InputBase
            v-model="social.value"
            :errors="errors"
            :name="`company_social.${index}.value`"
            label="Display Text"
            placeholder="@januputra"
          />

          <div class="gap-4 grid grid-cols-2 col-span-2">
            <InputBase
              v-model="social.icon"
              :errors="errors"
              :name="`company_social.${index}.icon`"
              label="Icon Name"
              placeholder="instagram"
            />
            <div class="flex items-end">
              <el-checkbox
                v-model="social.icon_custom"
                label="Custom Icon"
                border
              />
            </div>
          </div>

          <InputBase
            v-model="social.link"
            class="col-span-2"
            :errors="errors"
            :name="`company_social.${index}.link`"
            label="URL / Link"
            placeholder="https://instagram.com/..."
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
import type { FormInstance, FormRules } from 'element-plus'

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  generalSetting,
  getGeneralSetting,
  storeCompanyProfile,
} = useGeneralSettings()

// Create Rules Form
const rules = reactive<FormRules>({
  company_address: [
    {
      required: true,
      message: 'Alamat wajib diisi.',
      trigger: 'blur',
    },
  ],
  company_desc: [
    {
      required: true,
      message: 'Deskripsi wajib diisi.',
      trigger: 'blur',
    },
  ],
  company_name: [
    {
      required: true,
      message: 'Nama Perusahaan wajib diisi.',
      trigger: 'blur',
    },
  ],
  company_phone: [
    {
      required: true,
      message: 'No. Telepon wajib diisi.',
      trigger: 'blur',
    },
  ],
})

const addSocial = () => {
  if (!generalSetting.value.company_social) {
    generalSetting.value.company_social = []
  }
  generalSetting.value.company_social.push({
    icon: '',
    icon_custom: true,
    key: '',
    link: '',
    value: '',
  })
}

const removeSocial = (index: string | number) => {
  generalSetting.value.company_social.splice(index, 1)
}

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      await storeCompanyProfile(generalSetting.value)
    }
  })
}

onMounted(async () => {
  await getGeneralSetting('company')

  if (!generalSetting.value.company_social) {
    generalSetting.value.company_social = []
  }
})
</script>
