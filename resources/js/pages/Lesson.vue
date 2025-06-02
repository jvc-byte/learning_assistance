<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    Book,
    Bookmark,
    BookmarkCheck,
    CheckCircle,
    ChevronLeft,
    ChevronRight,
    Clock,
    Download,
    Eye,
    MessageCircle,
    NotebookPen,
    Pause,
    Play,
    Settings,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Lessons',
        href: '/lessons',
    },
    {
        title: 'Introduction to Photosynthesis',
        href: '#',
    },
];

// Reactive state
const currentPage = ref(1);
const isBookmarked = ref(false);
const readingMode = ref<'normal' | 'focus' | 'dark'>('normal');
const fontSize = ref<'small' | 'medium' | 'large'>('medium');
const isPlaying = ref(false);
const readingProgress = ref(65);
const showNotes = ref(false);
const currentNote = ref('');
const showSettings = ref(false);
const notes = ref([
    { id: 1, text: 'Important concept about photosynthesis', page: 1, timestamp: '2 mins ago' },
    { id: 2, text: 'Remember this for exam', page: 2, timestamp: '5 mins ago' },
]);

// Sample lesson data
const lesson = {
    title: 'Introduction to Photosynthesis',
    subject: 'Biology',
    grade: 'Grade 10',
    duration: '45 mins',
    instructor: 'Dr. Sarah Johnson',
    totalPages: 12,
    description: 'Learn about the process by which plants convert light energy into chemical energy through photosynthesis.',
    content: [
        {
            page: 1,
            title: 'What is Photosynthesis?',
            content: `Photosynthesis is the biological process by which plants, algae, and some bacteria convert light energy, usually from the sun, into chemical energy stored in glucose molecules.

This process is fundamental to life on Earth as it provides energy for nearly all living organisms and produces the oxygen we breathe.

The general equation for photosynthesis is:
6CO₂ + 6H₂O + light energy → C₆H₁₂O₆ + 6O₂

Key Points:
• Occurs primarily in the leaves of plants
• Requires chlorophyll to capture light energy
• Takes place in two main stages: light reactions and dark reactions
• Essential for the food chain and oxygen production`,
            keyTerms: ['Photosynthesis', 'Chlorophyll', 'Glucose', 'Light reactions'],
        },
        {
            page: 2,
            title: 'The Two Stages of Photosynthesis',
            content: `Photosynthesis occurs in two interconnected stages:

Light-Dependent Reactions (Photo Stage):
- Occur in the thylakoids of chloroplasts
- Chlorophyll absorbs light energy
- Water molecules are split, releasing oxygen
- Energy is captured in ATP and NADPH

Light-Independent Reactions (Calvin Cycle):
- Take place in the stroma of chloroplasts
- Use ATP and NADPH from light reactions
- Carbon dioxide is converted into glucose
- Does not directly require light but depends on products from light reactions

These stages work together to convert solar energy into chemical energy that plants can use for growth and development.`,
            keyTerms: ['Thylakoids', 'Calvin Cycle', 'ATP', 'NADPH'],
        },
    ],
};

// Computed properties
const currentContent = computed(() => lesson.content[currentPage.value - 1]);

// Methods
const toggleAudio = () => {
    isPlaying.value = !isPlaying.value;
};

const toggleBookmark = () => {
    isBookmarked.value = !isBookmarked.value;
};

const toggleReadingMode = () => {
    const modes: Array<'normal' | 'focus' | 'dark'> = ['normal', 'focus', 'dark'];
    const currentIndex = modes.indexOf(readingMode.value);
    readingMode.value = modes[(currentIndex + 1) % modes.length];
};

const addNote = () => {
    if (currentNote.value.trim()) {
        const newNote = {
            id: notes.value.length + 1,
            text: currentNote.value,
            page: currentPage.value,
            timestamp: 'just now',
        };
        notes.value.unshift(newNote);
        currentNote.value = '';
    }
};

