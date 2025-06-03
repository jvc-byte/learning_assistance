<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Role, User } from '@/types/User';
import { Undo2 } from 'lucide-vue-next';

interface Props {
    user: User;
    roles: Role[];
    userRoles: string[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Edit User',
        href: `/users/${props.user.id}/edit`,
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    roles: props.userRoles,
});

function submit() {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Don't reset the form - we want to keep the updated values visible
            console.log('User updated successfully');
        },
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Edit User" />

        <Link
            href="/users"
            class="flex items-center gap-2 self-end rounded border border-gray-500 bg-gray-500 px-2 py-1 font-bold text-white hover:bg-gray-700"
        >
            <Undo2 />
            Back to Users
        </Link>

        <div class="flex w-1/2 flex-col space-y-6 self-center">
            <HeadingSmall title="Edit User" description="Edit a user" />

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" placeholder="Email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Roles: </Label>
                    <div v-for="role in props.roles" :key="role.name" class="flex items-center space-x-3">
                        <label for="remember" class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="form.roles"
                                :value="role.name"
                                class="form-checkbox h-5 w-5 rounded text-blue-600 focus:ring-2 focus:ring-blue-500"
                            />
                            <span class="text-gray-800 capitalize">{{ role.name }}</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.roles" />
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
