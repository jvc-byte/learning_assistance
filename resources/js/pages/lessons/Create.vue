<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Undo2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lessons',
        href: '/lessons',
    },
    {
        title: 'Create',
        href: '/lessons/create',
    },
];

const form = useForm({
    title: '',
    content: '',
    subject: '',
    grade_level: '',
    status: 'draft',
});

const submit = () => {
    form.post(route('lessons.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Link
            href="/lessons"
            class="flex items-center gap-2 self-end rounded border border-gray-500 bg-gray-500 px-2 py-1 font-bold text-white hover:bg-gray-700"
        >
            <Undo2 />
            Back to Lessons
        </Link>
        <Head title="Create User" />

        <div class="flex w-1/2 flex-col space-y-6 self-center">
            <HeadingSmall title="Create Lesson" description="Create a new Lesson" />

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input id="title" class="mt-1 block w-full" v-model="form.title" required autocomplete="title" placeholder="Title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div class="grid gap-2">
                    <Label for="content">Content</Label>
                    <textarea
                        id="content"
                        class="mt-1 block w-full border-2"
                        v-model="form.content"
                        required
                        autocomplete="content"
                        placeholder="Content"
                    />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>

                <div class="grid gap-2">
                    <Label for="subject">Subject</Label>
                    <Input id="subject" class="mt-1 block w-full" v-model="form.subject" required autocomplete="subject" placeholder="Subject" />
                    <InputError class="mt-2" :message="form.errors.subject" />
                </div>
                <div class="grid gap-2">
                    <Label for="grade_level">Grade Level</Label>
                    <Input
                        id="grade_level"
                        class="mt-1 block w-full"
                        v-model="form.grade_level"
                        required
                        autocomplete="grade_level"
                        placeholder="Grade Level"
                    />
                    <InputError class="mt-2" :message="form.errors.grade_level" />
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        id="status"
                        v-model="form.status"
                        class="mt-1 block w-full text-gray-800 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        :class="{ 'border-red-500': form.errors.status }"
                    >
                        <option class="text-gray-600" value="draft">Draft</option>
                        <option class="text-gray-600" value="active">Active</option>
                    </select>
                    <div v-if="form.errors.status" class="mt-2 text-sm text-red-600">
                        {{ form.errors.status }}
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button class="w-full" :disabled="form.processing">Save</Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
