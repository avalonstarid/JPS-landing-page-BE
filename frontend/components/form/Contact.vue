<template>
  <el-form
    ref="formRef"
    class="card"
    :model="faq"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group gap-4 grid grid-cols-6">
      <InputNumber
        v-model="faq.sort_order"
        :errors="errors"
        label="Urutan"
        name="sort_order"
      />
    </div>

    <div class="card-group gap-4 grid grid-cols-2">
      <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
        <div class="font-bold text-gray-700">Bahasa Indonesia</div>

        <InputBase
          v-if="faq.question"
          v-model="faq.question.id"
          :errors="errors"
          label="Pertanyaan (ID)"
          name="question.id"
        />

        <InputBase
          v-if="faq.answer"
          v-model="faq.answer.id"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Jawaban (ID)"
          name="answer.id"
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
          v-if="faq.question"
          v-model="faq.question.en"
          :errors="errors"
          label="Pertanyaan (EN)"
          name="question.en"
        />

        <InputBase
          v-if="faq.answer"
          v-model="faq.answer.en"
          :errors="errors"
          :autosize="{ minRows: 2 }"
          label="Jawaban (EN)"
          name="answer.en"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group">
      <InputSwitch
        v-model="faq.active"
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
import useFaqs from '@/composables/faqs'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const { errors, loading, faq, getFaq, storeFaq, updateFaq } = useFaqs()

const copyFromId = () => {
  faq.value.answer!.en = faq.value.answer!.id
  faq.value.question!.en = faq.value.question!.id
}

// Create Rules Form
const rules = reactive<FormRules>({
  'answer.id': [
    {
      required: true,
      message: 'Jawaban (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'answer.en': [
    {
      required: true,
      message: 'Jawaban (EN) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'question.id': [
    {
      required: true,
      message: 'Pertanyaan (ID) wajib diisi.',
      trigger: 'blur',
    },
  ],
  'question.en': [
    {
      required: true,
      message: 'Pertanyaan (EN) wajib diisi.',
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
        await updateFaq(props.id, { ...faq.value })
      } else {
        await storeFaq({ ...faq.value })
      }
    }
  })
}

onMounted(() => {
  if (props.id) {
    getFaq(props.id)
  }
})
</script>
