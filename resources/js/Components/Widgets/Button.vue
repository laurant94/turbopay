<script setup>
const props = defineProps({
  type: {
    type: String,
    default: "button"
  },
  variant: {
    type: String,
    default: "default", // default | outline | danger | success ...
  },
  size: {
    type: String,
    default: "md", // sm | md | lg | compact
  }
})

const variantClasses = {
  default: "bg-primary-500 hover:bg-primary-700 text-white",
  outline: "border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white bg-transparent dark:text-primary-400 dark:border-primary-400",
  danger: "bg-red-500 hover:bg-red-700 text-white",
  success: "bg-green-500 hover:bg-green-700 text-white",
  secondary: "bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200"
}

const sizeClasses = {
  sm: "py-1 px-3 text-sm",
  md: "py-2 px-4 text-base",
  lg: "py-3 px-6 text-lg",
  compact: "py-1 px-2 text-sm rounded-sm"
}
</script>

<template>
  <button
    :type="type"
    :class="[
      'font-bold rounded focus:outline-none focus:shadow-outline transition-all duration-200',
      variantClasses[variant],
      sizeClasses[size]
    ]"
  >
    <slot />
  </button>
</template>

<style scoped>
/* Optionnel : tu peux ajouter des transitions globales */
</style>
