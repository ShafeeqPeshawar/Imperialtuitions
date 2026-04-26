@extends('layouts.admin')

@section('content')

<div class="max-w-3xl">

    <!-- Page Title -->
    <h2 class="text-2xl font-semibold text-black mb-6">
        Add Training Category
    </h2>

    <!-- Form Card -->
    <div class="bg-white text-black rounded-lg shadow-lg p-6">
@if ($errors->any())
<div id="errorPopup" class="custom-error-alert">
    Category with this name already exists.
</div>

<script>
setTimeout(function(){
    document.getElementById('errorPopup').style.display = 'none';
}, 3000);
</script>
@endif
        <form method="POST"
              action="{{ route('training.categories.store') }}"
              class="space-y-5">
            @csrf

            <!-- Category Name -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Category Name
                </label>
                <input
                    type="text"
                    name="name"
                    placeholder="Enter category name"
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
    @php
        $defaultSortOrder = (\App\Models\TrainingCategory::max('sort_order') ?? 0) + 10;
    @endphp
    <input
        type="number"
        name="sort_order"
        placeholder="0"
        value="{{ old('sort_order', $defaultSortOrder) }}"
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
                    Save Category
                </button>


                <a href="{{ route('training.categories.index') }}"
                   class="text-sm text-gray-600 hover:text-black">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
<style>
.custom-error-alert {
    background: #fee2e2;
    border-left: 6px solid #ef4444;
    color: #991b1b;
    padding: 14px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 18px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    animation: slideDown 0.4s ease;
}

/* animation */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
