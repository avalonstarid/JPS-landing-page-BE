<template>
  <el-form
    ref="formRef"
    class="card"
    :model="location"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-4">
      <InputSelect
        v-model="location.business_line_id"
        :errors="errors"
        label="Lini Bisnis"
        name="business_line_id"
      >
        <el-option
          v-for="item in useBusinessLine.businessLines?.data"
          :key="item.id"
          :label="item.title.id"
          :value="item.id"
        />
      </InputSelect>

      <InputBase
        v-model="location.phone"
        :errors="errors"
        label="No. Telp"
        name="phone"
      />

      <InputBase
        v-model="location.lat"
        :errors="errors"
        label="Latitude"
        name="lat"
      />

      <InputBase
        v-model="location.lng"
        :errors="errors"
        label="Longitude"
        name="lng"
      />

      <InputBase
        v-model="location.address"
        :errors="errors"
        :autosize="{ minRows: 2 }"
        label="Alamat"
        name="address"
        type="textarea"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="location.desc"
          v-model="location.desc.id"
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
          v-if="location.desc"
          v-model="location.desc.en"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Deskripsi (EN)"
          name="desc.en"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group">
      <InputImageList
        v-model="location.images"
        :errors="errors"
        name="images"
        @remove="handleImageRemove"
      />
    </div>

    <div class="card-group">
      <InputSwitch
        v-model="location.active"
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
import useBusinessLines from '@/composables/business-lines'
import useLocations from '@/composables/master/locations'
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  location,
  getLocation,
  storeLocation,
  updateLocation,
} = useLocations()
const useBusinessLine = reactive(useBusinessLines())

const copyFromId = () => {
  location.value.desc!.en = location.value.desc!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  address: [
    {
      required: true,
      message: 'Alamat wajib diisi.',
      trigger: 'blur',
    },
  ],
  lat: [
    {
      required: true,
      message: 'Latitude wajib diisi.',
      trigger: 'blur',
    },
  ],
  lng: [
    {
      required: true,
      message: 'Longitude wajib diisi.',
      trigger: 'blur',
    },
  ],
  phone: [
    {
      required: true,
      message: 'No. Telp wajib diisi.',
      trigger: 'blur',
    },
  ],
  business_line_id: [
    {
      required: true,
      message: 'Urutan wajib diisi.',
      trigger: 'blur',
    },
  ],
})

const handleImageRemove = (item: any) => {
  if (item && typeof item === 'object' && item.id) {
    if (!location.value.images_remove) {
      location.value.images_remove = []
    }
    location.value.images_remove.push(item.id)
  }
}

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...location.value,
        images: location.value.images?.filter((img) => img instanceof File),
      }

      const formData = objectToFormData(payload)

      if (props.id) {
        await updateLocation(props.id, formData)
      } else {
        await storeLocation(formData)
      }
    }
  })
}

onMounted(() => {
  useBusinessLine.getAllBusinessLines()

  if (props.id) {
    getLocation(props.id)
  }
})
</script>
