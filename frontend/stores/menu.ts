import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useMenuStore = defineStore('menu', () => {
  const errors = ref({})
  const menus = ref<any>([])
  const loading = ref<boolean>(false)

  function setMenu(payload: any) {
    menus.value = payload
  }

  function setLoading(payload: any) {
    loading.value = payload
  }

  return { errors, menus, loading, setMenu, setLoading }
})
