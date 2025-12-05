<template>
  <el-form
    ref="formRef"
    class="card"
    :model="auditLog"
    label-position="top"
    require-asterisk-position="right"
    scroll-to-error
    status-icon
  >
    <div class="card-body">
      <div class="grid grid-cols-2 gap-4">
        <InputBase v-model="auditLog.id" label="ID" name="id" disabled />

        <InputBase
          v-model="auditLog.batch_uuid"
          label="Batch UUID"
          name="batch_uuid"
          disabled
        />

        <InputDate
          v-model="auditLog.created_at"
          label="Tanggal"
          name="created_at"
          disabled
        />

        <InputBase
          v-model="auditLog.log_name"
          label="Nama Log"
          name="log_name"
          disabled
        />

        <InputBase
          v-model="auditLog.description"
          label="Deskripsi"
          name="description"
          disabled
        />

        <InputBase
          v-model="auditLog.event"
          label="Event"
          name="event"
          disabled
        />

        <InputBase
          v-model="auditLog.subject_type"
          label="Tipe Subjek"
          name="subject_type"
          disabled
        />

        <InputBase
          v-model="auditLog.causer_type"
          label="Causer Type"
          name="causer_type"
          disabled
        />

        <InputBase
          v-model="auditLog.properties"
          class="col-span-2"
          :autosize="{ minRows: 6 }"
          label="Properties"
          name="properties"
          type="textarea"
          disabled
        />
      </div>
    </div>
    <div class="justify-start gap-3 card-footer">
      <button
        class="btn btn-sm btn-secondary"
        type="button"
        @click="$router.back()"
      >
        <i class="ki-filled ki-left"></i> Kembali
      </button>
    </div>
  </el-form>
</template>

<script setup lang="ts">
import useAuditLogs from '@/composables/settings/system/audit-logs'
import type { FormInstance } from 'element-plus'

definePageMeta({
  title: 'Detail',
  breadcrumbs: [
    { title: 'Settings' },
    { title: 'Audit Logs', to: '/settings/system/audit-logs' },
  ],
  authorize: ['audit_log_read'],
})

const formRef = ref<FormInstance>()
const route = useRoute()
const { loading, auditLog, getAuditLog } = useAuditLogs()

onMounted(async () => {
  if (route.params.id) {
    await getAuditLog(route.params.id.toString())

    auditLog.value['properties'] = JSON.stringify(
      auditLog.value['properties'],
      null,
      4,
    )
  }
})
</script>
