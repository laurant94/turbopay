import { useTimeAgoIntl } from "@vueuse/core";


export const asset = (path)=>{
  return import.meta.env.VITE_APP_URL +'/' + path;
}

// Messages FR pour useTimeAgo
const timeAgoMessagesFr = {
  justNow: 'à l’instant',
  past: n => `il y a ${n}`,
  future: n => `dans ${n}`,
  month: ['mois', 'mois'],
  year: ['an', 'ans'],
  day: ['jour', 'jours'],
  hour: ['heure', 'heures'],
  minute: ['minute', 'minutes'],
  second: ['seconde', 'secondes'],
}

export function intelligentDate(
    inputDate,
    {
        locale = 'fr',
        mode = 'date',
        timezone = Intl.DateTimeFormat().resolvedOptions().timeZone,
    } = {}
) {
    if (!inputDate) return ''

    const date = new Date(inputDate)
    if (isNaN(date.getTime())) return ''

    // MODE: RELATIVE
    if (mode === 'relative') {
        return useTimeAgo(date, {
            locale,
            messages: locale === 'fr' ? timeAgoMessagesFr : undefined
        }).value
    }

    // Other modes: Intl.DateTimeFormat
    const options = {}

    switch (mode) {
        case 'datetime':
            options.year = 'numeric'
            options.month = '2-digit'
            options.day = '2-digit'
            options.hour = '2-digit'
            options.minute = '2-digit'
            break

        case 'time':
            options.hour = '2-digit'
            options.minute = '2-digit'
            break

        case 'date':
        default:
            options.year = 'numeric'
            options.month = '2-digit'
            options.day = '2-digit'
            break
    }

    options.timeZone = timezone

    return new Intl.DateTimeFormat(locale, options).format(date)
}

export function formatDate(
    inputDate,
    {
      locale = navigator.language || 'fr',
      mode = 'date',
      timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
    } = {}
) {
    if (!inputDate) return ''

    let date = new Date(inputDate)
    if (isNaN(date.getTime())) return '' // fallback si mauvaise date

    // RELATIVE TIME ("il y a 3 heures")
    if (mode === 'relative') {
        return useTimeAgoIntl(date, { locale }).value
    }

    const options = {}

    switch (mode) {
        case 'datetime':
            options.year = 'numeric'
            options.month = '2-digit'
            options.day = '2-digit'
            options.hour = '2-digit'
            options.minute = '2-digit'
            break

        case 'time':
            options.hour = '2-digit'
            options.minute = '2-digit'
            break

        case 'date':
        default:
            options.year = 'numeric'
            options.month = '2-digit'
            options.day = '2-digit'
            break
    }

    options.timeZone = timezone

    return new Intl.DateTimeFormat(locale, options).format(date)
}