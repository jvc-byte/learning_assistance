<script setup lang="ts">
import { defineProps, ref } from 'vue';
import { type ChatMessage, type LessonContext } from '@/types/LessonReader';

defineProps<{
    messages: ChatMessage[];
    context: LessonContext;
    isTyping: boolean;
}>();

const emit = defineEmits<{
    (e: 'ask-question', question: string): void;
}>();

const newMessage = ref('');

const sendMessage = () => {
    if (newMessage.value.trim()) {
        emit('ask-question', newMessage.value);
        newMessage.value = '';
    }
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6 h-full overflow-y-auto">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900">AI Tutor</h2>
            <p class="text-sm text-gray-500">Ask questions about the lesson content</p>
        </div>

        <div class="space-y-4 mb-6">
            <div v-for="(message, index) in messages" :key="index" :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                <div class="max-w-[80%]">
                    <div :class="message.sender === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900'" class="p-3 rounded-lg rounded-br-none">
                        {{ message.content }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ new Date(message.timestamp).toLocaleTimeString() }}
                    </div>
                </div>
            </div>

            <div v-if="isTyping" class="flex justify-start">
                <div class="max-w-[80%]">
                    <div class="p-3 rounded-lg rounded-br-none bg-gray-100">
                        <div class="animate-pulse flex space-x-2">
                            <div class="w-3 h-3 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-3 h-3 bg-gray-400 rounded-full animate-bounce delay-100"></div>
                            <div class="w-3 h-3 bg-gray-400 rounded-full animate-bounce delay-200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t pt-4">
            <div class="relative">
                <input
                    v-model="newMessage"
                    @keypress.enter.prevent="sendMessage"
                    type="text"
                    placeholder="Ask a question..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button
                    @click="sendMessage"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                >
                    Send
                </button>
            </div>
        </div>
    </div>
</template>
