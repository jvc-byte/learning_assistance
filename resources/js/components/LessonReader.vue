<script setup lang="ts">
import { defineProps, ref, onMounted } from 'vue';
import { type Lesson, type LessonContext } from '@/types/lesson';

const props = defineProps<{
    lesson: Lesson;
    context: LessonContext;
}>();

const emit = defineEmits<{
    (e: 'update-context', context: LessonContext): void;
    (e: 'highlight-text', text: string): void;
    (e: 'ask-question', question: string): void;
}>();

const currentPage = ref(props.context.currentPage);
const highlightedText = ref('');

const updateContext = (page: number) => {
    emit('update-context', {
        ...props.context,
        currentPage: page,
        highlightedText: highlightedText.value
    });
};

const handleTextSelection = () => {
    const selection = window.getSelection();
    if (selection?.toString()) {
        highlightedText.value = selection.toString();
        emit('highlight-text', highlightedText.value);
    }
};

const askQuestion = (text: string) => {
    emit('ask-question', text);
};

onMounted(() => {
    document.addEventListener('mouseup', handleTextSelection);
});

const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && highlightedText.value) {
        askQuestion(`What does this mean? ${highlightedText.value}`);
    }
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6 h-full overflow-y-auto">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ lesson.title }}</h1>
            <p class="text-sm text-gray-500">{{ lesson.subject }} - {{ lesson.grade_level }}</p>
        </div>

        <div class="prose prose-gray max-w-none" v-html="lesson.content" @click="handleTextSelection" @keydown="handleKeyDown"></div>

        <div class="mt-6 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Page {{ currentPage }} of {{ lesson.total_pages }}</span>
                <button @click="updateContext(currentPage - 1)" :disabled="currentPage <= 1" class="px-3 py-1 rounded-md text-sm">
                    Previous
                </button>
                <button @click="updateContext(currentPage + 1)" :disabled="currentPage >= lesson.total_pages" class="px-3 py-1 rounded-md text-sm">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>
