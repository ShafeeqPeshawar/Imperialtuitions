@extends('layouts.admin')

@section('content')

<div class="max-w-xl">

    <h2 class="text-2xl font-semibold text-black mb-6">
        Edit Course Launch Date
    </h2>

    <form method="POST"
          action="{{ route('admin.course-launches.update', $courseLaunch) }}"
          class="bg-white p-6 rounded-lg shadow space-y-4">

        @csrf
        @method('PUT')

        <!-- Course Dropdown -->
        <div>
            <label class="block text-sm font-semibold mb-1">
                Course
            </label>

            <select name="course_id"
                    class="w-full border rounded px-3 py-2" style="color:#000;">

                @foreach($courses as $course)
                    <option value="{{ $course->id }}"
                        {{ $courseLaunch->course_id == $course->id ? 'selected' : '' }}>
{{ $course->title }} ({{ ucfirst($course->level) }})
                    </option>
                @endforeach

            </select>
        </div>

        <!-- Launch Date -->
        <div>
            <label class="block text-sm font-semibold mb-1" style="color:#000;">
                Launch Date
            </label>

            <input type="date"
                   name="launch_date"
                   value="{{ $courseLaunch->launch_date }}"
                   class="w-full border rounded px-3 py-2" style="color:#000;">
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="bg-yellow-500 px-6 py-2 rounded font-semibold">
                Update
            </button>

            <a href="{{ route('admin.course-launches.index') }}"
               class="bg-gray-200 px-6 py-2 rounded font-semibold" style="color:#000;">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
