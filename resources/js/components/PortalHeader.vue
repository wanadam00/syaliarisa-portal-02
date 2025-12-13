<template>
    <header :class="[
        'fixed top-0 w-full shadow-md z-[9999] transition-colors duration-500',
        scrolled
            ? 'bg-gradient-to-r from-[#133762] to-[#2262AE] opacity-100'
            : 'bg-gradient-to-r from-[#133762]/85 to-[#2262AE]/85 opacity-90'
    ]" data-aos="fade-down">
        <nav class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center" @click.prevent="handleLogoClick"
                    @touchstart="handleLogoPressStart" @touchend="handleLogoPressEnd" @contextmenu.prevent>
                    <!-- Light mode logo (shown by default) -->
                    <img src="/images/crop-logo-2.png" alt="Syaliarisa Services" class="h-14 block dark:hidden pb-1" />
                    <span
                        class="block dark:hidden mx-4 text-white font-bold text-xl uppercase drop-shadow-lg drop-shadow-gray-900 font-mono">Syaliarisa</span>

                    <!-- Dark mode logo (hidden by default, shown in dark mode) -->
                    <img src="/images/secondary-logo.png" alt="Syaliarisa Services" class="h-14 hidden dark:block" />
                    <span
                        class="hidden dark:block mx-4 text-white font-bold text-xl uppercase drop-shadow-lg drop-shadow-gray-900 font-mono">Syaliarisa</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="/"
                        class="text-white hover:text-gray-700 font-medium transition-colors duration-300">Home</a>

                    <!-- About Us Dropdown -->
                    <div class="relative group inline-block dropdown-container"
                        @mouseenter="handleDropdownHover('about')" @mouseleave="handleDropdownLeave"
                        @click="toggleDropdown('about')">
                        <!-- Button -->
                        <button
                            class="flex items-center gap-1 text-white hover:text-gray-700 font-medium transition-colors duration-300">
                            About Us
                            <!-- Arrow SVG -->
                            <svg class="w-5 h-5 text-white group-hover:text-gray-700 transform transition-transform duration-300"
                                :class="{ 'rotate-180': openDropdown === 'about' || activeDropdown === 'about' }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 border transition-all duration-300 ease-out"
                            :class="{
                                'opacity-100 visible translate-y-0': openDropdown === 'about' || activeDropdown === 'about',
                                'opacity-100 invisible -translate-y-2': openDropdown !== 'about' && activeDropdown !== 'about'
                            }">
                            <a href="/about-us/company-info"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">Company
                                Info</a>
                            <a href="/about-us/standards"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">Regulatory & Industry Standards</a>
                            <a href="/about-us/legislation"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">Guidelines & References</a>
                        </div>
                    </div>

                    <!-- Services Dropdown -->
                    <div class="relative group inline-block dropdown-container"
                        @mouseenter="handleDropdownHover('services')" @mouseleave="handleDropdownLeave"
                        @click="toggleDropdown('services')">
                        <button
                            class="flex items-center gap-1 text-white hover:text-gray-700 font-medium transition-colors duration-300">
                            Services
                            <!-- Arrow SVG -->
                            <svg class="w-5 h-5 text-white group-hover:text-gray-700 transform transition-transform duration-300"
                                :class="{ 'rotate-180': openDropdown === 'services' || activeDropdown === 'services' }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 border transition-all duration-300 ease-out"
                            :class="{
                                'opacity-100 visible translate-y-0': openDropdown === 'services' || activeDropdown === 'services',
                                'opacity-100 invisible -translate-y-2': openDropdown !== 'services' && activeDropdown !== 'services'
                            }">
                            <a href="/services/safety-health"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                Occupational Safety & Health Monitoring
                            </a>
                            <a href="/services/environmental"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                Environmental Monitoring
                            </a>
                            <a href="/services/training"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                                Training Academy
                            </a>
                        </div>
                    </div>

                    <a href="/contact-us"
                        class="text-white hover:text-gray-700 font-medium transition-colors duration-300">Contact
                        Us</a>

                    <!-- Even more hidden admin login link -->
                    <a href="/login"
                        class="admin-login-link opacity-0 hover:opacity-100 focus:opacity-100 transition-all duration-300 ease-in-out w-0 hover:w-8 h-8 flex items-center justify-center text-white hover:text-blue-200 border border-transparent hover:border-white/20 rounded bg-white/5 hover:bg-white/10 overflow-hidden"
                        title="Admin Login" aria-label="Admin Login">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 min-w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <!-- Mobile Toggle -->
                <button @click="toggleMobileMenu" class="lg:hidden p-2 focus:outline-none mobile-toggle-button"
                    aria-label="Toggle menu">
                    <div class="w-6 h-6 relative">
                        <!-- Top bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-100 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'transform rotate-45 translate-y-1.5': mobileMenuOpen, 'top-0': !mobileMenuOpen }"></span>
                        <!-- Middle bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-100 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'opacity-0': mobileMenuOpen, 'top-1.5': !mobileMenuOpen }"></span>
                        <!-- Bottom bar -->
                        <span
                            class="block absolute h-0.5 w-6 bg-gray-100 dark:bg-white transition-all duration-300 ease-out"
                            :class="{ 'transform -rotate-45 translate-y-1.5': mobileMenuOpen, 'top-3': !mobileMenuOpen }"></span>
                    </div>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="lg:hidden pt-4 mobile-menu-container">
                <div class="flex flex-col space-y-2">

                    <!-- Home -->
                    <a href="/" class="block py-2 text-white hover:text-gray-700 font-medium">
                        Home
                    </a>

                    <!-- About Us Dropdown (toggle on mobile) -->
                    <div>
                        <button @click="toggleDropdown('about')"
                            class="flex justify-between items-center w-full py-2 text-white hover:text-gray-700 font-medium transition-colors duration-300">
                            About Us
                            <svg class="w-5 h-5 text-white group-hover:text-gray-700 transform transition-transform duration-300"
                                :class="openDropdown === 'about' ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-gray-700 dark:text-gray-100'"
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
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Company
                                    Info</a>
                                <a href="/about-us/standards"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Regulatory & Industry Standards</a>
                                <a href="/about-us/legislation"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Guidelines & References</a>
                            </div>
                        </transition>
                    </div>

                    <!-- Services Dropdown (toggle on mobile) -->
                    <div>
                        <button @click="toggleDropdown('services')"
                            class="flex justify-between items-center w-full py-2 text-white hover:text-gray-700 font-medium transition-colors duration-300">
                            Services
                            <svg class="w-5 h-5 text-white group-hover:text-gray-700 transform transition-transform duration-300"
                                :class="openDropdown === 'services'
                                    ? 'rotate-180 text-blue-600 dark:text-blue-400'
                                    : 'rotate-0 text-gray-700 dark:text-gray-100'" xmlns="http://www.w3.org/2000/svg"
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
                                <a href="/services/safety-health"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Occupational
                                    Safety & Health Monitoring</a>
                                <a href="/services/environmental"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Environmental
                                    Monitoring</a>
                                <a href="/services/training"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Training
                                    Academy</a>
                                <!-- <a href="/services/engineering"
                                    class="block py-2 text-white dark:text-gray-200 hover:text-primary">Engineering</a> -->
                            </div>
                        </transition>
                    </div>

                    <!-- Contact -->
                    <a href="/contact-us" class="block py-2 text-white hover:text-gray-700 font-medium">
                        Contact Us
                    </a>

                    <!-- Hidden Admin Login in Mobile Menu -->
                    <!-- <a href="/login"
                        class="admin-login-link-mobile opacity-50 hover:opacity-100 transition-opacity duration-300 w-full text-left py-2 text-white hover:text-blue-200 text-sm flex items-center border-t border-white/10 mt-2 pt-3"
                        aria-label="Admin Login">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Admin Access
                    </a> -->
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
const scrolled = ref(false);

