<script setup lang="ts">
import { defineProps } from 'vue';
import { type LessonContext } from '@/types/LessonReader';

defineProps<{
    context: LessonContext;
}>();

const emit = defineEmits<{
    (e: 'ask-question', question: string): void;
}>();

const suggestedQuestions = [
    'What is the main concept in this section?',
    'How does this relate to previous topics?',
    'Can you give me an example of this in real life?',
    'What are the key points I should remember?',
    'How does this apply to practical situations?',
];

const askQuestion = (question: string) => {
    emit('ask-question', question);
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Question Suggestions</h3>
        
        <div class="space-y-2">
            <button
                v-for="(question, index) in suggestedQuestions"
                :key="index"
                @click="askQuestion(question)"
                class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors"
            >
                {{ question }}
            </button>
        </div>
    </div>
</template>
