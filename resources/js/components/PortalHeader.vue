<template>
    <header class="fixed top-0 w-full bg-white dark:bg-background/80 shadow-sm z-50">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center">
                    <!-- Light mode logo (shown by default) -->
                    <img src="/images/primary-logo.png" alt="Syaliarisa Services" class="h-16 block dark:hidden" />

                    <!-- Dark mode logo (hidden by default, shown in dark mode) -->
                    <img src="/images/secondary-logo.png" alt="Syaliarisa Services" class="h-16 hidden dark:block" />
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="/"
                        class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Home</a>
                    <a href="/company-info"
                        class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Company
                        Info</a>
                    <!-- About Us Dropdown -->
                    <div class="relative group">
                        <button
                            class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium flex items-center">
                            About Us
                            <i
                                class="bi bi-chevron-down ml-1 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-background shadow-lg rounded-md py-2 opacity-0 invisible border
                transition-all duration-200 transform -translate-y-1
                group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                            <a href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Standards</a>
                            <a href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Legislation</a>
                        </div>
                    </div>

                    <!-- Services Dropdown -->
                    <div class="relative group">
                        <button
                            class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium flex items-center">
                            Services
                            <i
                                class="bi bi-chevron-down ml-1 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-background shadow-lg rounded-md py-2 opacity-0 invisible border
                transition-all duration-200 transform -translate-y-1
                group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                            <a href="/services/health-safety"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Health
                                & Safety</a>
                            <a href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Training</a>
                            <a href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Engineering</a>
                            <a href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Environmental</a>
                        </div>
                    </div>

                    <a href="/contact-us"
                        class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Contact
                        Us</a>
                </div>

                <!-- Mobile Toggle -->
                <!-- Mobile Toggle Button - Made more prominent -->
                <button @click="toggleMobileMenu" class="lg:hidden p-2 focus:outline-none" aria-label="Toggle menu">
                    <div class="w-6 h-6 relative">
                        <!-- Top bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-800 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'transform rotate-45 translate-y-1.5': mobileMenuOpen, 'top-0': !mobileMenuOpen }"></span>
                        <!-- Middle bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-800 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'opacity-0': mobileMenuOpen, 'top-1.5': !mobileMenuOpen }"></span>
                        <!-- Bottom bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-800 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'transform -rotate-45 translate-y-1.5': mobileMenuOpen, 'top-3': !mobileMenuOpen }"></span>
                    </div>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="lg:hidden pt-4">
                <div class="flex flex-col space-y-2">
                    <a href="/"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Home</a>
                    <a href="/company-info"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Company
                        Info</a>
                    <a href="/our-brand"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Our
                        Brand</a>
                    <a href="/store-location"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Our
                        Location</a>

                    <!-- Mobile Dropdowns -->
                    <div>
                        <button @click="toggleDropdown('about')"
                            class="flex justify-between w-full py-2 text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            About Us
                            <i :class="openDropdown === 'about' ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                        </button>
                        <div v-if="openDropdown === 'about'" class="pl-4">
                            <a href="/about-us/organization"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Organization</a>
                            <a href="#" class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Client
                                List</a>
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Standards</a>
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Legislation</a>
                        </div>
                    </div>

                    <div>
                        <button @click="toggleDropdown('services')"
                            class="flex justify-between w-full py-2 text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            Services
                            <i :class="openDropdown === 'services' ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                        </button>
                        <div v-if="openDropdown === 'services'" class="pl-4">
                            <a href="/services/health-safety"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Health &
                                Safety</a>
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Training</a>
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Engineering</a>
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Environmental</a>
                        </div>
                    </div>

                    <a href="/contact-us"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Contact
                        Us</a>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref } from 'vue';

const mobileMenuOpen = ref(false);
const openDropdown = ref('');

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    openDropdown.value = '';
    document.body.style.overflow = mobileMenuOpen.value ? 'hidden' : '';
};

const toggleDropdown = (menu) => {
    openDropdown.value = openDropdown.value === menu ? '' : menu;
};
</script>
