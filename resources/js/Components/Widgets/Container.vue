<script setup>

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    elevation: {
        type: Number,
        default: 1
    }
})

const elevationShadow = computed(()=>{
    const baseClasses = [
        '',
        'shadow-sm',
        'shadow-md',
        'shadow-lg',
        'shadow-xl',
        'shadow-2xl',
    ];

    return baseClasses[props.elevation] ?? 0;
})

</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg p-6"
                :class="[
                    elevationShadow
                ]"
            >
            
                <div class="flex justify-between">
                    <h3 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ title }}</h3>
                    <slot name="title" v-if="$slots.title" />

                    <div class="flex justify-end">
                        <slot name="actions" />
                    </div>  
                </div>

                <div v-if="$page.props.flash.success" class="bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 dark:border-green-700 text-green-700 dark:text-green-300 p-4 mb-4" role="alert">
                    <p>{{ $page.props.flash.success }}</p>
                </div>
                <div v-if="$page.props.flash.error" class="bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 dark:border-red-700 text-red-700 dark:text-red-300 p-4 mb-4" role="alert">
                    <p>{{ $page.props.flash.error }}</p>
                </div>
                
                <div>
                    <slot />
                </div>


            </div>
        </div>
    </div>
</template>