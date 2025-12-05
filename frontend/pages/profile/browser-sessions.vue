<template>
  <div class="w-full">
    <p class="mb-5">
      Anda login di perangkat berikut atau telah login dalam 30 hari terakhir.
      Mungkin ada beberapa sesi aktivitas dari perangkat yang sama.
    </p>

    <div v-if="session.id" class="card mb-5">
      <div class="card-body">
        <div class="flex items-center gap-4">
          <div class="leading-none w-5 shrink-0">
            <i class="ki-filled ki-screen text-gray-500 text-2xl"> </i>
          </div>
          <div class="flex flex-col gap-0.5">
            <span class="leading-none font-medium text-sm text-gray-900">
              {{ `${session.ua?.os?.name} ${session.ua?.os?.version?.alias}` }}
              -
              {{
                `${session.ua?.browser?.name} (${session.ua?.browser?.version?.value})`
              }}
            </span>
            <span class="text-2sm text-gray-700 font-normal">
              {{ session.ip_address }},
              <span class="text-success">Perangkat ini</span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <template v-for="item in sessions.data">
      <div class="card mb-5">
        <div class="card-body flex justify-between">
          <div class="flex items-center gap-4">
            <div class="leading-none w-5 shrink-0">
              <i class="ki-filled ki-screen text-gray-500 text-2xl"> </i>
            </div>
            <div class="flex flex-col gap-0.5">
              <span class="leading-none font-medium text-sm text-gray-900">
                {{ `${item.ua?.os?.name} ${item.ua?.os?.version?.alias}` }}
                -
                {{
                  `${item.ua?.browser?.name} (${item.ua?.browser?.version?.value})`
                }}
              </span>
              <span class="text-2sm text-gray-700 font-normal">
                {{ item.ip_address }}, Terakhir aktif
                {{ $dayjs.unix(item.last_activity).fromNow() }}
              </span>
            </div>
          </div>

          <button
            class="btn btn-sm btn-danger"
            type="button"
            @click="destroyData(item.id)"
          >
            Hapus
          </button>
        </div>
      </div>
    </template>

    <button class="btn btn-danger" type="button" @click="destroyOther">
      Keluar Dari Perangkat Lain
    </button>
  </div>
</template>

<script setup lang="ts">
import useSessions from '@/composables/user-management/sessions'

definePageMeta({
  title: 'Perangkat',
  breadcrumbs: [{ title: 'Profil' }],
})

const {
  errors,
  loading,
  session,
  sessions,
  getCurrentSession,
  getSessions,
  destroySession,
  destroyOtherSession,
} = useSessions()

const destroyData = (id: string) => {
  ElMessageBox.confirm(
    'Apakah yakin akan keluar dari perangkat ini?',
    'Warning',
    {
      closeOnClickModal: false,
      type: 'warning',
    },
  )
    .then(async () => {
      await destroySession(id)
      await getSessions()
    })
    .catch(() => {})
}

const destroyOther = () => {
  ElMessageBox.confirm(
    'Apakah yakin akan keluar dari perangkat lainnya?',
    'Warning',
    {
      closeOnClickModal: false,
      type: 'warning',
    },
  )
    .then(async () => {
      await destroyOtherSession()
      await getSessions()
    })
    .catch(() => {})
}

onMounted(() => {
  getCurrentSession()
  getSessions()
})
</script>
