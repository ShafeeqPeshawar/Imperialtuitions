{{-- resources/views/admin/course-launches/create.blade.php --}}

@extends('layouts.admin')

@section('content')

<h2 class="text-2xl font-semibold text-black mb-6">Add Course Launch Date</h2>

<form method="POST" action="{{ route('admin.course-launches.store') }}"
      class="bg-white p-6 rounded-lg space-y-4 max-w-xl">

    @csrf

    <div>
        <label class="block text-sm font-semibold" style="color:#000;">Course</label>
        <select name="course_id"
        class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
        style="color:#000;"
        required>

    <option value="">Select Course</option>

    {{-- FREE COURSES --}}
    <optgroup label="Free Courses">
        @foreach($courses->where('price', 0) as $course)
            <option value="{{ $course->id }}" data-type="free">
                {{ $course->title }} ({{ ucfirst($course->level) }})
            </option>
        @endforeach
    </optgroup>

    {{-- PAID COURSES --}}
    <!-- <optgroup label="Paid Courses">
        @foreach($courses->where('price', '>', 0) as $course)
            <option value="{{ $course->id }}" data-type="paid">
                {{ $course->title }} ({{ ucfirst($course->level) }})
            </option>
        @endforeach
    </optgroup> -->

</select>

<p id="freeHint"
   class="hidden mt-2 text-sm"
   style="color:#09515D;font-weight:600;">
    This is a free course. It will appear under Upcoming Free Courses.
</p>



    </div>

    <div>
        <label class="block text-sm font-semibold"style="color:#000;">Launch Date</label>
        <input type="date" name="launch_date"
               class="w-full border rounded px-3 py-2" style="color:#000;">
    </div>

    <button class="bg-yellow-500 px-6 py-2 rounded font-semibold">
        Save
    </button>

</form>

@endsection
<style>
    /* Admin select polish */
select optgroup {
    font-weight: 700;
    color: #374151;
}

select option {
    font-weight: 500;
    color: #111827;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.querySelector('select[name="course_id"]');
    const hint = document.getElementById('freeHint');

    select.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        hint.classList.toggle('hidden', option.dataset.type !== 'free');
    });
});
</script>
