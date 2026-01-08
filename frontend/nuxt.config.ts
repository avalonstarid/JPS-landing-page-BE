// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  app: {
    head: {
      bodyAttrs: {
        class:
          'antialiased flex h-full text-base text-gray-700 [--tw-page-bg:#F6F6F9] [--tw-page-bg-dark:var(--tw-coal-200)] [--tw-content-bg:var(--tw-light)] [--tw-content-bg-dark:var(--tw-coal-500)] [--tw-content-scrollbar-color:#e8e8e8] [--tw-header-height:60px] [--tw-sidebar-width:270px] bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark] lg:overflow-hidden',
      },
      charset: 'UTF-8',
      htmlAttrs: {
        class: 'h-full',
        'data-theme': 'true',
        'data-theme-mode': 'light',
        lang: 'id',
      },
      link: [
        {
          href: '/icon/favicon-96x96.png',
          rel: 'icon',
          sizes: '96x96',
          type: 'image/png',
        },
        {
          href: '/icon/favicon.svg',
          rel: 'icon',
          type: 'image/svg+xml',
        },
        {
          href: '/icon/favicon.ico',
          rel: 'shortcut icon',
        },
        {
          href: '/icon/apple-touch-icon.png',
          rel: 'apple-touch-icon',
          sizes: '180x180',
        },
        {
          rel: 'manifest',
          href: '/manifest.webmanifest',
        },
      ],
      meta: [{ 'http-equiv': 'X-UA-Compatible', content: 'IE=edge' }],
      noscript: [
        {
          children:
            "We're sorry but JPS Landing doesn't work properly without JavaScript enabled. Please enable it to continue.",
        },
      ],
      script: [
        {
          children:
            'const defaultThemeMode="light";let themeMode;document.documentElement&&("system"===(themeMode=localStorage.getItem("theme")?localStorage.getItem("theme"):document.documentElement.hasAttribute("data-theme-mode")?document.documentElement.getAttribute("data-theme-mode"):"light")&&(themeMode=window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"),document.documentElement.classList.add(themeMode));',
          tagPosition: 'bodyClose',
        },
      ],
      viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
    },
    rootAttrs: {
      id: 'app',
    },
  },
  compatibilityDate: '2025-05-13',
  css: ['~/assets/scss/element-ui.overrides.scss', '~/assets/scss/style.scss'],
  dayjs: {
    defaultLocale: 'id',
    locales: ['id'],
    plugins: ['relativeTime'],
  },
  devtools: { enabled: false },
  // echo: {
  //   authentication: {
  //     baseUrl: process.env.VITE_API_URL!,
  //     mode: 'cookie', // available: cookie, token
  //   },
  //   broadcaster: 'reverb', // available: reverb, pusher
  //   host: process.env.VITE_ECHO_HOST!,
  //   key: process.env.VITE_ECHO_KEY!,
  //   port: Number(process.env.VITE_ECHO_PORT!),
  //   scheme: 'http', // available: http, https
  // },
  elementPlus: {
    defaultLocale: 'id',
    imports: [['useLocale', 'es/hooks/use-locale/index.mjs']],
    themes: ['dark'],
  },
  googleFonts: {
    display: 'swap',
    download: true,
    families: {
      Inter: [400, 500, 600, 700],
    },
  },
  ignore: [
    // 'pages/auth/forgot-password.vue',
    // 'pages/auth/reset-password.vue',
    'pages/auth/sign-up.vue',
    // 'plugins/pushNotifications.client.ts',
  ],
  modules: [
    '@element-plus/nuxt',
    '@nuxtjs/google-fonts',
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@vite-pwa/nuxt',
    'dayjs-nuxt',
    'nuxt-auth-sanctum',
    // 'nuxt-laravel-echo',
  ],
  nitro: {
    preset: 'bun',
  },
  postcss: {
    plugins: {
      'postcss-import': {},
      'tailwindcss/nesting': 'postcss-nesting',
      'postcss-preset-env': {
        features: {
          'nesting-rules': false,
          'is-pseudo-class': false,
        },
      },
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  pwa: {
    client: {
      installPrompt: true,
      // you don't need to include this: only for testing purposes
      // if enabling periodic sync for update use 1 hour or so (periodicSyncForUpdates: 3600)
      // periodicSyncForUpdates: 20,
    },
    devOptions: {
      enabled: true,
      type: 'module',
    },
    manifest: {
      background_color: '#ffffff',
      description: 'Admin Panel Janu Putra Sejahtera Landing Page.',
      display: 'standalone',
      icons: [
        {
          purpose: 'maskable',
          sizes: '192x192',
          src: '/icon/web-app-manifest-192x192.png',
          type: 'image/png',
        },
        {
          purpose: 'maskable',
          sizes: '512x512',
          src: '/icon/web-app-manifest-512x512.png',
          type: 'image/png',
        },
      ],
      lang: 'id',
      name: 'Janu Putra Sejahtera',
      orientation: 'any',
      short_name: 'JPS',
      start_url: '/?utm_medium=PWA&utm_source=launcher',
      theme_color: '#ffffff',
    },
    registerType: 'autoUpdate',
    workbox: {
      globPatterns: ['**/*.{js,css,png,svg,ico}'],
      maximumFileSizeToCacheInBytes: 3000000,
      navigateFallback: null,
      runtimeCaching: [
        {
          urlPattern: ({ url }) => {
            return url.pathname.startsWith('/api')
          },
          handler: 'NetworkFirst' as const,
          options: {
            cacheName: 'api-cache',
            cacheableResponse: {
              statuses: [0, 200],
            },
          },
        },
      ],
    },
  },
  sanctum: {
    baseUrl: process.env.VITE_API_URL!,
    endpoints: {
      login: '/v1/auth/login/' + process.env.VITE_SANCTUM_MODE,
      logout: '/v1/auth/logout/' + process.env.VITE_SANCTUM_MODE,
      user: '/v1/auth/fetch/' + process.env.VITE_SANCTUM_MODE,
    },
    globalMiddleware: {
      enabled: true,
    },
    // @ts-ignore
    mode: process.env.VITE_SANCTUM_MODE!,
    redirect: {
      onAuthOnly: '/auth/sign-in',
      onGuestOnly: '/',
      onLogin: false,
      onLogout: '/auth/sign-in',
    },
  },
  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          api: 'modern-compiler',
        },
      },
    },
    optimizeDeps: {
      include: ['pusher-js'],
    },
    server: {
      allowedHosts: true,
    },
  },
})
