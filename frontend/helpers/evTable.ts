import { useDebounceFn } from '@/composables/useDebouncedRef'

const route = useRoute()

export const handleEvTable = async (
  q: string | any,
  val?: number | string | null,
) => {
  if (typeof q === 'string') {
    await navigateTo({
      path: route.path,
      query: { ...route.query, [q]: val },
    })
  } else if (typeof q === 'object') {
    await navigateTo({
      path: route.path,
      query: { ...route.query, ...q },
    })
  }
}

export const handleSearch = useDebounceFn(async (type: string, val: any) => {
  handleEvTable(type, val)
}, 400)

export const handleSortChange = async (state: any, val: any) => {
  console.log('handleSortChange', val)
  state.sort = val.order
    ? (val.order.startsWith('asc') ? '' : '-') + val.prop
    : null

  handleEvTable('sort', state.sort)
}

export const syncStateFilter = async (route: any, state: any, filter?: any) => {
  if (Object.keys(route.query).length === 0) {
    for (const key in state) {
      if (key.startsWith('filter')) {
        state[key] = null

        // Sync Filter
        if (filter) {
          filter.value[key] = state[key]
        }
      }
    }
  } else {
    Object.assign(state, {
      ...route.query,
      page: Number(route.query.page) || state.page,
      rows: Number(route.query.rows) || state.rows,
    })

    // Handle default sort
    if (route.query.sort) {
      let prop =
        typeof route.query.sort === 'string'
          ? route.query.sort.replace(/^-/, '')
          : ''
      let order =
        typeof route.query.sort === 'string'
          ? route.query.sort.startsWith('-')
            ? 'descending'
            : 'ascending'
          : ''

      state.defaultSort = {
        order: order,
        prop: prop,
      }
    }

    // Sync Filter
    if (filter) {
      for (const key in state) {
        if (key.startsWith('filter')) {
          filter.value[key] = state[key]
        }
      }
    }
  }
}

export const isSameFilter = (q: Object, route: any) => {
  return Object.keys(q).every((key) => q[key] === route.query[key])
}