// Triple click detection for logo
const logoClickCount = ref(0);
const lastLogoClickTime = ref(0);

let logoPressTimer = null;
const LONG_PRESS_DURATION = 800; // 800 milliseconds is a good standard long-press duration

const handleLogoPressStart = (event) => {
    // Only run this logic on touch devices or mobile view (if needed)
    // Clear any existing timer just in case
    clearTimeout(logoPressTimer);

    logoPressTimer = setTimeout(() => {
        // Long press detected!
        event.preventDefault();
        window.location.href = '/login';
    }, LONG_PRESS_DURATION);
};

const handleLogoPressEnd = () => {
    // Clear the timer if the touch/mouse is released early (before long press)
    clearTimeout(logoPressTimer);
};

// Modify handleLogoClick to only run the triple-click logic on non-mobile/mouse devices
const handleLogoClick = (event) => {
    // Use the triple-click only if it wasn't a long press event
    // or if you want to keep triple-click for non-touch devices
    if (logoPressTimer !== null) {
        clearTimeout(logoPressTimer);
        logoPressTimer = null;
        return; // Long press handled the action
    }

    // Original Triple-Click Logic (for desktop/mouse users)
    const currentTime = new Date().getTime();
    const clickGap = currentTime - lastLogoClickTime.value;

    if (clickGap > 300) {
        logoClickCount.value = 1;
    } else {
        logoClickCount.value++;
    }

    lastLogoClickTime.value = currentTime;

    if (logoClickCount.value === 3) {
        event.preventDefault();
        window.location.href = '/login';
        return;
    }
};

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
        if (!isClickInsideDropdown) {
            openDropdown.value = '';
        }
    } else {
        if (!isClickInsideMobileMenu && !isClickOnToggle) {
            mobileMenuOpen.value = false;
            document.body.style.overflow = '';
        }
    }
};

const handleScroll = () => {
    scrolled.value = window.scrollY > 50;
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('scroll', handleScroll);
});
</script>
