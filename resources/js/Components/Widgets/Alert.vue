<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  type: {
    type: String,
    default: "info", // success | error | warning | info
  },
  message: {
    type: String,
    default: "",
  },
  closable: {
    type: Boolean,
    default: true,
  },
});

const visible = ref(true);

const colors = {
  success: "bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 border-green-300 dark:border-green-700",
  error: "bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 border-red-300 dark:border-red-700",
  warning: "bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 border-yellow-300 dark:border-yellow-700",
  info: "bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 border-blue-300 dark:border-blue-700",
};

const icons = {
  success: "✅",
  error: "❌",
  warning: "⚠️",
  info: "ℹ️",
};

const alertClass = computed(() => colors[props.type] || colors.info);
const alertIcon = computed(() => icons[props.type] || icons.info);

function close() {
  visible.value = false;
}
</script>

<template>
  <transition name="fade">
    <div
      v-if="visible"
      :class="['flex items-start p-4 rounded-md border text-sm shadow-sm', alertClass]"
    >
      <span class="text-lg mr-3">{{ alertIcon }}</span>
      <div class="flex-1">
        <slot>
          {{ message }}
        </slot>
      </div>
      <button
        v-if="closable"
        @click="close"
        class="ml-3 text-xl leading-none focus:outline-none hover:opacity-70"
        :aria-label="$t('widgets.alert.close_button_aria_label')"
      >
        &times;
      </button>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
