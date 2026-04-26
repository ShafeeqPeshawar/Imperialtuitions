<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\TrainingCategory;

class WebsiteController extends Controller
{
    /**
     * Homepage – show courses grouped by title
     */
    public function index()
    {
        // Fetch only active courses
        $courses = Course::where('is_active', true)
            ->with('nextLaunch') // Load nextLaunch relation
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Fetch categories in custom order
        $categories = TrainingCategory::with('images')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('index', compact('courses', 'categories'));
    }

    /**
     * Public training gallery page
     */
    public function training()
    {
        $categories = TrainingCategory::with('images')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('training-page', compact('categories'));
    }

    /**
     * Course detail page
     */
    public function show(Course $course)
    {
        // Prevent inactive course access
        abort_if(!$course->is_active, 404);

        // Load only active topics
        $course->load([
            'topics' => function ($q) {
                $q->where('is_active', 1)
                  ->orderBy('sort_order');
            }
        ]);

        // Sidebar – only courses in the same category, excluding current course
        $sidebarHeading = 'Courses you may also like ';
        $sidebarCourses = collect(); // Default empty collection

        if ($course->training_category_id) {
            $sidebarCourses = Course::where('is_active', true)
                ->where('training_category_id', $course->training_category_id)
                ->where('id', '!=', $course->id) // exclude current course
                ->with('nextLaunch')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(12)
                ->get();
        }

        return view('show', compact('course', 'sidebarCourses', 'sidebarHeading'));
    }

    /**
     * Switch course level from detail page
     * (Beginner → Intermediate → Advanced)
     */
    public function switchLevel(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'level' => 'required|string',
        ]);

        $course = Course::where('title', $request->title)
            ->where('level', $request->level)
            ->where('is_active', 1)
            ->first();

        if (!$course) {
            return response()->json([
                'found' => false,
                'message' => 'No course available right now for this level'
            ]);
        }

        return response()->json([
            'found' => true,
            'url' => route('show', $course->id)
        ]);
    }
}