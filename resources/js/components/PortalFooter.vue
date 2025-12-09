<template>
    <footer class="pt-16 border-t bg-white dark:bg-gray-900" data-aos="fade-up">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Column 1: Company Info -->
                <div class="space-y-4">
                    <a href="/" class="flex items-center">
                        <!-- Light mode logo (shown by default) -->
                        <img src="/images/crop-logo-2.png" alt="Syaliarisa Services"
                            class="h-13 block dark:hidden pb-1" />
                        <span
                            class="block dark:hidden mx-4 text-[#2262AE] font-extrabold text-xl uppercase drop-shadow-lg drop-shadow-gray-400 font-mono">Syaliarisa</span>

                        <!-- Dark mode logo (hidden by default, shown in dark mode) -->
                        <img src="/images/secondary-logo.png" alt="Syaliarisa Services"
                            class="h-13 hidden dark:block" />
                        <span
                            class="hidden dark:block mx-4 text-white font-extrabold text-xl uppercase drop-shadow-lg drop-shadow-gray-400 font-mono">Syaliarisa</span>
                    </a>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white ml-3">Follow Us</h3>
                    <div class="flex space-x-3 ml-3">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/profile.php?id=61566208336903" target="_blank"
                            class="w-10 h-10 bg-[#1877F2] rounded-full flex items-center justify-center">
                            <i class="bi bi-facebook text-white text-lg"></i>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/company/syaliarisa-services-sdn-bhd/?viewAsMember=true"
                            target="_blank"
                            class="w-10 h-10 bg-[#0A66C2] rounded-full flex items-center justify-center">
                            <i class="bi bi-linkedin text-white text-lg"></i>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/syaliarisa.services?igsh=MW9uOTRqMXd2d29tNA=="
                            target="_blank"
                            class="w-10 h-10 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="bi bi-instagram text-white text-lg"></i>
                        </a>

                        <!-- YouTube -->
                        <!-- <a href="#"
                            class="w-10 h-10 bg-[#FF0000] rounded-full flex items-center justify-center">
                            <i class="bi bi-youtube text-white text-lg"></i>
                        </a> -->
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="/"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1">Home</a>
                        </li>
                        <li><a href="/about-us/company-info"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1">Company
                                Info</a></li>
                        <li><a href="/about-us/standards"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1">Standards</a>
                        </li>
                        <li><a href="/about-us/legislation"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1">Legislations</a>
                        </li>
                        <li><a href="/contact-us"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1">Contact
                                Us</a></li>
                    </ul>
                </div>

                <!-- Column 3: Services -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Our Services</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="/services/safety-health"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1 break-words">
                                Occupational Safety & Health
                            </a>
                        </li>
                        <li>
                            <a href="/services/environmental"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1 break-words">
                                Environmental Monitoring
                            </a>
                        </li>
                        <li>
                            <a href="/services/training"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 block py-1 break-words">
                                Training Academy
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Contact Info (Improved for long content) -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Contact Info</h3>
                    <ul class="space-y-4">
                        <!-- Address -->
                        <li v-if="contactInfo.address" class="flex items-start gap-3">
                            <i class="bi bi-geo-alt text-xl text-[#2262ae] dark:text-blue-400 flex-shrink-0"></i>
                            <span class="text-gray-600 dark:text-gray-300 break-words leading-relaxed min-w-0">
                                {{ contactInfo.address }}
                            </span>
                        </li>

                        <!-- Phone -->
                        <li v-if="contactInfo.phone" class="flex items-start gap-3">
                            <i class="bi bi-telephone text-xl text-[#2262ae] dark:text-blue-400 flex-shrink-0"></i>
                            <a :href="`tel:${contactInfo.phone}`"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 break-words">
                                {{ contactInfo.phone }}
                            </a>
                        </li>

                        <!-- Mobile Phone 1 -->
                        <!-- <li v-if="contactInfo.mobile_phone_1" class="flex items-start gap-3">
                            <i class="bi bi-whatsapp text-xl text-green-500 flex-shrink-0"></i>
                            <a :href="`https://wa.me/${contactInfo.mobile_phone_1.replace('+', '')}`"
                               target="_blank"
                               class="text-gray-600 dark:text-gray-300 hover:text-green-500 transition-colors duration-200 break-words">
                                {{ contactInfo.mobile_phone_1 }}
                            </a>
                        </li> -->

                        <!-- Mobile Phone 2 -->
                        <!-- <li v-if="contactInfo.mobile_phone_2" class="flex items-start gap-3">
                            <i class="bi bi-whatsapp text-xl text-green-500 mt-1 flex-shrink-0"></i>
                            <a :href="`https://wa.me/${contactInfo.mobile_phone_2.replace('+', '')}`"
                               target="_blank"
                               class="text-gray-600 dark:text-gray-300 hover:text-green-500 transition-colors duration-200 break-words">
                                {{ contactInfo.mobile_phone_2 }}
                            </a>
                        </li> -->

                        <!-- Email -->
                        <li v-if="contactInfo.email_1" class="flex items-start gap-3">
                            <i class="bi bi-envelope text-xl text-[#2262ae] dark:text-blue-400 flex-shrink-0"></i>
                            <a :href="`mailto:${contactInfo.email_1}`"
                                class="text-gray-600 dark:text-gray-300 hover:text-[#48b2e5] dark:hover:text-blue-400 transition-colors duration-200 break-all">
                                {{ contactInfo.email_1 }}
                            </a>
                        </li>

                        <!-- Business Hours -->
                        <li v-if="contactInfo.business_hours" class="flex items-start gap-3">
                            <i class="bi bi-clock text-xl text-[#2262ae] dark:text-blue-400 flex-shrink-0"></i>
                            <div class="text-gray-600 dark:text-gray-300 break-words leading-relaxed min-w-0"
                                v-html="contactInfo.business_hours"></div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer bottom -->
            <div class="border-t border-gray-300 dark:border-gray-700 mt-12 pt-8 pb-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        &copy; {{ new Date().getFullYear() }} Syaliarisa Services Sdn Bhd. All rights reserved.
                    </p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm">
                        202001036763 (1393084-A)
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const contactInfo = ref<any>({})

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/contact-info')
        contactInfo.value = data.contact_info || {}
    } catch (err) {
        console.error('Failed to load contact info:', err)
    }
})
</script>
