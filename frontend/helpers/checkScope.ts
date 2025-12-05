import type { IUser } from '@/composables/user-management/users'

const checkScope = (value: any) => {
  if (value && value instanceof Array && value.length > 0) {
    const user = useSanctumUser<IUser>()

    const scopes = user.value?.scope ?? []
    const requiredScopes = value

    if (scopes.includes('super-admin')) {
      return true
    }

    return scopes.some((role: any) => {
      return requiredScopes.includes(role)
    })
  } else {
    return true
  }
}

export { checkScope }
