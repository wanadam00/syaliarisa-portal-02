<template>
    <footer class="pt-16 border-t">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Column 1: Company Info -->
                <div>
                    <a href="/" class="flex items-center mb-6">
                        <!-- Light mode logo (shown by default) -->
                        <img src="/images/primary-logo.png" alt="Syaliarisa Services" class="h-16 block dark:hidden" />

                        <!-- Dark mode logo (hidden by default, shown in dark mode) -->
                        <img src="/images/secondary-logo.png" alt="Syaliarisa Services"
                            class="h-16 hidden dark:block" />
                    </a>
                    <p class="mb-6 text-gray-300">
                        Syaliarisa Services Sdn. Bhd. is an environmental, industrial hygiene and safety consulting,
                        monitoring & training firm operating for Malaysia and the regions.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-gray-700 hover:bg-primary rounded-full flex items-center justify-center transition">
                            <i class="bi bi-facebook text-white"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-700 hover:bg-primary rounded-full flex items-center justify-center transition">
                            <i class="bi bi-linkedin text-white"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-700 hover:bg-primary rounded-full flex items-center justify-center transition">
                            <i class="bi bi-twitter-x text-white"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-700 hover:bg-primary rounded-full flex items-center justify-center transition">
                            <i class="bi bi-youtube text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-xl font-semibold text-white mb-6">Quick Links</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><a href="/" class="hover:text-primary">Home</a></li>
                        <li><a href="/company-info" class="hover:text-primary">Company Info</a></li>
                        <li><a href="/our-brand" class="hover:text-primary">Our Brand</a></li>
                        <li><a href="/store-location" class="hover:text-primary">Our Location</a></li>
                        <li><a href="/contact-us" class="hover:text-primary">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Column 3: Services -->
                <div>
                    <h3 class="text-xl font-semibold text-white mb-6">Services</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li><a href="/services/health-safety" class="hover:text-primary">Health & Safety</a></li>
                        <li><a href="/services/training" class="hover:text-primary">Training</a></li>
                        <li><a href="/services/engineering" class="hover:text-primary">Engineering</a></li>
                        <li><a href="/services/environmental" class="hover:text-primary">Environmental</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Info -->
                <div>
                    <h3 class="text-xl font-semibold text-white mb-6">Contact Info</h3>
                    <ul class="space-y-4 text-gray-300">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt text-xl"></i>
                            <span>123 Business Avenue, Kuala Lumpur, Malaysia</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-telephone text-xl"></i>
                            <span>+603 1234 5678</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-envelope text-xl"></i>
                            <span>info@syaliarisa.com</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-clock text-xl"></i>
                            <span>Mon-Fri: 9:00 AM - 6:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer bottom -->
            <div class="border-t border-gray-700 mt-12 pt-8 pb-6 text-sm text-gray-400">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p>&copy; {{ new Date().getFullYear() }} Syaliarisa Services Sdn Bhd. All rights reserved.</p>
                    <p>201803358725 (SA0496979-V)</p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
// No additional logic needed
</script>
