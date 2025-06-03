<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lessons',
        href: '/lessons',
    },
    {
        title: 'Edit',
        href: '/lessons/{id}/edit',
    },
];

const props = defineProps({
    lesson: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: props.lesson.title,
    content: props.lesson.content,
    subject: props.lesson.subject,
    grade_level: props.lesson.grade_level,
    status: props.lesson.status,
});

const submit = () => {
    form.put(route('lessons.update', props.lesson.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Edit Lesson" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="overflow-x-auto">
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <div v-if="form.errors.title" class="mt-2 text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.content }"
                            ></textarea>
                            <div v-if="form.errors.content" class="mt-2 text-sm text-red-600">
                                {{ form.errors.content }}
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input
                                type="text"
                                id="subject"
                                v-model="form.subject"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.subject }"
                            />
                            <div v-if="form.errors.subject" class="mt-2 text-sm text-red-600">
                                {{ form.errors.subject }}
                            </div>
                        </div>

                        <div>
                            <label for="grade_level" class="block text-sm font-medium text-gray-700">Grade Level</label>
                            <input
                                type="text"
                                id="grade_level"
                                v-model="form.grade_level"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.grade_level }"
                            />
                            <div v-if="form.errors.grade_level" class="mt-2 text-sm text-red-600">
                                {{ form.errors.grade_level }}
                            </div>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-500': form.errors.status }"
                            >
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                            </select>
                            <div v-if="form.errors.status" class="mt-2 text-sm text-red-600">
                                {{ form.errors.status }}
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <Link
                                :href="route('lessons.show', lesson.id)"
                                class="rounded-md border border-transparent bg-gray-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                :disabled="form.processing"
                            >
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
