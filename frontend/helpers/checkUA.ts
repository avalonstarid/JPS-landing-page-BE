import UAParser from 'ua-parser-js'

export const checkUA = (ua: string) => {
  const parser = new UAParser()
  parser.setUA(ua)
  return parser.getResult()
}

/*
import { checkUA } from '@/helpers/checkUA'
const userA = checkUA('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36')
*/
