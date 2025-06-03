export interface LessonContext {
    currentPage: number;
    highlightedText: string;
    activeSection: string;
    readingProgress: number;
    timeSpent: string;
}

export interface Lesson {
    id: number;
    title: string;
    content: string;
    subject: string;
    grade_level: string;
    status: 'draft' | 'active';
    created_by: number;
    created_at: string;
    updated_at: string;
    total_pages: number;
    createdBy?: {
        id: number;
        name: string;
    };
}


export interface ChatMessage {
    id: string;
    content: string;
    sender: 'user' | 'ai';
    timestamp: string;
    references?: string[];
    difficulty?: string;
}

export interface LessonRecommendation {
    lesson: {
        id: number;
        title: string;
        subject: string;
        grade_level: string;
    };
    relevanceScore: number;
    reason: string;
    difficulty: 'easier' | 'similar' | 'advanced' | string;
    estimatedTime: string;
}

export interface AIResponse {
    answer: string;
    references: string[];
    difficulty: string;
    suggestions: string[];
    relatedQuestions: string[];
}
