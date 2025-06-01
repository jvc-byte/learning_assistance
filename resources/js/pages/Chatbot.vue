<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { MessageSquare } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Message {
    id: number;
    content: string;
    sender: 'user' | 'assistant';
    timestamp: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'AI ChatBot',
        href: '/chatbot',
    },
];

const messages = ref<Message[]>([
    {
        id: 1,
        content: 'Hi there! How can I assist you with your learning today?',
        sender: 'assistant',
        timestamp: new Date().toLocaleTimeString()
    }
]);

const newMessage = ref('');
const isLoading = ref(false);
const messageCount = ref(1);

const sendMessage = async () => {
    if (!newMessage.value.trim()) return;

    isLoading.value = true;
    
    // Add user message
    messages.value.push({
        id: ++messageCount.value,
        content: newMessage.value,
        sender: 'user',
        timestamp: new Date().toLocaleTimeString()
    });

    const messageToSend = newMessage.value;
    newMessage.value = '';

    try {
        // Use fetch to make API call
        const response = await fetch('/chat/response', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: messageToSend })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'Failed to get response');
        }

        // Handle response
        if (data.error) {
            messages.value.push({
                id: ++messageCount.value,
                content: data.error,
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString()
            });
        } else if (data.response) {
            messages.value.push({
                id: ++messageCount.value,
                content: data.response,
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString()
            });
        } else {
            messages.value.push({
                id: ++messageCount.value,
                content: 'No response received from the AI. Please try again.',
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString()
            });
        }

        // Scroll to bottom
        nextTick(() => {
            const container = document.querySelector('.chat-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
        
        isLoading.value = false;
    } catch (error) {
        console.error('Unexpected error:', error);
        messages.value.push({
            id: ++messageCount.value,
            content: 'Unexpected error: ' + error.message,
            sender: 'assistant',
            timestamp: new Date().toLocaleTimeString()
        });
        isLoading.value = false;
        newMessage.value = messageToSend;
    }
};
</script>

<template>
    <Head title="AI ChatBot" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between border-b p-4">
                <h1 class="text-2xl font-semibold">AI ChatBot</h1>
                <div class="flex items-center space-x-2">
                    <Button variant="ghost" size="sm">
                        <MessageSquare class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 chat-container" ref="chatContainer">
                <div class="space-y-4">
                    <div v-for="message in messages" :key="message.id" 
                         :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="[
                            'max-w-[80%] rounded-lg p-3',
                            message.sender === 'user' 
                                ? 'bg-primary text-primary-foreground' 
                                : 'bg-secondary text-secondary-foreground'
                        ]">
                            <p>{{ message.content }}</p>
                            <p class="text-xs text-muted-foreground mt-1">
                                {{ message.timestamp }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t p-4">
                <div class="flex gap-2">
                    <Input
                        v-model="newMessage"
                        placeholder="Type your message..."
                        @keypress.enter.prevent="sendMessage"
                        :disabled="isLoading"
                        class="flex-1"
                    />
                    <Button
                        @click="sendMessage"
                        :disabled="isLoading || !newMessage.trim()"
                        :class="{ 'opacity-50': isLoading || !newMessage.trim() }"
                    >
                        Send
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Add smooth scrolling to chat container */
.flex-1 {
    scroll-behavior: smooth;
}
</style>