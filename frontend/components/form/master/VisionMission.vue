<template>
  <el-form
    ref="formRef"
    class="card"
    :model="visionMission"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-6">
      <InputNumber
        v-model="visionMission.sort_order"
        :errors="errors"
        label="Urutan"
        name="sort_order"
      />

      <InputBase
        v-model="visionMission.icon"
        class="col-span-2"
        :errors="errors"
        label="Icon"
        name="icon"
      >
        <template #append>
          <InputSwitch
            v-model="visionMission.icon_custom"
            :errors="errors"
            active-text="Custom Icon?"
            name="icon_custom"
          />
        </template>
      </InputBase>
    </div>

    <div v-if="visionMission.desc" class="card-group gap-4 grid grid-cols-2">
      <div class="p-4 border border-gray-200 rounded-lg">
        <div class="mb-4 font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-model="visionMission.desc.id"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Deskripsi (ID)"
          name="desc.id"
          type="textarea"
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
          v-model="visionMission.desc.en"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Deskripsi (EN)"
          name="desc.en"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group">
      <InputSwitch
        v-model="visionMission.active"
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
import useVisionMissions from '@/composables/master/vision-missions'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  visionMission,
  getVisionMission,
  storeVisionMission,
  updateVisionMission,
} = useVisionMissions()

const copyFromId = () => {
  if (visionMission.value.desc) {
    visionMission.value.desc.en = visionMission.value.desc.id
  }
}

// Create Rules Form
const rules = reactive<FormRules>({
  'desc.id': [
    {
      required: true,
      message: 'Deskripsi (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'desc.en': [
    {
      required: true,
      message: 'Deskripsi (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  icon: [
    {
      required: true,
      message: 'Icon wajib diisi.',
      trigger: 'blur',
    },
  ],
  sort_order: [
    {
      required: true,
      message: 'Urutan wajib diisi.',
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
        await updateVisionMission(props.id, { ...visionMission.value })
      } else {
        await storeVisionMission({ ...visionMission.value })
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getVisionMission(props.id)
  }
})
</script>