const nextPage = () => {
    if (currentPage.value < lesson.totalPages) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const setCurrentPage = (page: number) => {
    currentPage.value = page;
};
</script>

<template>
    <Head title="Lesson Reader - Introduction to Photosynthesis" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            :class="[
                'flex h-full flex-1 flex-col gap-4 rounded-xl p-4 transition-colors duration-300',
                {
                    'bg-amber-50/50': readingMode === 'focus',
                    'bg-slate-900/20': readingMode === 'dark',
                },
            ]"
        >
            <!-- Header Controls -->
            <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between rounded-xl border p-4">
                <div class="flex items-center space-x-4">
                    <div>
                        <h1 class="text-foreground text-xl font-bold">{{ lesson.title }}</h1>
                        <div class="text-muted-foreground flex items-center space-x-4 text-sm">
                            <span class="flex items-center"> <Book class="mr-1 h-4 w-4" />{{ lesson.subject }} </span>
                            <span class="flex items-center"> <User class="mr-1 h-4 w-4" />{{ lesson.instructor }} </span>
                            <span class="flex items-center"> <Clock class="mr-1 h-4 w-4" />{{ lesson.duration }} </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Reading Controls -->
                    <button
                        @click="showSettings = !showSettings"
                        class="bg-muted hover:bg-muted/80 rounded-lg p-2 transition-colors"
                        title="Settings"
                    >
                        <Settings class="h-5 w-5" />
                    </button>

                    <button
                        @click="toggleReadingMode"
                        class="bg-muted hover:bg-muted/80 rounded-lg p-2 transition-colors"
                        title="Toggle reading mode"
                    >
                        <Eye class="h-5 w-5" />
                    </button>

                    <button @click="toggleAudio" class="bg-muted hover:bg-muted/80 rounded-lg p-2 transition-colors" title="Text to speech">
                        <Pause v-if="isPlaying" class="h-5 w-5" />
                        <Play v-else class="h-5 w-5" />
                    </button>

                    <button @click="toggleBookmark" class="bg-muted hover:bg-muted/80 rounded-lg p-2 transition-colors">
                        <BookmarkCheck v-if="isBookmarked" class="text-primary h-5 w-5" />
                        <Bookmark v-else class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <!-- Settings Panel -->
            <div v-if="showSettings" class="bg-card border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-foreground font-semibold">Reading Settings</h3>
                    <button @click="showSettings = false" class="text-muted-foreground hover:text-foreground">✕</button>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-muted-foreground text-sm">Font Size:</label>
                        <select
                            v-model="fontSize"
                            class="bg-background border-sidebar-border/70 dark:border-sidebar-border text-foreground rounded border px-3 py-1"
                        >
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-muted-foreground text-sm">Reading Mode:</label>
                        <span class="text-foreground text-sm capitalize">{{ readingMode }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                <div class="text-muted-foreground mb-2 flex items-center justify-between text-sm">
                    <span>Reading Progress</span>
                    <span>{{ readingProgress }}% Complete</span>
                </div>
                <div class="bg-muted h-2 w-full rounded-full">
                    <div class="bg-primary h-2 rounded-full transition-all duration-300" :style="{ width: `${readingProgress}%` }"></div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex flex-1 gap-4">
                <!-- Content -->
                <div class="flex-1">
                    <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border h-full rounded-xl border p-6">
                        <!-- Page Header -->
                        <div class="mb-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-foreground text-2xl font-bold">
                                    {{ currentContent.title }}
                                </h2>
                                <span class="text-muted-foreground bg-muted rounded-full px-3 py-1 text-sm">
                                    Page {{ currentPage }} of {{ lesson.totalPages }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div
                            :class="[
                                'prose prose-gray dark:prose-invert mb-8 max-w-none',
                                {
                                    'prose-sm': fontSize === 'small',
                                    'prose-lg': fontSize === 'large',
                                    'prose-base': fontSize === 'medium',
                                },
                            ]"
                        >
                            <div class="text-foreground leading-relaxed whitespace-pre-line">
                                {{ currentContent.content }}
                            </div>
                        </div>

                        <!-- Key Terms -->
                        <div v-if="currentContent.keyTerms" class="bg-primary/5 border-primary/20 mb-8 rounded-lg border p-4">
                            <h3 class="text-primary mb-3 font-semibold">Key Terms:</h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(term, index) in currentContent.keyTerms"
                                    :key="index"
                                    class="bg-primary/10 text-primary border-primary/20 rounded-full border px-3 py-1 text-sm font-medium"
                                >
                                    {{ term }}
                                </span>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between border-t pt-6">
                            <button
                                @click="prevPage"
                                :disabled="currentPage === 1"
                                class="bg-muted hover:bg-muted/80 text-foreground flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                <span>Previous</span>
                            </button>

                            <div class="flex items-center space-x-2">
                                <button
                                    v-for="page in Math.min(5, lesson.totalPages)"
                                    :key="page"
                                    @click="setCurrentPage(page)"
                                    :class="[
                                        'h-8 w-8 rounded-full text-sm font-medium transition-colors',
                                        currentPage === page ? 'bg-primary text-primary-foreground' : 'bg-muted hover:bg-muted/80 text-foreground',
                                    ]"
                                >
                                    {{ page }}
                                </button>
                                <span v-if="lesson.totalPages > 5" class="text-muted-foreground">...</span>
                            </div>

                            <button
                                @click="nextPage"
                                :disabled="currentPage === lesson.totalPages"
                                class="bg-primary text-primary-foreground hover:bg-primary/90 flex items-center space-x-2 rounded-lg px-4 py-2 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span>Next</span>
                                <ChevronRight class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="w-80 space-y-4">
                    <!-- Quick Actions -->
                    <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                        <h3 class="text-foreground mb-4 font-semibold">Quick Actions</h3>
                        <div class="space-y-2">
                            <button class="hover:bg-muted/50 text-foreground flex w-full items-center space-x-3 rounded-lg p-3 transition-colors">
                                <Download class="text-primary h-5 w-5" />
                                <span>Download PDF</span>
                            </button>
                            <button class="hover:bg-muted/50 text-foreground flex w-full items-center space-x-3 rounded-lg p-3 transition-colors">
                                <MessageCircle class="h-5 w-5 text-green-600" />
                                <span>Ask AI Assistant</span>
                            </button>
                            <button class="hover:bg-muted/50 text-foreground flex w-full items-center space-x-3 rounded-lg p-3 transition-colors">
                                <CheckCircle class="h-5 w-5 text-purple-600" />
                                <span>Take Quiz</span>
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-foreground flex items-center font-semibold">
                                <NotebookPen class="mr-2 h-4 w-4" />
                                My Notes
                            </h3>
                            <button @click="showNotes = !showNotes" class="text-primary hover:text-primary/80 text-sm">
                                {{ showNotes ? 'Hide' : 'Show All' }}
                            </button>
                        </div>

                        <div class="mb-4 space-y-3">
                            <textarea
                                v-model="currentNote"
                                placeholder="Add a note for this page..."
                                class="border-sidebar-border/70 dark:border-sidebar-border bg-background text-foreground placeholder:text-muted-foreground w-full resize-none rounded-lg border p-3"
                                rows="3"
                            />
                            <button
                                @click="addNote"
                                :disabled="!currentNote.trim()"
                                class="bg-primary text-primary-foreground hover:bg-primary/90 w-full rounded-lg py-2 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                Add Note
                            </button>
                        </div>

                        <div v-if="showNotes" class="max-h-64 space-y-3 overflow-y-auto">
                            <div v-for="note in notes" :key="note.id" class="bg-muted/50 border-sidebar-border/50 rounded-lg border p-3">
                                <p class="text-foreground mb-1 text-sm">{{ note.text }}</p>
                                <div class="text-muted-foreground flex items-center justify-between text-xs">
                                    <span>Page {{ note.page }}</span>
                                    <span>{{ note.timestamp }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Info -->
                    <div class="bg-card border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                        <h3 class="text-foreground mb-4 font-semibold">Lesson Information</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Grade Level:</span>
                                <span class="text-foreground font-medium">{{ lesson.grade }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Duration:</span>
                                <span class="text-foreground font-medium">{{ lesson.duration }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Pages:</span>
                                <span class="text-foreground font-medium">{{ lesson.totalPages }}</span>
                            </div>
                            <div class="border-sidebar-border/70 dark:border-sidebar-border border-t pt-3">
                                <p class="text-muted-foreground text-xs leading-relaxed">
                                    {{ lesson.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
