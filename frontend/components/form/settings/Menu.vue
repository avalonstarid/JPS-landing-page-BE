<template>
  <el-form
    ref="formRef"
    class="card"
    :model="menu"
    :rules="rules"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
    @submit.native.prevent="onSubmit(formRef)"
  >
    <div class="card-body">
      <div class="gap-4 grid grid-cols-6">
        <InputSelect
          v-model="menu.parent_id"
          class="col-span-2"
          :errors="errors"
          label="Parent"
          name="parent_id"
        >
          <el-option
            v-for="item in menus.data"
            :key="item.id"
            :label="item.title"
            :value="item.id"
          >
            <span v-if="item.parent">{{ item.parent.title }} > </span>
            {{ item.title }}
          </el-option>
        </InputSelect>

        <InputNumber
          v-model="menu.order"
          :errors="errors"
          label="Urutan"
          name="order"
        />

        <InputBase
          v-model="menu.title"
          class="col-span-3"
          :errors="errors"
          label="Judul"
          name="title"
        />

        <InputBase
          v-model="menu.to"
          class="col-span-3"
          :errors="errors"
          label="Page / URL"
          name="to"
        />

        <div class="col-span-3">
          <InputBase
            v-model="menu.icon"
            :errors="errors"
            label="Ikon"
            name="icon"
          />
          <div class="mt-1 text-gray-500 text-sm">
            Kode ikon
            <NuxtLink
              class="btn btn-link btn-sm"
              to="https://keenthemes.com/metronic/tailwind/docs/plugins/keenicons"
              target="_blank"
              >klik disini</NuxtLink
            >.
          </div>
        </div>

        <InputSelect
          v-model="menu.roles"
          class="col-span-3"
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
          v-model="menu.permissions"
          class="col-span-3"
          :errors="errors"
          label="Permissions"
          name="permissions"
          collapse-tags
          collapse-tags-tooltip
          multiple
        >
          <el-option
            v-for="item in permissions?.data"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </InputSelect>

        <InputSwitch
          v-model="menu.active"
          :errors="errors"
          active-text="Menu Aktif?"
          name="active"
        />

        <InputSwitch
          v-model="menu.new_tab"
          :errors="errors"
          active-text="Open New Tab?"
          name="new_tab"
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
import useMenus from '@/composables/settings/menu'
import usePermissions from '@/composables/user-management/permissions'
import useRoles from '@/composables/user-management/roles'
import type { FormInstance, FormRules } from 'element-plus'

const props = defineProps({
  id: String,
})

const formRef = ref<FormInstance>()
const {
  errors,
  loading,
  state,
  menus,
  menu,
  getMenu,
  getAllMenus,
  storeMenu,
  updateMenu,
} = useMenus()
const { permissions, getAllPermissions } = usePermissions()
const { roles, getAllRoles } = useRoles()

// Create Rules Form
const rules = reactive<FormRules>({
  order: [
    {
      required: true,
      message: 'Urutan wajib diisi.',
      trigger: 'blur',
    },
  ],
  title: [
    {
      required: true,
      message: 'Judul wajib diisi.',
      trigger: 'blur',
    },
  ],
  to: [
    {
      required: true,
      message: 'Page / URL wajib diisi.',
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
        await updateMenu(props.id, { ...menu.value })
      } else {
        await storeMenu({ ...menu.value })
      }
    }
  })
}

onMounted(() => {
  state['include'] = 'parent'
  getAllMenus()
  getAllRoles()
  getAllPermissions()

  if (props.id) {
    getMenu(props.id)
  }
})
</script>
