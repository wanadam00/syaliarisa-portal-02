<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import Textarea from '@/components/Textarea.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { Button } from '@/components/ui/button';
</script>

<template>
  <AuthLayout>
    <div class="p-6 space-y-6">
      <h1 class="text-2xl font-bold">Home Page Management</h1>
      <Card>
        <CardHeader>
          <CardTitle>Hero Section</CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <Input label="Title" placeholder="Welcome to our company" />
          <Textarea label="Content" placeholder="Short description or tagline" />
          <Input type="file" label="Hero Image" />
          <Switch label="Visible" />
          <Button>Save</Button>
        </CardContent>
      </Card>
    </div>
  </AuthLayout>
</template>
