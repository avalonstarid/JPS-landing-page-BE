import type { Sort } from 'element-plus'

export interface ApiResponse<T> {
  data: T
  errors: string | string[] | Record<string, any> | null
  from: number
  message: string
  success: boolean
  total: number
}

export interface IState {
  defaultSort?: Sort
  include?: string
  page: number
  rows: number
  sort: string | null
  [key: string]: any // Allow dynamic keys for filters
}

export interface IMultiLang {
  en: string
  id: string
}
