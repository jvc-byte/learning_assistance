<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { can } from '@/lib/can';
import { type BreadcrumbItem } from '@/types';
import type { ChatMessage } from '@/types/LessonReader';
import { Head, Link } from '@inertiajs/vue3';
import { Undo2 } from 'lucide-vue-next';
import { defineProps, ref } from 'vue';
import AIChatPanel from '../../components/AIChatPanel.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lessons',
        href: '/lessons',
    },
    {
        title: 'Show',
        href: '/lessons/{id}',
    },
];

const { lesson } = defineProps<{
    lesson: any;
}>();

const chatMessages = ref<ChatMessage[]>([]);
const isAITyping = ref(false);

const askAI = async (question: string) => {
    isAITyping.value = true;
    try {
        // Simulated AI response
        const response = {
            answer: `Great question about ${lesson.subject}! Here's how it relates to ${lesson.title}: [detailed explanation]`,
            references: ['section-1', 'section-2'],
            difficulty: 'intermediate',
            suggestions: ['Explain further', 'Show example', 'Test understanding'],
            relatedQuestions: ['What is the main concept?', 'How does this apply?', 'Can you give an example?'],
        };

        chatMessages.value.push({
            id: Date.now().toString(),
            content: question,
            sender: 'user',
            timestamp: new Date().toISOString(),
        });

        chatMessages.value.push({
            id: (Date.now() + 1).toString(),
            content: response.answer,
            sender: 'ai',
            timestamp: new Date().toISOString(),
            references: response.references,
            difficulty: response.difficulty,
        });
    } finally {
        isAITyping.value = false;
    }
};
</script>

<template>
    <Head title="Lesson Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col gap-4 p-4">
            <div class="self-end">
                <Link
                    v-if="can('lessons.create')"
                    :href="route('lessons.index')"
                    class="flex cursor-pointer items-center rounded-lg bg-blue-700 px-3 py-2 text-xs font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    <Undo2 class="mr-2" />Back to Lessons
                </Link>
            </div>
            <div class="flex h-full">
                <div class="w-full lg:w-3/5">
                    <div class="p-6">
                        <h1 class="mb-4 text-2xl font-bold">{{ lesson.title }}</h1>
                        <div class="prose max-w-none">
                            <div v-html="lesson.content"></div>
                        </div>
                    </div>
                </div>

                <!-- AI Chat Sidebar (30%) -->
                <div class="w-full pl-4 lg:w-2/5">
                    <div class="space-y-4">
                        <AIChatPanel :lessonId="lesson.id" :messages="chatMessages" :is-typing="isAITyping" @ask-question="askAI" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
