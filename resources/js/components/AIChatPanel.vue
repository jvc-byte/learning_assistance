<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { MessageSquareDiff } from 'lucide-vue-next';
import { defineProps, nextTick, onMounted, ref, watchEffect } from 'vue';

interface Message {
    id: number;
    content: string;
    sender: 'user' | 'assistant';
    timestamp: string;
}

const props = defineProps<{
    lessonId?: number | string;
}>();

// Debug logging
console.log('AIChatPanel props received:', props);
console.log('lessonId type:', typeof props.lessonId);
console.log('lessonId value:', props.lessonId);

// Watch for prop changes
watchEffect(() => {
    console.log('lessonId changed to:', props.lessonId);
});

const messages = ref<Message[]>([
    {
        id: 1,
        content: 'Hi there! I\'m EduBot, your learning companion. How can I assist you today?',
        sender: 'assistant',
        timestamp: new Date().toLocaleTimeString(),
    },
]);

const newMessage = ref('');
const isLoading = ref(false);
const messageCount = ref(1);

const csrfToken = ref('');
const isCsrfReady = ref(false);
const debugInfo = ref('');

// Get CSRF token on component mount
const initializeCsrfToken = async () => {
    try {
        // First try to get token from meta tag
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) {
            csrfToken.value = meta.getAttribute('content') || '';
            isCsrfReady.value = true;
            console.log('CSRF token found in meta:', csrfToken.value.substring(0, 10) + '...');
            return;
        }

        // If not in meta, fetch from API using Laravel's CSRF token endpoint
        try {
            // First try to get token from Laravel's CSRF token endpoint
            const response = await fetch('/sanctum/csrf-cookie', {
                method: 'GET',
                credentials: 'include'
            });
            
            if (!response.ok) {
                throw new Error(`Failed to fetch CSRF token: ${response.status}`);
            }

            // Then get the token from the meta tag
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) {
                csrfToken.value = meta.getAttribute('content') || '';
                isCsrfReady.value = true;
                console.log('CSRF token fetched from Laravel:', csrfToken.value.substring(0, 10) + '...');
            } else {
                throw new Error('CSRF token not found in meta tag after fetching CSRF cookie');
            }
        } catch (error) {
            console.error('Error fetching CSRF token:', error);
            throw error;
        }
    } catch (error) {
        console.error('Error getting CSRF token:', error);
        throw error;
    }
};

// Get CSRF token on component mount
onMounted(async () => {
    try {
        // Watch for authentication changes
        watchEffect(async () => {
            if (!isCsrfReady.value) {
                await initializeCsrfToken();
            }
        });

        // Initial initialization
        await initializeCsrfToken();
        
        // Update debug info
        updateDebugInfo();
    } catch (error) {
        console.error('Error initializing component:', error);
        // Set default error state
        isCsrfReady.value = false;
        csrfToken.value = '';
        updateDebugInfo();
    }
});

const updateDebugInfo = () => {
    debugInfo.value = `
        LessonId: ${props.lessonId} (${typeof props.lessonId})
        CSRF Token: ${csrfToken.value ? 'Present' : 'Missing'}
        Current URL: ${window.location.href}
    `.trim();
};

