<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { can } from '@/lib/can';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: '/roles',
    },
];

interface Props {
    roles: Array<any>;
}
const props = defineProps<Props>();

const form = useForm({});

function deleteRole(id: number) {
    if (confirm('Are you sure you want to delete this role?')) {
        form.delete(route('roles.destroy', id));
    }
}
</script>

<template>
    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="self-end">
                <Link
                    v-if="can('roles.create')"
                    :href="route('roles.create')"
                    class="flex cursor-pointer items-center rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    <Plus class="mr-2" /> Create Role
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Permissions</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="role in props.roles"
                            :key="role.id"
                            class="border-b border-gray-200 odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800"
                        >
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ role.id }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ role.name }}</td>
                            <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                                <span
                                    v-for="permission in role.permissions.slice(0, 5)"
                                    :key="permission.id"
                                    class="mr-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                >
                                    {{ permission.name }}
                                </span>
                                <span
                                    v-if="role.permissions.length > 5"
                                    class="mr-1 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                >
                                    +{{ role.permissions.length - 5 }} more
                                </span>
                            </td>

                            <td class="px-6 py-2">
                                <Link
                                    :href="route('roles.show', role.id)"
                                    class="mr-1 cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Show
                                </Link>
                                <Link
                                    v-if="can('roles.edit')"
                                    :href="route('roles.edit', role.id)"
                                    class="cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Edit
                                </Link>
                                <button
                                    v-if="can('roles.delete')"
                                    @click="deleteRole(role.id)"
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
