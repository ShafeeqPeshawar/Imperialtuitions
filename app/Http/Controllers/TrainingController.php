<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingCategory;
use App\Models\TrainingImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    /* ==============================
     | INDEX
     ============================== */
    public function index()
    {
        // ORDER BY sort_order
        $categories = TrainingCategory::with('images')
            ->orderBy('sort_order')
            ->get();

        return view('admin.training.index', compact('categories'));
    }

    /* ==============================
     | CATEGORY CRUD
     ============================== */
    public function createCategory()
    {
        return view('admin.training.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
      'name'       => 'required|string|max:255|unique:training_categories,name', // ✅ added unique

            'sort_order' => 'nullable|integer|min:0',
        ]);

    // Auto-generate sort_order if empty
if (!$request->sort_order) {

    $lastSortOrder = TrainingCategory::max('sort_order');

    $sortOrder = $lastSortOrder
        ? $lastSortOrder + 10
        : 10;

} else {

    $sortOrder = $request->sort_order;

}

TrainingCategory::create([
    'name'       => $request->name,
    'slug'       => Str::slug($request->name),
    'sort_order' => $sortOrder,
]);
        return redirect()
            ->route('training.categories.index')
            ->with('success', 'Category added');
    }

    public function editCategory(TrainingCategory $category)
    {
        return view('admin.training.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, TrainingCategory $category)
    {
        $request->validate([
                'name'       => 'required|string|max:255|unique:training_categories,name,' . $category->id, // ✅ unique with ignore
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => $request->sort_order ?? 0,   // 👈 added
        ]);

        return redirect()
            ->route('training.categories.index')
            ->with('success', 'Category updated');
    }

    /* ==============================
     | IMAGE CRUD
     ============================== */
    public function createImage()
    {
        // ORDER BY sort_order so dropdown matches frontend order
        $categories = TrainingCategory::orderBy('sort_order')->get();

        return view('admin.training.images.create', compact('categories'));
    }

    public function storeImage(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:training_categories,id',
        'image'       => 'required|image|mimes:png,jpg,jpeg,webp',
    ]);

    $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();

    $path = $request->file('image')->storeAs(
        'training',
        $imageName,
        'public'
    );

    TrainingImage::create([
        'training_category_id' => $request->category_id,
        'image' => $path, // ✅ save path, not filename
    ]);

    return redirect()
        ->route('training.index')
        ->with('success', 'Image added');
}


    public function editImage(TrainingImage $image)
    {
        $categories = TrainingCategory::orderBy('sort_order')->get();

        return view('admin.training.images.edit', compact('image', 'categories'));
    }

   public function updateImage(Request $request, TrainingImage $image)
{
    $request->validate([
        'category_id' => 'required|exists:training_categories,id',
        'image'       => 'nullable|image|mimes:png,jpg,jpeg,webp',
    ]);

    $path = $image->image;

    if ($request->hasFile('image')) {
        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();

        $path = $request->file('image')->storeAs(
            'training',
            $imageName,
            'public'
        );
    }

    $image->update([
        'training_category_id' => $request->category_id,
        'image' => $path,
    ]);

    return redirect()
        ->route('training.index')
        ->with('success', 'Image updated');
}


    public function destroyImage(TrainingImage $image)
{
    if ($image->image) {
        Storage::disk('public')->delete($image->image);
    }

    $image->delete();

    return back()->with('success', 'Image deleted');
}


    public function categoriesIndex()
    {
        // ORDER BY sort_order
        $categories = TrainingCategory::orderBy('sort_order')->get();

        return view('admin.training.categories.index', compact('categories'));
    }

    public function destroyCategory(TrainingCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('training.categories.index')
            ->with('success', 'Category deleted');
    }
}
