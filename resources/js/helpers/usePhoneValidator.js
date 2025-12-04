import { ref, computed } from 'vue'

// table des préfixes par pays / réseau
// exemple Togo 8 chiffres
const RULES = {
  mtnbenin:    { prefix: ['0190', '0196', '0197', '0166', '0154'], length: 8 },
  moovbenin:   { prefix: ['0196', '0197'],                   length: 8 },
  orange: { prefix: ['95', '98'],                   length: 8 },
}

const clean = (v) => String(v || '').replace(/\D/g, '')

export default function usePhoneValidator() {
  const raw = ref('')

  const valid = computed(() => !error.value)
  const error = computed(() => {
    const num = clean(raw.value)
    if (!num) return 'Numéro requis'
    const net = network.value
    if (!net) return 'Préfixe inconnu'
    if (num.length !== RULES[net].length) return `Le numéro ${net.toUpperCase()} doit faire ${RULES[net].length} chiffres`
    return null
  })

  const network = computed(() => {
    const num = clean(raw.value)
    for (const [net, { prefix }] of Object.entries(RULES)) {
      if (prefix.some(p => num.startsWith(p))) return net
    }
    return null
  })

  return { raw, valid, network, error }
}