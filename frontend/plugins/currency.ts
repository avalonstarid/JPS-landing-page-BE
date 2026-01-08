export default defineNuxtPlugin((nuxtApp) => {
  return {
    provide: {
      /**
       * Helper Currency Global
       * @param value Angka yang akan diformat
       * @param currencyCode Kode mata uang (IDR, USD, dll)
       * @param compact Singkat angka? (true = 1.5 jt, false = 1.500.000)
       */
      currency: (
        value: number | string | null | undefined,
        currencyCode: string = 'IDR',
        compact: boolean = false,
      ) => {
        // 1. Validasi Value
        if (value === null || value === undefined || value === '')
          return formatZero(currencyCode)

        const number = Number(value)
        if (isNaN(number)) return formatZero(currencyCode)

        // 2. Tentukan Locale & Desimal Dasar
        let locale = 'id-ID'
        let decimals = 0

        switch (currencyCode) {
          case 'USD':
            locale = 'en-US'
            decimals = 2
            break
          case 'SGD':
            locale = 'en-SG'
            decimals = 2
            break
          case 'EUR':
            locale = 'de-DE'
            decimals = 2
            break
          case 'IDR':
          default:
            locale = 'id-ID'
            decimals = 0
            break
        }

        // 3. Konfigurasi Options
        const options: Intl.NumberFormatOptions = {
          style: 'currency',
          currency: currencyCode,
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        }

        // 4. Logic Compact (Singkatan)
        if (compact) {
          options.notation = 'compact'
          options.compactDisplay = 'short'

          // Saat disingkat, biasanya kita butuh max 1 atau 2 desimal saja
          // Contoh: Rp 1,5 jt (bukan Rp 1,5000 jt)
          options.maximumFractionDigits = 1
          // Hapus minimumFractionDigits agar angka bulat tidak punya koma (Rp 1 jt, bukan Rp 1,0 jt)
          delete options.minimumFractionDigits
        }

        // 5. Eksekusi Formatting
        try {
          return new Intl.NumberFormat(locale, options).format(number)
        } catch (error) {
          return `${currencyCode} ${number}`
        }
      },
    },
  }
})

// Helper kecil
const formatZero = (currency: string) => {
  if (currency === 'IDR') return 'Rp 0'
  if (currency === 'USD') return '$0.00'

  return `${currency} 0`
}
