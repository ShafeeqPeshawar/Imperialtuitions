<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /* ==============================
     | INDEX
     ============================== */
    public function index()
    {
        $courses = Course::with('category')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('admin.courses.index', compact('courses'))->with('title', 'Manage Courses');
    }

    /* ==============================
     | CREATE
     ============================== */
    public function create()
    {
        $categories = TrainingCategory::orderBy('name')->get();

        return view('admin.courses.create', compact('categories'))->with('title', 'Add Course');
    }

    /* ==============================
     | STORE
     ============================== */
    public function store(Request $request)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'required|string|min:10', 
            'image'                 => 'required|image|mimes:png,jpg,jpeg,webp',
            'level'                 => 'nullable|string|max:100',
            'duration_value' => 'required|integer|min:1',
            'duration_unit'  => 'required|string|in:hours,days,weeks,months',
            'price'                 => 'nullable|numeric',
            'skills'                => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'training_category_id'  => 'nullable|exists:training_categories,id',
        ]);
$exists = Course::where('title', $request->title)
    ->where('level', $request->level)
    ->exists();

if ($exists) {
    return back()
        ->withInput()
        ->withErrors([
            'level' => 'A course with this title already exists in this level!'
        ]);
}
// Auto-generate sort_order if empty
if (!$request->sort_order) {
    $lastSortOrder = Course::max('sort_order');
    $sortOrder = $lastSortOrder ? $lastSortOrder + 10 : 10;
} else {
    $sortOrder = $request->sort_order;
}    
// Upload image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Course::create([
            'title'                => $request->title,
            'description'          => $request->description,
            'image'                => $imageName,
            'level'                => $request->level,
            'duration' => $request->duration_value . ' ' . ucfirst($request->duration_unit),
            'price'                => $request->price,
            'skills'               => $request->skills,
            'sort_order' => $sortOrder,
            'is_active'            => $request->has('is_active'),
            'training_category_id' => $request->training_category_id, // ✅ CATEGORY
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course added successfully');
    }

    /* ==============================
     | EDIT
     ============================== */
    public function edit(Course $course)
    {
        $categories = TrainingCategory::orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories'))->with('title', 'Edit Course');
    }

    /* ==============================
     | UPDATE
     ============================== */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'required|string',
            'image'                 => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'level'                 => 'nullable|string|max:100',
            'duration_value' => 'required|integer|min:1',
            'duration_unit'  => 'required|string|in:hours,days,weeks,months',
            'price'                 => 'nullable|numeric',
            'skills'                => 'nullable|string',
            'sort_order'            => 'required|integer',
            'training_category_id'  => 'nullable|exists:training_categories,id',
        ]);

        $imageName = $course->image;

        if ($request->hasFile('image')) {
            $oldPath = public_path('images/' . $course->image);
            if ($course->image && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }
$exists = Course::where('title', $request->title)
    ->where('level', $request->level)
    ->where('id', '!=', $course->id)
    ->exists();

if ($exists) {
    return back()
        ->withInput()
        ->withErrors([
            'level' => 'A course with this title already exists in this level!'
        ]);
}
        $course->update([
            'title'                => $request->title,
            'description'          => $request->description,
            'image'                => $imageName,
            'level'                => $request->level,
            'duration' => $request->duration_value . ' ' . ucfirst($request->duration_unit),
            'price'                => $request->price,
            'skills'               => $request->skills,
            'sort_order'           => $request->sort_order,
            'is_active'            => $request->has('is_active'),
            'training_category_id' => $request->training_category_id, // ✅ CATEGORY
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully');
    }

    /* ==============================
     | DELETE
     ============================== */
    public function destroy(Course $course)
    {
        $imagePath = public_path('images/' . $course->image);

        if ($course->image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $course->delete();

        return back()->with('success', 'Course deleted successfully');
    }

    /* ==============================
     | SEARCH (FRONTEND)
     ============================== */
    public function search(Request $request)
    {
        $query = $request->q;

        $courses = Course::where('is_active', 1)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhere('skills', 'like', "%$query%");
            })
            ->orderBy('sort_order')
            ->get();

        return view('partials.course-cards', compact('courses'));
    }
    public function makePopular(Request $request)
{
    if (!$request->selected_courses) {
        return back();
    }

    Course::whereIn('id', $request->selected_courses)
          ->update(['is_popular' => true]);

    return back()->with('success', 'Selected courses added to Popular Courses.');
}
public function popular()
{
    $courses = Course::where('is_popular', true)
        ->orderBy('sort_order')
        ->get();

    return view('admin.courses.popular', compact('courses'))->with('title', 'Popular Courses');
}
public function removePopular(Request $request)
{
    if (!$request->selected_courses) {
        return back()->with('success', 'No courses selected.');
    }

    Course::whereIn('id', $request->selected_courses)
          ->update(['is_popular' => false]);

    return back()->with('success', 'Selected courses removed from Popular.');
}
}