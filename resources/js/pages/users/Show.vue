<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Undo2 } from 'lucide-vue-next';

interface Props {
    user: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: props.user.name,
        href: `/users/${props.user.id}`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="User Details" />

        <div class="space-y-6">
            <!-- Back Button -->
            <div class="flex flex-row-reverse">
                <Link
                    :href="route('users.index')"
                    class="flex cursor-pointer items-center rounded-lg bg-gray-700 px-3 py-2 text-xs font-medium text-white hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                >
                    <Undo2 class="mr-2" /> Back to Users
                </Link>
            </div>

            <!-- User Card -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">User Details</h2>

                <div class="grid grid-cols-2 gap-6">
                    <!-- User Information -->
                    <div>
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.name }}</dd>
                        </div>
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ user.email }}</dd>
                        </div>
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span
                                    v-if="user.email_verified_at"
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                >
                                    Verified
                                </span>
                                <span v-else class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                    Not Verified
                                </span>
                            </dd>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex h-10 justify-end">
                        <Link
                            :href="route('users.edit', user.id)"
                            class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Edit User
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
