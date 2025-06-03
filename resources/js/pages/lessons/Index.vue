<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { can } from '@/lib/can';
import { defineProps } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lessons',
        href: '/lessons',
    },
];

const props = defineProps({
    lessons: {
        type: Object,
        required: true,
    },
});

const form = useForm({});

function deleteLesson(id: number) {
    if (confirm('Are you sure you want to delete this lesson?')) {
        form.delete(route('lessons.destroy', id));
    }
}
</script>

<template>
    <Head title="Lessons" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="self-end">
                <Link
                    v-if="can('lessons.create')"
                    :href="route('lessons.create')"
                    class="flex cursor-pointer items-center rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Lesson
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Subject</th>
                            <th scope="col" class="px-6 py-3">Grade Level</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Created By</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="lesson in props.lessons.data"
                            :key="lesson.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        >
                            <td class="px-6 py-4">{{ lesson.title }}</td>
                            <td class="px-6 py-4">{{ lesson.subject }}</td>
                            <td class="px-6 py-4">{{ lesson.grade_level }}</td>
                            <td class="px-6 py-4">
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800': lesson.status === 'active',
                                        'bg-yellow-100 text-yellow-800': lesson.status === 'draft',
                                    }"
                                    class="px-2.5 py-0.5 rounded text-xs font-medium"
                                >
                                    {{ lesson.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ lesson.created_by?.name }}</td>
                            <td class="px-6 py-4">
                                <Link
                                    :href="route('lessons.show', lesson.id)"
                                    class="cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Show
                                </Link>
                                <Link
                                    v-if="can('lessons.edit')"
                                    :href="route('lessons.edit', lesson.id)"
                                    class="ml-1 cursor-pointer rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Edit
                                </Link>
                                <button
                                    v-if="can('lessons.delete')"
                                    @click="deleteLesson(lesson.id)"
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
