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

interface Props {
    permissions: Array<any>;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Role',
        href: '/roles/create',
    },
];

const form = useForm({
    name: '',
    permissions: [],
});

function submit() {
    form.post(route('roles.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Link
            href="/roles"
            class="flex items-center gap-2 self-end rounded border border-gray-500 bg-gray-500 px-2 py-1 font-bold text-white hover:bg-gray-700"
        >
            <Undo2 />
            Back to Roles
        </Link>
        <Head title="Create Role" />

        <div class="flex w-1/2 flex-col space-y-6 self-center">
            <HeadingSmall title="Create Role" description="Create a new role" />

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="name">Permissions: </Label>
                    <div v-for="permission in permissions" :key="permission.id" class="flex items-center space-x-3">
                        <label for="remember" class="flex items-center space-x-2">
                            <input type="checkbox" v-model="form.permissions" :value="permission.name" class="form-checkbox h-5 w-5 rounded text-blue-600 focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-800 capitalize">{{ permission.name }}</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.permissions" />
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
