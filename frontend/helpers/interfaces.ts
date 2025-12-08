import type { Sort } from 'element-plus'

export interface IState {
  defaultSort?: Sort
  include?: string
  page: number
  rows: number
  sort: string | null
  [key: string]: any // Allow dynamic keys for filters
}
