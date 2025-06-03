


<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Undo2 } from 'lucide-vue-next';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    roles: any[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create User',
        href: '/users/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: [],
});

function submit() {
    form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Link href="/users" class="self-end rounded border border-gray-500 bg-gray-500 px-2 py-1 font-bold text-white hover:bg-gray-700 flex items-center gap-2"
                >
                <Undo2 />
                Back to Users
            </Link>
        <Head title="Create User" />
        

        <div class="flex flex-col space-y-6 self-center w-1/2">
            <HeadingSmall title="Create User" description="Create a new user" />

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
                    <Label for="password">Password</Label>
                    <Input id="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="password" placeholder="Password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input id="password_confirmation" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="confirm-password" placeholder="Confirm Password" />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Roles: </Label>
                    <div v-for="role in roles" :key="role.id" class="flex items-center space-x-3">
                        <label for="remember" class="flex items-center space-x-2">
                            <input type="checkbox" v-model="form.role" :value="role.name" class="form-checkbox h-5 w-5 rounded text-blue-600 focus:ring-2 focus:ring-blue-500" />
                            <span class="text-gray-800 capitalize">{{ role.name }}</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.role" />
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
