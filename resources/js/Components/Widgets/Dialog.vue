<script setup>
import { defineProps, defineEmits, computed } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  closable: {
    type: Boolean,
    default: true,
  },
  maxWidth: {
    type: String,
    default: "md", // sm, md, lg, xl, 2xl
  },
});

const emit = defineEmits(["update:modelValue"]);

function close() {
  emit("update:modelValue", false);
}

// Classe Tailwind pour la largeur max
const maxWidthClass = computed(() => {
  const sizes = {
    sm: "max-w-sm",
    md: "max-w-md",
    lg: "max-w-lg",
    xl: "max-w-xl",
    "2xl": "max-w-2xl",
  };
  return sizes[props.maxWidth] || "max-w-md";
});
</script>


<template>
  <div 
    v-if="modelValue" 
    class="fixed inset-0 z-50 flex items-center justify-center bg-black dark:bg-gray-900 bg-opacity-50"
    @click.self="closable ? close() : null"
  >
    <div 
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full p-6 dark:border dark:border-gray-700"
      :class="maxWidthClass"
    >
      <!-- Header -->
      <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
          <slot name="title">{{ $t('widgets.dialog.default_title') }}</slot>
        </h2>
        <button 
          v-if="closable" 
          @click="close" 
          class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
        >
          ✕
        </button>
      </div>

      <!-- Body -->
      <div class="mb-4 text-gray-800 dark:text-gray-200">
        <slot>
          {{ $t('widgets.dialog.default_content') }}
        </slot>
      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-2">
        <slot name="footer">
          <button 
            v-if="closable"
            @click="close" 
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200"
          >
            {{ $t('widgets.dialog.close_button') }}
          </button>
        </slot>
      </div>
    </div>
  </div>
</template>
