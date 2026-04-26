<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;

class CourseTopicController extends Controller
{
    // Show topics list + add form
    public function index(Course $course)
    {
        $topics = $course->topics()
            ->orderBy('sort_order')
            ->get();

        return view('admin.courses.topics.index', compact('course', 'topics'))->with('title', 'Manage Topics');
    }

    // Store new topic
 public function store(Request $request, Course $course)
{
    $request->validate([
        'title'      => 'required|string|max:255',
        'description'=> 'nullable|string',
        'sort_order' => 'nullable|integer',
    ]);

    // ✅ Auto-generate sort_order per course
    if (!$request->sort_order) {
        $lastSortOrder = $course->topics()->max('sort_order'); // current course ka max
        $sortOrder = $lastSortOrder ? $lastSortOrder + 10 : 10;
    } else {
        $sortOrder = $request->sort_order;
    }

    $course->topics()->create([
        'title'      => $request->title,
        'description'=> $request->description,
        'sort_order' => $sortOrder,
        'is_active'  => $request->has('is_active'),
    ]);

    return redirect()
        ->route('admin.courses.topics', $course)
        ->with('success', 'Topic added successfully');
}
    // Edit topic page
    public function edit(CourseTopic $topic)
    {
        $course = $topic->course;
        return view('admin.courses.topics.edit', compact('topic', 'course'));
    }

    // Update topic
    public function update(Request $request, CourseTopic $topic)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        $topic->update([
            'title'      => $request->title,
            'description'=> $request->description,
            'sort_order' => $request->sort_order,
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.courses.topics', $topic->course)
            ->with('success', 'Topic updated successfully');
    }

    // Delete topic
    public function destroy(CourseTopic $topic)
    {
        $course = $topic->course;
        $topic->delete();

        return redirect()
            ->route('admin.courses.topics', $course)
            ->with('success', 'Topic deleted successfully');
    }
}
