<template>
  <el-form
    ref="formRef"
    class="card"
    :model="generalSetting"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">SEO</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <InputBase
          v-model="generalSetting.seo_title"
          :errors="errors"
          label="Title"
          name="seo_title"
        />

        <InputBase
          v-model="generalSetting.seo_description"
          :autosize="{ minRows: 2 }"
          :errors="errors"
          label="Description"
          name="seo_description"
          type="textarea"
        />
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Hero</ElDivider>

      <InputImageList
        v-model="generalSetting.hero_background"
        :errors="errors"
        :multiple="false"
        label="Hero Background"
        name="hero_background"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.hero_title"
            v-model="generalSetting.hero_title.id"
            :errors="errors"
            label="Title (ID)"
            name="hero_title.id"
          />

          <InputBase
            v-if="generalSetting.hero_subtitle"
            v-model="generalSetting.hero_subtitle.id"
            :autosize="{ minRows: 2 }"
            :errors="errors"
            label="Subtitle (ID)"
            name="hero_subtitle.id"
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
              @click="copyFromId('hero')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.hero_title"
            v-model="generalSetting.hero_title.en"
            :errors="errors"
            label="Title (EN)"
            name="hero_title.en"
          />

          <InputBase
            v-if="generalSetting.hero_subtitle"
            v-model="generalSetting.hero_subtitle.en"
            :autosize="{ minRows: 2 }"
            :errors="errors"
            label="Subtitle (EN)"
            name="hero_subtitle.en"
            type="textarea"
          />
        </div>
      </div>

      <div class="card-body">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold text-lg">Hero Stat</h3>
          <button
            class="btn btn-sm btn-primary"
            type="button"
            @click="addHeroStat"
          >
            <i class="ki-outline ki-plus"></i> Tambah Hero Stat
          </button>
        </div>

        <div
          v-for="(stat, index) in generalSetting.hero_stat"
          :key="index"
          class="relative mb-4 p-4 border rounded-lg"
        >
          <button
            class="-top-2 -right-2 absolute btn btn-icon btn-sm btn-icon-danger"
            type="button"
            @click="removeHeroStat(index)"
          >
            <i class="ki-outline ki-trash"></i>
          </button>

          <div class="gap-4 grid grid-cols-5">
            <InputBase
              v-model="stat.label.id"
              :errors="errors"
              :name="`hero_stat.${index}.label.id`"
              label="Label (ID)"
            />

            <InputBase
              v-model="stat.label.en"
              :errors="errors"
              :name="`hero_stat.${index}.label.en`"
              label="Label (EN)"
            />

            <InputBase
              v-model="stat.value"
              :errors="errors"
              :name="`hero_stat.${index}.value`"
              label="Value"
            />

            <div class="gap-4 grid grid-cols-2 col-span-2">
              <InputBase
                v-model="stat.icon"
                :errors="errors"
                :name="`hero_stat.${index}.icon`"
                label="Icon Name"
                placeholder="instagram"
              />
              <div class="flex items-end">
                <el-checkbox
                  v-model="stat.icon_custom"
                  false-value="0"
                  label="Custom Icon"
                  true-value="1"
                  border
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Video</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <InputBase
          v-model="generalSetting.video_link"
          :errors="errors"
          label="Link Youtube"
          name="video_link"
        />
      </div>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.video_title"
            v-model="generalSetting.video_title.id"
            :errors="errors"
            label="Title (ID)"
            name="video_title.id"
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
              @click="copyFromId('video')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.video_title"
            v-model="generalSetting.video_title.en"
            :errors="errors"
            label="Title (EN)"
            name="video_title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Visi dan Misi Perusahaan</ElDivider>

      <InputImageList
        v-model="generalSetting.visi_misi_featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="visi_misi_featured"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.visi_misi_title"
            v-model="generalSetting.visi_misi_title.id"
            :errors="errors"
            label="Title (ID)"
            name="visi_misi_title.id"
          />

          <InputBase
            v-if="generalSetting.visi_misi_subtitle"
            v-model="generalSetting.visi_misi_subtitle.id"
            :autosize="{ minRows: 2 }"
            :errors="errors"
            label="Subtitle (ID)"
            name="visi_misi_subtitle.id"
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
              @click="copyFromId('visi_misi')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.visi_misi_title"
            v-model="generalSetting.visi_misi_title.en"
            :errors="errors"
            label="Title (EN)"
            name="visi_misi_title.en"
          />

          <InputBase
            v-if="generalSetting.visi_misi_subtitle"
            v-model="generalSetting.visi_misi_subtitle.en"
            :autosize="{ minRows: 2 }"
            :errors="errors"
            label="Subtitle (EN)"
            name="visi_misi_subtitle.en"
            type="textarea"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Linimasa Sejarah</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.history_timeline_title"
            v-model="generalSetting.history_timeline_title.id"
            :errors="errors"
            label="Title (ID)"
            name="history_timeline_title.id"
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
              @click="copyFromId('history_timeline')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.history_timeline_title"
            v-model="generalSetting.history_timeline_title.en"
            :errors="errors"
            label="Title (EN)"
            name="history_timeline_title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Lokasi Usaha</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.location_title"
            v-model="generalSetting.location_title.id"
            :errors="errors"
            label="Title (ID)"
            name="location_title.id"
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
              @click="copyFromId('location')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.location_title"
            v-model="generalSetting.location_title.en"
            :errors="errors"
            label="Title (EN)"
            name="location_title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Struktur Organisasi</ElDivider>

      <InputImageList
        v-model="generalSetting.organization_featured"
        :errors="errors"
        :multiple="false"
        label="Featured Image"
        name="organization_featured"
      />

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.organization_title"
            v-model="generalSetting.organization_title.id"
            :errors="errors"
            label="Title (ID)"
            name="organization_title.id"
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
              @click="copyFromId('organization')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.organization_title"
            v-model="generalSetting.organization_title.en"
            :errors="errors"
            label="Title (EN)"
            name="organization_title.en"
          />
        </div>
      </div>
    </div>

    <div class="card-group flex flex-col gap-4">
      <ElDivider class="!my-4">Section Dewan Komisaris</ElDivider>

      <div class="gap-4 grid grid-cols-2">
        <div class="flex flex-col gap-4 p-4 border border-gray-200 rounded-lg">
          <div class="font-bold text-gray-700">Bahasa Indonesia</div>

          <InputBase
            v-if="generalSetting.dewan_title"
            v-model="generalSetting.dewan_title.id"
            :errors="errors"
            label="Title (ID)"
            name="dewan_title.id"
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
              @click="copyFromId('hero')"
            >
              <i class="ki-filled ki-copy"></i> Copy From ID
            </button>
          </div>

          <InputBase
            v-if="generalSetting.dewan_title"
            v-model="generalSetting.dewan_title.en"
            :errors="errors"
            label="Title (EN)"
            name="dewan_title.en"
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
import { objectToFormData } from '@/helpers/formData'
import type { FormInstance } from 'element-plus'

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  generalSetting,
  getGeneralSetting,
  storeLandingTentangPerusahaan,
} = useGeneralSettings()

