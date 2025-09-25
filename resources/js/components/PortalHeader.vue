<template>
    <header class="fixed top-0 w-full bg-white dark:bg-background/80 shadow-sm z-50" data-aos="fade-down">
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
                    <!-- <a href="/company-info"
                        class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Company
                        Info</a> -->

                    <!-- About Us Dropdown -->
                    <div class="relative group inline-block dropdown-container"
                        @mouseenter="handleDropdownHover('about')" @mouseleave="handleDropdownLeave"
                        @click="toggleDropdown('about')">
                        <!-- Button -->
                        <button
                            class="flex items-center gap-1 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium transition-colors duration-200">
                            About Us
                            <!-- Arrow SVG -->
                            <svg class="w-5 h-5 text-gray-800 group-hover:text-blue-600 dark:text-white transform transition-transform duration-300"
                                :class="{ 'rotate-180': openDropdown === 'about' || activeDropdown === 'about' }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-background shadow-lg rounded-md py-2 border transition-all duration-400 ease-out"
                            :class="{
                                'opacity-100 visible translate-y-0': openDropdown === 'about' || activeDropdown === 'about',
                                'opacity-0 invisible -translate-y-2': openDropdown !== 'about' && activeDropdown !== 'about'
                            }">
                            <a href="/about-us/company-info"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">Company
                                Info</a>
                            <a href="/about-us/standards"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">Standards</a>
                            <a href="/about-us/legislation"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">Legislations</a>
                        </div>
                    </div>

                    <!-- Services Dropdown -->
                    <div class="relative group inline-block dropdown-container"
                        @mouseenter="handleDropdownHover('services')" @mouseleave="handleDropdownLeave"
                        @click="toggleDropdown('services')">
                        <button
                            class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium flex items-center gap-1">
                            Services
                            <!-- Arrow SVG -->
                            <svg class="w-5 h-5 text-gray-800 group-hover:text-blue-600 dark:text-white transform transition-transform duration-300"
                                :class="{ 'rotate-180': openDropdown === 'services' || activeDropdown === 'services' }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white dark:bg-background shadow-lg rounded-md py-2 opacity-0 invisible border transition-all duration-400 ease-out"
                            :class="{
                                'opacity-100 visible translate-y-0': openDropdown === 'services' || activeDropdown === 'services',
                                'opacity-0 invisible -translate-y-2': openDropdown !== 'services' && activeDropdown !== 'services'
                            }">
                            <a href="/services/health-safety"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Health
                                & Safety</a>
                            <a href="/services/training"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Training</a>
                            <a href="/services/engineering"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Engineering</a>
                            <a href="/services/environmental"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Environmental</a>
                        </div>
                    </div>

                    <a href="/contact-us"
                        class="text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">Contact
                        Us</a>
                </div>

                <!-- Mobile Toggle -->
                <!-- Mobile Toggle Button - Made more prominent -->
                <button @click="toggleMobileMenu" class="lg:hidden p-2 focus:outline-none mobile-toggle-button"
                    aria-label="Toggle menu">
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
            <div v-if="mobileMenuOpen" class="lg:hidden pt-4 mobile-menu-container">
                <div class="flex flex-col space-y-2">

                    <!-- Home -->
                    <a href="/"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">
                        Home
                    </a>

                    <!-- About Us Dropdown (toggle on mobile) -->
                    <div>
                        <button @click="toggleDropdown('about')"
                            class="flex justify-between items-center w-full py-2 text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            About Us
                            <svg class="w-5 h-5 transform transition-transform duration-300"
                                :class="openDropdown === 'about' ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-gray-800 dark:text-gray-100'"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Transition wrapper -->
                        <transition enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                            <div v-if="openDropdown === 'about'" class="pl-4">
                                <a href="/about-us/company-info"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Company
                                    Info</a>
                                <a href="/about-us/standards"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Standards</a>
                                <a href="/about-us/legislation"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Legislation</a>
                            </div>
                        </transition>
                    </div>

                    <!-- Services Dropdown (toggle on mobile) -->
                    <div>
                        <button @click="toggleDropdown('services')"
                            class="flex justify-between items-center w-full py-2 text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            Services
                            <svg class="w-5 h-5 transform transition-transform duration-300" :class="openDropdown === 'services'
                                ? 'rotate-180 text-blue-600 dark:text-blue-400'
                                : 'rotate-0 text-gray-800 dark:text-gray-100'" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Animated Dropdown -->
                        <transition enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                            <div v-if="openDropdown === 'services'" class="pl-4 overflow-hidden">
                                <a href="/services/health-safety"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Health &
                                    Safety</a>
                                <a href="/services/training"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Training</a>
                                <a href="/services/engineering"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Engineering</a>
                                <a href="/services/environmental"
                                    class="block py-2 text-gray-700 dark:text-gray-200 hover:text-primary">Environmental</a>
                            </div>
                        </transition>
                    </div>

                    <!-- Contact -->
                    <a href="/contact-us"
                        class="block py-2 text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-400 font-medium">
                        Contact Us
                    </a>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const mobileMenuOpen = ref(false);
const openDropdown = ref('');
const activeDropdown = ref('');

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    openDropdown.value = '';
    document.body.style.overflow = mobileMenuOpen.value ? 'hidden' : '';
};

const toggleDropdown = (menu) => {
    openDropdown.value = openDropdown.value === menu ? '' : menu;
};

const handleDropdownHover = (menu) => {
    activeDropdown.value = menu;
};

const handleDropdownLeave = () => {
    activeDropdown.value = '';
};

const handleClickOutside = (event) => {
    const dropdowns = document.querySelectorAll('.dropdown-container');
    const isClickInsideDropdown = Array.from(dropdowns).some(dropdown => dropdown.contains(event.target));

    const mobileMenu = document.querySelector('.mobile-menu-container');
    const mobileToggle = document.querySelector('.mobile-toggle-button');
    const isClickInsideMobileMenu = mobileMenu && mobileMenu.contains(event.target);
    const isClickOnToggle = mobileToggle && mobileToggle.contains(event.target);

    if (window.innerWidth >= 1024) {
        // Close dropdowns on desktop sizes
        if (!isClickInsideDropdown) {
            openDropdown.value = '';
        }
    } else {
        // Close mobile menu on mobile sizes if clicking outside the menu and toggle button
        if (!isClickInsideMobileMenu && !isClickOnToggle) {
            mobileMenuOpen.value = false;
            document.body.style.overflow = '';
        }
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
