<template>
    <header class="fixed top-0 w-full bg-white/95 dark:bg-gray-900/95 shadow-sm z-50 backdrop-blur-md">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center">
                    <img src="/images/primary-logo.png" alt="Syaliarisa Services" class="h-16" />
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
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2 opacity-0 invisible
                transition-all duration-200 transform -translate-y-1
                group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">
                            <a href="/about-us/organization"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Organization</a>
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
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2 opacity-0 invisible
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
                <button @click="toggleMobileMenu" class="lg:hidden text-gray-800 dark:text-white text-2xl">
                    <i :class="mobileMenuOpen ? 'bi bi-x' : 'bi bi-list'"></i>
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
                            <a href="#"
                                class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Client List</a>
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
};

const toggleDropdown = (menu) => {
    openDropdown.value = openDropdown.value === menu ? '' : menu;
};
</script>
