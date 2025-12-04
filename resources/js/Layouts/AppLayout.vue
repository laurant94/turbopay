<script setup>
import { ref, computed, onMounted, onUpdated } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Dropdown as VDropdown } from 'floating-vue';
import { useAlertStore } from '@/Stores/AlertStore';
import Alert from '@/Components/Widgets/Alert.vue';
import { Moon, Sun } from 'lucide-vue-next';

// Define props
defineProps({
    title: String,
});

// Stores and reactive variables
const alertStore = useAlertStore();
const page = usePage();
const showingSidebar = ref(false);

// Computed properties
const user = computed(() => page.props.auth.user);
const userHospitals = computed(() => page.props.user_hospitals || []);
const currentHospital = computed(() => {
    const hospitalId = page.props.current_hospital?.id;
    if (!hospitalId || !userHospitals.value) return null;
    return userHospitals.value.find(h => h.id == hospitalId);
});
const mainMenu = computed(() => {
    const categorizedMenuItems = page.props.main_menu || {};
    const processedMenu = {};

    for (const category in categorizedMenuItems) {
        if (categorizedMenuItems.hasOwnProperty(category)) {
            processedMenu[category] = categorizedMenuItems[category].map(item => {
                let isActive = route().current(item.route);

                if (!isActive && item.route) {
                    const routeParts = item.route.split('.');
                    if (routeParts.length > 1) {
                        const baseRoutePattern = routeParts.slice(0, -1).join('.') + '.*';
                        isActive = route().current(baseRoutePattern);
                    }
                }
                return {
                    ...item,
                    isActive: isActive,
                };
            });
        }
    }
    return processedMenu;
});

// Methods
const toggleSidebar = () => {
    showingSidebar.value = !showingSidebar.value;
};

const getHospitalRoute = (hospitalId) => {
    try {
        return route(route().current(), { ...route().params, hospital: hospitalId });
    } catch (e) {
        return route('hospital.dashboard', { hospital: hospitalId });
    }
};

const logout = () => {
    router.post(route('logout'));
};

const updateFlashMessages = () => {
    const flash = page.props.flash;
    let message = null;
    let type = 'info';

    if (flash.success) {
        message = flash.success;
        type = 'success';
    } else if (flash.error) {
        message = flash.error;
        type = 'error';
    } else if (flash.warning) {
        message = flash.warning;
        type = 'warning';
    } else if (flash.info) {
        message = flash.info;
        type = 'info';
    }

    if (message) {
        alertStore.setMessage({ message, type });
        setTimeout(() => alertStore.clearMessage(), 5000);
    }
};

// Lifecycle hooks
onMounted(updateFlashMessages);
onUpdated(updateFlashMessages);

// Theme Toggling
const toggleTheme = () => {
    if (window.ThemeManager) {
        window.ThemeManager.toggleTheme();
    }
};
</script>

<template>
    <Head :title="title" />

    <!-- Alert component for flash messages -->
    <div class="fixed top-5 right-5 z-[100]">
        <Alert v-if="alertStore.getMessage" :message="alertStore.getMessage" />
    </div>


    <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside
            :class="showingSidebar ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-gray-200 dark:border-gray-700">
                <Link :href="route('dashboard')" class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $page.props.app_name }}
                </Link>
            </div>

            <!-- Hospital Switcher -->
            <div v-if="currentHospital" class="p-4 border-b border-gray-200 dark:border-gray-700">
                <VDropdown :distance="10" placement="bottom-start">
                    <button class="w-full flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-700 rounded-md focus:outline-none">
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ currentHospital.name }}</span>
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </button>
                    <template #popper>
                        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-md shadow-xl text-sm w-56">
                            <Link
                                v-for="hospital in userHospitals"
                                :key="hospital.id"
                                :href="getHospitalRoute(hospital.id)"
                                class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                {{ hospital.name }}
                            </Link>
                        </div>
                    </template>
                </VDropdown>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto hide-scrollbar">
                <div v-for="(categoryItems, categoryName) in mainMenu" :key="categoryName" class="mb-4">
                    <h3 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                        {{ categoryName }}
                    </h3>
                    <Link
                        v-for="item in categoryItems"
                        :key="item.route"
                        :href="route(item.route, item.params)"
                        :class="[
                            'flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors',
                            item.isActive
                                ? 'bg-primary-500 text-white'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                        ]"
                    >
                        <!-- You can add icons here based on item.label or a new 'icon' property -->
                        <span>{{ item.label }}</span>
                    </Link>
                </div>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between h-16 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <!-- Mobile Menu Button -->
                <button @click="toggleSidebar" class="md:hidden text-gray-600 dark:text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                </button>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                        <Moon class="h-6 w-6 dark:hidden"  />
                        <Sun class="h-6 w-6 hidden dark:block" />
                       
                    </button>

                    <!-- User Dropdown -->
                    <VDropdown :distance="10" placement="bottom-end">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <img class="h-9 w-9 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name">
                            <span class="hidden sm:inline text-sm font-medium text-gray-700 dark:text-gray-300">{{ user.name }}</span>
                        </button>
                        <template #popper>
                            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-md shadow-xl text-sm w-48">
                                <div class="px-4 py-3 border-b dark:border-gray-700">
                                    <p class="font-semibold text-gray-800 dark:text-white">{{ user.name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ user.email }}</p>
                                </div>
                                <Link :href="route('profile.show')" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $t('layout.user_dropdown.profile_link') }}</Link>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                <form @submit.prevent="logout">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        {{ $t('layout.user_dropdown.logout_button') }}
                                    </button>

                                </form>
                            </div>
                        </template>
                    </VDropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <div class="container mx-auto">
                    <!-- Page Header -->
                    <div v-if="$slots.header" class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                            <slot name="header" />
                        </h1>
                    </div>

                    <!-- Main Slot -->
                    <slot />
                </div>
            </main>
        </div>

        <!-- Mobile Sidebar Backdrop -->
        <div v-if="showingSidebar" @click="toggleSidebar" class="fixed inset-0 z-40 bg-black opacity-50 md:hidden"></div>
    </div>
</template>

<style>
/* Floating Vue Custom Theme */
.v-popper--theme-dropdown .v-popper__inner {
    background: #fff;
    color: black;
    border-radius: 0.375rem; /* 6px */
    border: 1px solid #e5e7eb; /* gray-200 */
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
.dark .v-popper--theme-dropdown .v-popper__inner {
    background: #1f2937; /* gray-800 */
    color: white;
    border-color: #374151; /* gray-700 */
}
.v-popper--theme-dropdown .v-popper__arrow-outer,
.v-popper--theme-dropdown .v-popper__arrow-inner {
    border-color: #fff;
}
.dark .v-popper--theme-dropdown .v-popper__arrow-outer,
.dark .v-popper--theme-dropdown .v-popper__arrow-inner {
    border-color: #1f2937; /* gray-800 */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.hide-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>