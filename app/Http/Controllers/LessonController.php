<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('createdBy')->latest()->paginate(10);
        return Inertia::render('lessons/Index', [
            'lessons' => $lessons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('lessons/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subject' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
        ]);

        $lesson = Lesson::create([
            'title' => $request->title,
            'content' => $request->content,
            'subject' => $request->subject,
            'grade_level' => $request->grade_level,
            'status' => $request->status ?? 'draft',
            'created_by' => Auth::id(),
            'updated_by' => null,
        ]);

        return redirect()->route('lessons.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        return Inertia::render('lessons/Show', [
            'lesson' => $lesson->load(['createdBy' => function ($query) {
                $query->select('id', 'name');
            }])->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        return Inertia::render('lessons/Edit', [
            'lesson' => $lesson,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subject' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'status' => 'in:draft,active',
        ]);

        $lesson->update([
            'title' => $request->title,
            'content' => $request->content,
            'subject' => $request->subject,
            'grade_level' => $request->grade_level,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('lessons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('lessons.index');
    }
}
