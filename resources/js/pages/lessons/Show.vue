<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import { Lesson } from '@/types/LessonReader';
import type { LessonContext, ChatMessage, LessonRecommendation } from '@/types/LessonReader';
import LessonReader from '../../components/LessonReader.vue';
import AIChatPanel from '../../components/AIChatPanel.vue';
import QuestionSuggestions from '../../components/QuestionSuggestions.vue';
import LessonRecommendations from '../../components/LessonRecommendations.vue';
import { can } from '@/lib/can';
import { Undo2 } from 'lucide-vue-next';

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
    lesson: Lesson;
}>();

const lessonContext = ref<LessonContext>({
    currentPage: 1,
    highlightedText: '',
    activeSection: 'introduction',
    readingProgress: 0,
    timeSpent: '0 minutes'
});

const chatMessages = ref<ChatMessage[]>([]);
const isAITyping = ref(false);
const recommendations = ref<LessonRecommendation[]>([]);

const askAI = async (question: string) => {
    isAITyping.value = true;
    try {
        // Simulated AI response
        const response = {
            answer: `Great question about ${lesson.subject}! Here's how it relates to ${lesson.title}: [detailed explanation]`,
            references: ['section-1', 'section-2'],
            difficulty: 'intermediate',
            suggestions: ['Explain further', 'Show example', 'Test understanding'],
            relatedQuestions: ['What is the main concept?', 'How does this apply?', 'Can you give an example?']
        };

        chatMessages.value.push({
            id: Date.now().toString(),
            content: question,
            sender: 'user',
            timestamp: new Date().toISOString()
        });

        chatMessages.value.push({
            id: (Date.now() + 1).toString(),
            content: response.answer,
            sender: 'ai',
            timestamp: new Date().toISOString(),
            references: response.references,
            difficulty: response.difficulty
        });
    } finally {
        isAITyping.value = false;
    }
};

const updateContext = (context: LessonContext) => {
    lessonContext.value = context;
};

const handleTextHighlight = (text: string) => {
    lessonContext.value.highlightedText = text;
};

const showRecommendations = async () => {
    // Simulated recommendations
    recommendations.value = [
        {
            lesson: {
                id: 2,
                title: 'Related Topic',
                subject: lesson.subject,
                grade_level: lesson.grade_level
            },
            relevanceScore: 0.85,
            reason: 'Based on your questions about this topic',
            difficulty: 'similar',
            estimatedTime: '15 minutes'
        }
    ];
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
                    
                    <Undo2 class="mr-2"/>Back to Lessons
                </Link>
            </div>
            <div class="flex h-full">
                <!-- Main Lesson Reader (70%) -->
                <div class="w-full lg:w-3/5">
                    <LessonReader
                        :lesson="lesson"
                        :context="lessonContext"
                        @update-context="updateContext"
                        @highlight-text="handleTextHighlight"
                        @ask-question="askAI"
                    />
                </div>

                <!-- AI Chat Sidebar (30%) -->
                <div class="w-full lg:w-2/5 pl-4">
                    <div class="space-y-4">
                        <AIChatPanel
                            :messages="chatMessages"
                            :context="lessonContext"
                            :is-typing="isAITyping"
                            @ask-question="askAI"
                        />
                        
                        <QuestionSuggestions
                            :context="lessonContext"
                            @ask-question="askAI"
                        />
                        
                        <LessonRecommendations
                            :recommendations="recommendations"
                            @show-recommendations="showRecommendations"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>