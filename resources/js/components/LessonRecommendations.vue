<script setup lang="ts">
import { defineProps } from 'vue';
import { type LessonRecommendation } from '@/types/LessonReader';

defineProps<{
    recommendations: LessonRecommendation[];
}>();

const emit = defineEmits<{
    (e: 'show-recommendations'): void;
}>();

const showMoreRecommendations = () => {
    emit('show-recommendations');
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recommended Lessons</h3>
            <button
                @click="showMoreRecommendations"
                class="text-sm text-blue-500 hover:text-blue-600"
            >
                Show More
            </button>
        </div>

        <div class="space-y-4">
            <div v-for="(recommendation, index) in recommendations" :key="index" class="p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ recommendation.lesson.title }}</h4>
                        <p class="text-sm text-gray-500">{{ recommendation.lesson.subject }} - {{ recommendation.lesson.grade_level }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ recommendation.reason }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ recommendation.relevance }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium" 
                              :class="recommendation.difficulty === 'easier' ? 'bg-green-100 text-green-800' : 
                                         recommendation.difficulty === 'similar' ? 'bg-yellow-100 text-yellow-800' : 
                                         'bg-red-100 text-red-800'">
                            {{ recommendation.difficulty === 'easier' ? 'Beginner' : 
                               recommendation.difficulty === 'similar' ? 'Intermediate' : 
                               'Advanced' }}
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ recommendation.estimatedTime }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
