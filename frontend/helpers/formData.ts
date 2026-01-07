export function objectToFormData(
  obj: any,
  form: FormData = new FormData(),
  namespace = '',
): FormData {
  for (const property in obj) {
    if (
      !obj.hasOwnProperty(property) ||
      obj[property] === undefined ||
      obj[property] === null
    ) {
      continue
    }

    const formKey = namespace ? `${namespace}[${property}]` : property
    const value = obj[property]

    if (value instanceof Date) {
      form.append(formKey, value.toISOString())
    } else if (value instanceof File || value instanceof Blob) {
      form.append(formKey, value)
    } else if (Array.isArray(value)) {
      value.forEach((element, index) => {
        // Jika array berisi File, gunakan key[], jika tidak gunakan key[index]
        // Sesuaikan dengan logic backend (Laravel biasanya suka key[])
        const arrayKey =
          element instanceof File ? `${formKey}[]` : `${formKey}[${index}]`

        if (typeof element === 'object' && !(element instanceof File)) {
          objectToFormData(element, form, `${formKey}[${index}]`)
        } else {
          form.append(`${formKey}[]`, element)
        }
      })
    } else if (typeof value === 'object' && !(value instanceof File)) {
      objectToFormData(value, form, formKey)
    } else {
      // Handle boolean: convert true/false to '1'/'0'
      const val = typeof value === 'boolean' ? (value ? '1' : '0') : value
      form.append(formKey, val.toString())
    }
  }
  return form
}
