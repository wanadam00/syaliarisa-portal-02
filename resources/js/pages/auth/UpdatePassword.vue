<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';


// Create form object
const form = useForm({
    password: '',
    password_confirmation: '',
})

const submitting = ref(false)

const submit = () => {
    submitting.value = true
    form.post(route('password.forceUpdate'), {
        onFinish: () => (submitting.value = false),
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
            <h1 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">
                Update Your Password
            </h1>
            <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-6">
                Please set a new password before continuing.
            </p>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Password -->
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                    </div>
                    <Input id="password" type="password" required :tabindex="2" autocomplete="current-password"
                        v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Confirm Password -->
                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password_confirmation">Password Confirmation</Label>
                    </div>
                    <Input id="password_confirmation" type="password" required :tabindex="2" autocomplete="password_confirmation"
                        v-model="form.password_confirmation" placeholder="Password confirmation" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- Submit -->
                <button type="submit" :disabled="submitting"
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-medium rounded-lg shadow-sm transition">
                    {{ submitting ? 'Updating...' : 'Update Password' }}
                </button>
            </form>
        </div>
    </div>
</template>
