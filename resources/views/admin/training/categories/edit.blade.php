@extends('layouts.admin')

@section('content')

<div class="max-w-3xl">

    <!-- Page Title -->
    <h2 class="text-2xl font-semibold text-black mb-6">
        Edit Training Category
    </h2>

    <!-- Form Card -->
    <div class="bg-white text-black rounded-lg shadow-lg p-6">

        <form method="POST"
              action="{{ route('training.categories.update', $category) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Category Name -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Category Name
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ $category->name }}"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500"
                >
            </div>
            <!-- Sort Order -->
<div>
    <label class="block text-sm font-medium mb-1">
        Sort Order
    </label>
    <input
        type="number"
        name="sort_order"
        value="{{ old('sort_order', $category->sort_order) }}"
        min="0"
        class="w-full rounded-md border border-gray-300 px-4 py-2
               focus:outline-none focus:ring-2 focus:ring-yellow-500"
    >
    <p class="text-xs text-gray-500 mt-1">
        Lower number = shown first
    </p>
</div>


            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <button
                    type="submit"
                    class="bg-yellow-500 text-black font-semibold
                           px-6 py-2 rounded-md
                           hover:bg-yellow-400 transition">
                    Update Category
                </button>

                <a href="{{ route('training.categories.index') }}"
                   class="text-sm text-gray-600 hover:text-black">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
