<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { LogIn, UserPlus, ArrowUpRight, BookHeart, BotMessageSquare, RailSymbol, BookOpenCheck } from 'lucide-vue-next';
import { computed } from 'vue';

interface PageProps {
    auth: {
        user: any | null;
    };
}

const props = defineProps<PageProps>();
const isAuthenticated = computed(() => props.auth.user !== null);
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-background text-foreground flex min-h-screen flex-col items-center p-6 lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <template v-if="isAuthenticated">
                    <Link
                        :href="route('dashboard')"
                        class="text-foreground hover:bg-muted/100 inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-semibold"
                    >
                        Dashboard <ArrowUpRight />
                    </Link>
                </template>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-foreground hover:bg-muted/100 inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-semibold"
                    >
                        <LogIn /> Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium"
                    >
                        <UserPlus /> Register
                    </Link>
                </template>
            </nav>
        </header>

        <div class="flex w-full max-w-4xl flex-col items-center gap-8">
            <div class="flex flex-col items-center gap-4">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Welcome to Learning Assistant</h1>
                <p class="text-muted-foreground text-lg sm:text-xl">Start your learning journey with our AI-powered assistant</p>
            </div>

            <div class="grid w-full max-w-2xl grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-card rounded-lg border p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg">
                            <BookHeart />
                        </div>
                        <div>
                            <h3 class="font-semibold">Personalized Learning</h3>
                            <p class="text-muted-foreground mt-2 text-sm">Get tailored assistance based on your learning style and needs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-lg border p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg">
                           <BotMessageSquare />
                        </div>
                        <div>
                            <h3 class="font-semibold">24/7 Support</h3>
                            <p class="text-muted-foreground mt-2 text-sm">Access our AI assistant anytime, anywhere</p>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-lg border p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg">
                            <RailSymbol />
                        </div>
                        <div>
                            <h3 class="font-semibold">Progress Tracking</h3>
                            <p class="text-muted-foreground mt-2 text-sm">Monitor your learning progress and achievements</p>
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-lg border p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="bg-primary/10 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg">
                            <BookOpenCheck />
                        </div>
                        <div>
                            <h3 class="font-semibold">Interactive Learning</h3>
                            <p class="text-muted-foreground mt-2 text-sm">Engage with interactive content and exercises</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
