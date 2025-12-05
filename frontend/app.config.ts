import type { NuxtApp } from 'nuxt/app'

/**
 * Handlers to work with authentication token.
 */
export interface TokenStorage {
  /**
   * Function to load a token from the storage.
   */
  get: (app: NuxtApp) => Promise<string | undefined>
  /**
   * Function to save a token to the storage.
   */
  set: (app: NuxtApp, token?: string) => Promise<void>
}

// LocalStorage example for Laravel Authentication token
const tokenStorageKey = 'sanctum.storage.token'
const localTokenStorage: TokenStorage = {
  get: async () => {
    if (import.meta.server) {
      return undefined
    }

    return window.localStorage.getItem(tokenStorageKey) ?? undefined
  },

  set: async (app: NuxtApp, token?: string) => {
    if (import.meta.server) {
      return
    }

    if (!token) {
      window.localStorage.removeItem(tokenStorageKey)
      return
    }

    window.localStorage.setItem(tokenStorageKey, token)
  },
}

export default defineAppConfig({
  sanctum: {
    tokenStorage: localTokenStorage,
  },
})
