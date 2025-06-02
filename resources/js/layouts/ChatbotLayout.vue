<script setup lang="ts">
import { Conversation } from '@/types';
import { MessageSquare } from 'lucide-vue-next';
import { computed } from 'vue';

const chat_history: Conversation[] = [
    {
        id: 1,
        title: 'Math Homework Help',
        date: '2025-06-01',
    },
    {
        id: 2,
        title: 'Science Project',
        date: '2025-05-31',
    },
    {
        id: 3,
        title: 'History Discussion',
        date: '2025-05-24',
    },
];

const MAX_TITLE_LENGTH = 13;

const trimmedchat_history = computed(() => {
    return chat_history.map((conversation) => ({
        ...conversation,
        title: conversation.title.length > MAX_TITLE_LENGTH ? conversation.title.slice(0, MAX_TITLE_LENGTH - 1) + '…' : conversation.title,
    }));
});
</script>

<template>
    <div class="flex h-full">
        <aside class="hidden border-r sm:block sm:w-56 md:w-64">
            <div class="p-4">
                <h1 class="mb-4 text-xl font-medium">Chat History</h1>
                <div class="space-y-2">
                    <div
                        onclick="console.log('clicked')"
                        v-for="conversation in trimmedchat_history"
                        :key="conversation.id"
                        class="hover:bg-muted flex cursor-pointer items-center rounded-lg p-3 transition-colors"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="bg-primary/10 flex h-8 w-8 items-center justify-center rounded-full">
                                <MessageSquare class="text-primary h-4 w-4" /><History />
                            </div>
                            <div>
                                <h3 class="font-medium">{{ conversation.title }}</h3>
                                <span class="text-muted-foreground text-sm">{{ conversation.date }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1">
            <slot />
        </main>
    </div>
</template>
