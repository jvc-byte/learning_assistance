<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { User, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

interface Props {
    users: Array<User>;
}
const props = defineProps<Props>();

const form = useForm({});

function deleteUser(id: number) {
    if (confirm('Are you sure you want to delete this user?')) {
        form.delete(route('users.destroy', id));
    }
}
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="self-end">
                <Link
                    :href="route('users.create')"
                    class="flex cursor-pointer items-center rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    <Plus class="mr-2" /> Create User
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in props.users"
                            :key="user.id"
                            class="border-b border-gray-200 odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800"
                        >
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ user.id }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ user.name }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ user.email }}</td>
                            <td class="px-6 py-2">
                                <Link
                                    :href="route('users.show', user.id)"
                                    class="mr-1 cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Show
                                </Link>
                                <Link
                                    :href="route('users.edit', user.id)"
                                    class="cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteUser(user.id)"
                                    class="ml-1 cursor-pointer rounded-lg bg-red-700 px-3 py-2 text-xs font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300 focus:outline-none dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
