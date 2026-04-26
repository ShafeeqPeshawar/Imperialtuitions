@extends('layouts.admin')

@section('content')

<!-- Toast Container -->
<div id="toast-container" class="fixed top-6 right-6 z-50 space-y-3"></div>

<h2 class="text-2xl font-semibold mb-6 text-black">
    Popular Courses
</h2>

<form id="removePopularForm" method="POST" action="{{ route('admin.courses.removePopular') }}">
    @csrf

    <!-- Same Yellow Button Style -->
    <button type="submit"
        class="bg-yellow-500 text-black font-semibold
               px-4 py-2 rounded-md
               hover:bg-yellow-400 transition flex items-center gap-2 mb-4">
        ⭐ Remove From Popular
    </button>

    <div class="bg-white text-black rounded-lg shadow-lg overflow-hidden">

        <table class="min-w-full border-collapse">
            <thead class="bg-black text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Course</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold">Select</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($courses as $course)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium">
                        {{ $course->title }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <input type="checkbox"
                               name="selected_courses[]"
                               value="{{ $course->id }}"
                               class="popular-checkbox">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                        No popular courses found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</form>


<!-- Toast Script (Same System As Previous Page) -->
<style>
@keyframes progress { from { width:100%; } to { width:0%; } }
.animate-progress { animation: progress 3s linear forwards; }
</style>

<script>
function showToast(message, type = 'success') {

    const container = document.getElementById('toast-container');

    const colors = {
        success: 'border-yellow-500 bg-gray-900 text-white',
        error: 'border-red-500 bg-gray-900 text-white'
    };

    const toast = document.createElement('div');
    toast.className = `
        ${colors[type]}
        border rounded-xl shadow-2xl w-96
        transform transition-all duration-500
        translate-x-full opacity-0
    `;

    toast.innerHTML = `
        <div class="flex items-start p-5 gap-4">
            <div class="rounded-full w-10 h-10 flex items-center justify-center font-bold
                ${type === 'success' ? 'bg-yellow-500 text-black' : 'bg-red-500 text-white'}">
                ${type === 'success' ? '✓' : '!'}
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-sm ${type === 'success' ? 'text-yellow-400' : 'text-red-400'}">
                    ${type === 'success' ? 'Success' : 'Error'}
                </h4>
                <p class="text-sm text-gray-300 mt-1">${message}</p>
            </div>
        </div>
        <div class="h-1 ${type === 'success' ? 'bg-yellow-500' : 'bg-red-500'} animate-progress"></div>
    `;

    container.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-full','opacity-0'), 50);

    setTimeout(() => {
        toast.classList.add('translate-x-full','opacity-0');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>


<!-- Success Toast -->
@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast(@json(session('success')), 'success');
});
</script>
@endif


<!-- Error if No Selection -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('removePopularForm');

    form.addEventListener('submit', function(e) {

        const checked = document.querySelectorAll('.popular-checkbox:checked');

        if (checked.length === 0) {
            e.preventDefault();
            showToast('Please select at least one course first.', 'error');
        }

    });

});
</script>

@endsection