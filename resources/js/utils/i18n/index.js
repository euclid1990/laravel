import Vue from 'vue';
import VueI18n from 'vue-i18n'
import Formatter from '@/utils/i18n/formatter'
import axios from 'axios'

Vue.use(VueI18n)

const messages = require(`@/langs/${window.config.lang}`).default

const loadedLanguages = []
const locale = window.config.lang
const formatter = new Formatter({ locale })

const i18n = new VueI18n({
  locale,
  formatter,
  messages,
  silentTranslationWarn: false
})

loadedLanguages.push(locale)

function setI18nLanguage (lang) {
  i18n.locale = lang
  axios.defaults.headers.common['Accept-Language'] = lang
  document.querySelector('html').setAttribute('lang', lang)

  return lang
}

const $t = i18n.tc.bind(i18n)

function loadLanguageAsync (lang) {
  if (i18n.locale !== lang) {
    if (!loadedLanguages.includes(lang)) {
      return import(/* webpackChunkName: "lang-[request]" */ `@/langs/${lang}`).then(msgs => {
        i18n.setLocaleMessage(lang, msgs.default)
        loadedLanguages.push(lang)

        return setI18nLanguage(lang)
      })
    }

    return Promise.resolve(setI18nLanguage(lang))
  }

  return Promise.resolve(lang)
}

export {
  i18n,
  $t,
  loadLanguageAsync
}
