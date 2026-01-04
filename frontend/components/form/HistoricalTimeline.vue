<template>
  <el-form
    ref="formRef"
    class="card"
    :model="historicalTimeline"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-6">
      <InputNumber
        v-model="historicalTimeline.year"
        :errors="errors"
        label="Tahun"
        name="year"
      />

      <InputBase
        v-model="historicalTimeline.icon"
        class="col-span-2"
        :errors="errors"
        label="Icon"
        name="icon"
      >
        <template #append>
          <InputSwitch
            v-model="historicalTimeline.icon_custom"
            :errors="errors"
            active-text="Custom Icon?"
            name="icon_custom"
          />
        </template>
      </InputBase>
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="historicalTimeline.title"
          v-model="historicalTimeline.title.id"
          :errors="errors"
          label="Judul (ID)"
          name="title.id"
        />

        <InputBase
          v-if="historicalTimeline.desc"
          v-model="historicalTimeline.desc.id"
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
          v-if="historicalTimeline.title"
          v-model="historicalTimeline.title.en"
          :errors="errors"
          label="Judul (EN)"
          name="title.en"
        />

        <InputBase
          v-if="historicalTimeline.desc"
          v-model="historicalTimeline.desc.en"
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
import useHistoricalTimelines from '@/composables/historical-timelines'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  historicalTimeline,
  getHistoricalTimeline,
  storeHistoricalTimeline,
  updateHistoricalTimeline,
} = useHistoricalTimelines()

const copyFromId = () => {
  historicalTimeline.value.desc!.en = historicalTimeline.value.desc!.id
  historicalTimeline.value.title!.en = historicalTimeline.value.title!.id
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
  'title.id': [
    {
      required: true,
      message: 'Judul (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'title.en': [
    {
      required: true,
      message: 'Judul (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  year: [
    {
      required: true,
      message: 'Tahun wajib diisi.',
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
        await updateHistoricalTimeline(props.id, {
          ...historicalTimeline.value,
        })
      } else {
        await storeHistoricalTimeline({ ...historicalTimeline.value })
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getHistoricalTimeline(props.id)
  }
})
</script>