const sendMessage = async () => {
    if (!newMessage.value.trim() || isLoading.value || !isCsrfReady.value) return;

    // Validate lessonId before making the request
    if (!props.lessonId || props.lessonId === 'undefined' || props.lessonId === undefined) {
        console.error('Invalid lessonId:', props.lessonId);
        messages.value.push({
            id: ++messageCount.value,
            content: `Error: Lesson ID is missing or invalid (${props.lessonId}). Please check how the component is being used.`,
            sender: 'assistant',
            timestamp: new Date().toLocaleTimeString(),
        });
        return;
    }

    isLoading.value = true;

    // Add user message
    messages.value.push({
        id: ++messageCount.value,
        content: newMessage.value,
        sender: 'user',
        timestamp: new Date().toLocaleTimeString(),
    });

    const messageToSend = newMessage.value;
    newMessage.value = '';

    // Scroll to bottom after adding user message
    await nextTick();
    scrollToBottom();

    const apiUrl = `/ai/lesson/${props.lessonId}/question`;
    console.log('Making request to:', apiUrl);

    try {
        const requestBody = { question: messageToSend };
        console.log('Request body:', requestBody);
        console.log('Request headers will include CSRF token:', csrfToken.value.substring(0, 10) + '...');

        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken.value,
            },
            // For Laravel, we need to send the CSRF token in the body as well
            body: JSON.stringify({
                ...requestBody,
                _token: csrfToken.value
            }),
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));

        if (!response.ok) {
            let errorMessage = `Server error: ${response.status} ${response.statusText}`;
            
            try {
                const responseText = await response.text();
                console.log('Error response body:', responseText);
                
                // Try to parse as JSON
                try {
                    const errorData = JSON.parse(responseText);
                    errorMessage = errorData.error || errorData.message || errorMessage;
                    
                    if (errorData.details && typeof errorData.details === 'object') {
                        const validationErrors = Object.values(errorData.details).flat();
                        errorMessage += ': ' + validationErrors.join(', ');
                    }
                } catch (parseError) {
                    // If it's not JSON, it might be HTML error page
                    if (responseText.includes('<!DOCTYPE html>')) {
                        console.error('HTML error page received:', parseError);
                        errorMessage = 'Server returned an HTML error page (check Laravel logs)';
                    } else {
                        errorMessage += ': ' + responseText.substring(0, 100);
                    }
                }
            } catch (e) {
                console.error('Error reading error response:', e);
            }
            
            throw new Error(errorMessage);
        }

        const data = await response.json();
        console.log('Success response:', data);

        // Handle successful response
        if (data.response) {
            messages.value.push({
                id: ++messageCount.value,
                content: data.response,
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString(),
            });
        } else if (data.error) {
            messages.value.push({
                id: ++messageCount.value,
                content: `Error: ${data.error}`,
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString(),
            });
        } else {
            console.warn('Unexpected response format:', data);
            messages.value.push({
                id: ++messageCount.value,
                content: 'I received your message but the response format was unexpected. Please try again.',
                sender: 'assistant',
                timestamp: new Date().toLocaleTimeString(),
            });
        }
    } catch (error: unknown) {
        console.error('Chat error:', error);

        let errorMessage = 'Sorry, I encountered an error while processing your message.';

        if (error instanceof Error) {
            errorMessage = error.message;
        } else if (typeof error === 'string') {
            errorMessage = error;
        }

        messages.value.push({
            id: ++messageCount.value,
            content: errorMessage,
            sender: 'assistant',
            timestamp: new Date().toLocaleTimeString(),
        });

        // Restore the user's message to the input if there was an error
        newMessage.value = messageToSend;
    } finally {
        isLoading.value = false;
        // Scroll to bottom after adding assistant message
        await nextTick();
        scrollToBottom();
    }
};

const scrollToBottom = () => {
    const container = document.querySelector('.chat-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
};

const handleKeyPress = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
};
</script>

<template>
    <div class="flex h-[calc(100vh-12rem)] flex-col">
        <div class="flex items-center justify-between border-b p-4">
            <h1 class="text-2xl font-semibold">EduBot - AI Assistant</h1>
            <div class="flex items-center space-x-2">
                <Button variant="ghost" size="sm">
                    <MessageSquareDiff class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <!-- Debug Information Panel -->
        <div v-if="!props.lessonId" class="border-l-4 border-red-400 bg-red-50 p-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-red-700"><strong>Debug Info:</strong> lessonId prop is missing or undefined.</p>
                    <pre class="mt-2 text-xs whitespace-pre-wrap text-red-600">{{ debugInfo }}</pre>
                    <p class="mt-2 text-sm text-red-700">
                        <strong>Solution:</strong> Make sure you're passing the lessonId prop correctly:
                        <code class="rounded bg-red-100 px-1">&lt;AIChatPanel :lesson-id="yourLessonId" /&gt;</code>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="space-y-4 p-4">
                <div v-for="message in messages" :key="message.id" :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div
                        :class="[
                            'max-w-[80%] rounded-lg p-3',
                            message.sender === 'user' ? 'bg-primary text-primary-foreground' : 'bg-secondary text-secondary-foreground',
                        ]"
                    >
                        <p class="whitespace-pre-wrap">{{ message.content }}</p>
                        <p class="text-muted-foreground mt-1 text-xs">
                            {{ message.timestamp }}
                        </p>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div v-if="isLoading" class="flex justify-start">
                    <div class="bg-secondary text-secondary-foreground max-w-[80%] rounded-lg p-3">
                        <div class="flex items-center space-x-2">
                            <div class="border-primary h-4 w-4 animate-spin rounded-full border-2 border-t-transparent"></div>
                            <p>EduBot is thinking...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t p-4">
            <div class="flex gap-2">
                <Input
                    v-model="newMessage"
                    placeholder="Type your message..."
                    @keypress="handleKeyPress"
                    :disabled="isLoading || !props.lessonId"
                    class="flex-1"
                    maxlength="1000"
                />
                <Button
                    @click="sendMessage"
                    :disabled="isLoading || !newMessage.trim() || !props.lessonId"
                    :class="{ 'opacity-50': isLoading || !newMessage.trim() || !props.lessonId }"
                >
                    {{ isLoading ? 'Sending...' : 'Send' }}
                </Button>
            </div>
            <p class="text-muted-foreground mt-2 text-xs">
                Press Enter to send, Shift+Enter for new line
                <span v-if="props.lessonId" class="ml-2">• Lesson ID: {{ props.lessonId }}</span>
            </p>
        </div>
    </div>
</template>