// Form Submit Function
const onSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) return

  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const payload = {
        ...generalSetting.value,
        hero_background:
          generalSetting.value.hero_background instanceof File
            ? generalSetting.value.hero_background
            : null,
        organization_featured:
          generalSetting.value.organization_featured instanceof File
            ? generalSetting.value.organization_featured
            : null,
        visi_misi_featured:
          generalSetting.value.visi_misi_featured instanceof File
            ? generalSetting.value.visi_misi_featured
            : null,
      }

      const formData = objectToFormData(payload)

      await storeLandingTentangPerusahaan(formData)
    }
  })
}

onMounted(async () => {
  await getGeneralSetting('landing_tentang_perusahaan')

  if (!generalSetting.value.hero_stat) {
    generalSetting.value.hero_stat = []
  }
})

const copyFromId = (type: string) => {
  if (type === 'dewan') {
    generalSetting.value.dewan_title!.en = generalSetting.value.dewan_title!.id
  } else if (type === 'hero') {
    generalSetting.value.hero_subtitle!.en =
      generalSetting.value.hero_subtitle!.id
    generalSetting.value.hero_title!.en = generalSetting.value.hero_title!.id
  } else if (type === 'history_timeline') {
    generalSetting.value.history_timeline_title!.en =
      generalSetting.value.history_timeline_title!.id
  } else if (type === 'location') {
    generalSetting.value.location_title!.en =
      generalSetting.value.location_title!.id
  } else if (type === 'organization') {
    generalSetting.value.organization_title!.en =
      generalSetting.value.organization_title!.id
  } else if (type === 'video') {
    generalSetting.value.video_title!.en = generalSetting.value.video_title!.id
  } else if (type === 'visi_misi') {
    generalSetting.value.visi_misi_subtitle!.en =
      generalSetting.value.visi_misi_subtitle!.id
    generalSetting.value.visi_misi_title!.en =
      generalSetting.value.visi_misi_title!.id
  }
}

const addHeroStat = () => {
  if (!generalSetting.value.hero_stat) {
    generalSetting.value.hero_stat = []
  }
  generalSetting.value.hero_stat.push({
    icon: '',
    icon_custom: '1',
    label: {
      en: '',
      id: '',
    },
    value: '',
  })
}

const removeHeroStat = (index: string | number) => {
  generalSetting.value.hero_stat.splice(index, 1)
}
</script>
