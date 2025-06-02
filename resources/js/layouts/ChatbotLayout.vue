<script setup lang="ts">
import { MessageSquare } from 'lucide-vue-next';
import { Conversation } from '@/types';
import { computed } from 'vue';

const conversations: Conversation[] = [
    {
        id: 1,
        title: 'Math Homework Help',
        date: '2025-06-01'
    },
    {
        id: 2,
        title: 'Science Project',
        date: '2025-05-31'
    },
    {
        id: 3,
        title: 'History Discussion',
        date: '2025-05-24'
    }
];

const MAX_TITLE_LENGTH = 13;

const trimmedConversations = computed(() => {
    return conversations.map(conversation => ({
        ...conversation,
        title: conversation.title.length > MAX_TITLE_LENGTH 
            ? conversation.title.slice(0, MAX_TITLE_LENGTH - 1) + '…'
            : conversation.title
    }));
});
</script>

<template>
    <div class="flex h-full">
        <aside class="w-64 border-r">
            <div class="p-4">
                <h1 class="text-2xl font-semibold mb-4">Conversations</h1>
                <div class="space-y-2">
                    <div
                        v-for="conversation in trimmedConversations"
                        :key="conversation.id"
                        class="flex items-center rounded-lg p-3 hover:bg-muted transition-colors cursor-pointer"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <MessageSquare class="h-4 w-4 text-primary" />
                            </div>
                            <div>
                                <h3 class="font-medium">{{ conversation.title }}</h3>
                                <span class="text-sm text-muted-foreground">{{ conversation.date }}</span>
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
