@extends('layouts.admin')

@section('content')
@php
    $durationValue = '';
    $durationUnit = '';

    if (!empty($course->duration)) {
        $parts = explode(' ', trim($course->duration));
        $durationValue = $parts[0] ?? '';
        $durationUnit = strtolower($parts[1] ?? '');
    }
@endphp

<div class="max-w-3xl">

    <!-- Page Title -->
    <h2 class="text-2xl font-semibold text-black mb-6">
        Edit Course
    </h2>

    <!-- Form Card -->
    <div class="bg-white text-black rounded-lg shadow-lg p-6">

        <form id="courseForm"
              method="POST"
              enctype="multipart/form-data"
              action="{{ route('admin.courses.update', $course) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Course Title -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Course Title
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ $course->title }}"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Course Category -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Course Category
                </label>

                <select
                    name="training_category_id"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">

                    <option value="">Select Category</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $course->training_category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Course Description -->
            <div>
                <label class="block text-sm font-medium mb-1">Course Description</label>

         
                    <div id="editor" class="bg-white border border-gray-300 rounded-md"></div>

                    <!-- Hidden input for backend -->
                    <input type="hidden" name="description" id="description">

                    
               
            </div>

            <!-- Course Level -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Course Level
                </label>
                <select
                    name="level"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <option value="">Select Level</option>
                    <option value="Beginner" {{ $course->level === 'Beginner' ? 'selected' : '' }}>
                        Beginner
                    </option>
                    <option value="Intermediate" {{ $course->level === 'Intermediate' ? 'selected' : '' }}>
                        Intermediate
                    </option>
                    <option value="Advanced" {{ $course->level === 'Advanced' ? 'selected' : '' }}>
                        Advanced
                    </option>
                </select>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Duration
                </label>

                <div class="flex gap-3">
                    <input
                        type="number"
                        name="duration_value"
                        min="1"
                        required
                        value="{{ $durationValue }}"
                        placeholder="e.g. 6"
                        class="w-1/2 rounded-md border border-gray-300 px-4 py-2
                               focus:outline-none focus:ring-2 focus:ring-yellow-500">

                    <select
                        name="duration_unit"
                        required
                        class="w-1/2 rounded-md border border-gray-300 px-4 py-2
                               focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">Select Unit</option>
                        <option value="hours" {{ $durationUnit === 'hours' ? 'selected' : '' }}>Hours</option>
                        <option value="days" {{ $durationUnit === 'days' ? 'selected' : '' }}>Days</option>
                        <option value="weeks" {{ $durationUnit === 'weeks' ? 'selected' : '' }}>Weeks</option>
                        <option value="months" {{ $durationUnit === 'months' ? 'selected' : '' }}>Months</option>
                    </select>
                </div>
            </div>

            <!-- Price -->
            <div>
                @php
    $selectedCategory = $categories->firstWhere('id', $course->training_category_id);
    $isFreeCourseCategory = $selectedCategory && strtolower(trim($selectedCategory->name)) === 'free courses';
@endphp

<label class="block text-sm font-medium mb-1">
    Price ($)
</label>
<input
    type="number"
    step="0.01"
    name="price"
    id="price"
    value="{{ $isFreeCourseCategory ? 0 : $course->price }}"
    {{ $isFreeCourseCategory ? 'readonly' : '' }}
    class="w-full rounded-md border border-gray-300 px-4 py-2
           focus:outline-none focus:ring-2 focus:ring-yellow-500 {{ $isFreeCourseCategory ? 'bg-gray-100 cursor-not-allowed' : '' }}">
            </div>

            <!-- Skills / Prerequisites -->
            <div>
                <label class="block text-sm font-medium mb-2">
                    Skills / Prerequisites
                </label>

                <div class="flex gap-2">
                    <input
                        type="text"
                        id="skillInput"
                        placeholder="Type a skill and press Add"
                        class="flex-1 rounded-md border border-gray-300 px-4 py-2
                               focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <button
                        type="button"
                        onclick="addSkill()"
                        class="bg-black text-white px-4 py-2 rounded-md
                               hover:bg-yellow-500 hover:text-black transition">
                        Add
                    </button>
                </div>

                <div id="skillsContainer" class="flex flex-wrap gap-2 mt-3"></div>

                <input type="hidden" name="skills" id="skillsField">

                <div id="skillsData"
                     data-skills='@json($course->skills ? explode(",", $course->skills) : [])'>
                </div>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Sort Order
                </label>
                <input
                    type="number"
                    name="sort_order"
                    value="{{ $course->sort_order }}"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Current Image -->
            <div>
                <label class="block text-sm font-medium mb-2">
                    Current Image
                </label>
                <div class="flex items-center gap-4">
                    <img
                        src="{{ asset('images/'.$course->image) }}"
                        class="w-28 rounded border">
                    <span class="text-sm text-gray-600">
                        Upload a new image to replace this
                    </span>
                </div>
            </div>

            <!-- Replace Image -->
            <div>
                <label class="block text-sm font-medium mb-1">
                    Replace Image
                </label>
                <input
                    type="file"
                    name="image"
                    class="w-full text-sm border border-gray-300 rounded-md
                           file:bg-black file:text-white
                           file:px-4 file:py-2 file:border-0
                           hover:file:bg-yellow-500 hover:file:text-black">
            </div>

            <!-- Status -->
            <div class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    {{ $course->is_active ? 'checked' : '' }}
                    class="h-4 w-4 text-yellow-500">
                <span class="text-sm font-medium">Active</span>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <button
                    type="submit"
                    class="bg-yellow-500 text-black font-semibold
                           px-6 py-2 rounded-md
                           hover:bg-yellow-400 transition">
                    Update Course
                </button>

                <a href="{{ route('admin.courses.index') }}"
                   class="text-sm text-gray-600 hover:text-black">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Register Fonts
    
    var Font = Quill.import('formats/font');
Font.whitelist = [
    'arial',
    'times-new-roman',
    'courier-new',
    'georgia',
    'verdana',
    'tahoma',
    'trebuchet-ms',
    'roboto',
    'poppins',
    'inter',
    'syne',
    'monospace'
];
Quill.register(Font, true);

    // Register Sizes
 var Size = Quill.import('attributors/style/size');
Size.whitelist = [
    '10px',
    '12px',
    '14px',
    '16px',
    '18px',
    '20px',
    '22px',
    '24px',
    '26px',
    '28px',
    '30px',
    '32px'
];
Quill.register(Size, true);

    const courseForm = document.getElementById('courseForm');
    const descriptionInput = document.getElementById('description');
    // const descriptionValidator = document.getElementById('descriptionValidator');
    const editorContainer = document.getElementById('editor');
    const categorySelect = document.querySelector('select[name="training_category_id"]');
const priceInput = document.querySelector('input[name="price"]');

function handleFreeCoursePrice() {
    const selectedText = categorySelect.options[categorySelect.selectedIndex]?.text?.trim().toLowerCase();

    if (selectedText === 'free courses') {
        priceInput.value = 0;
        priceInput.readOnly = true;
        priceInput.classList.add('bg-gray-100', 'cursor-not-allowed');
    } else {
        priceInput.readOnly = false;
        priceInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
    }
}

handleFreeCoursePrice();
categorySelect.addEventListener('change', handleFreeCoursePrice);

    // Initialize Quill
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write course description here...',
        modules: {
          toolbar: [
    [{ font: Font.whitelist }],
    [{ size: Size.whitelist }],
    ['bold', 'italic', 'underline'],
    [{ header: [1, 2, 3, false] }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link'],
    ['clean']
]
        }
    });

    // Load existing description
    quill.root.innerHTML = {!! json_encode($course->description ?? '') !!};

   quill.on('text-change', function () {
    if (quill.getText().trim() !== '') {
        descriptionError.classList.add('hidden');
        document.querySelector('#editor').classList.remove('editor-error');
    }
});

// Add error message below editor
// Add browser-style error box below editor
const descriptionError = document.createElement('div');
descriptionError.id = 'descriptionError';
descriptionError.className = 'fake-browser-error hidden';
descriptionError.innerHTML = `
    <span class="fake-browser-error-arrow"></span>
    <span class="fake-browser-error-icon">!</span>
    <span>Please select an item in the list.</span>
`;
document.getElementById('editor').parentNode.appendChild(descriptionError);

courseForm.addEventListener('submit', function(e){
    descriptionInput.value = quill.root.innerHTML;

  if (quill.getText().trim() === '') {
    e.preventDefault();
    descriptionError.classList.remove('hidden');
    document.querySelector('#editor').classList.add('editor-error');

    document.querySelector('#editor').scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });

    return false;
} else {
    descriptionError.classList.add('hidden');
    document.querySelector('#editor').classList.remove('editor-error');
}
});

    // Skills logic
    const skillsDataEl = document.getElementById('skillsData');
    let skills = [];

    try {
        skills = JSON.parse(skillsDataEl.dataset.skills || '[]');
    } catch (e) {
        skills = [];
    }

    window.addSkill = function () {
        const input = document.getElementById('skillInput');
        const skill = input.value.trim();
        if (!skill) return;
        if (skills.some(s => s.toLowerCase() === skill.toLowerCase())) return;
        skills.push(skill);
        input.value = '';
        renderSkills();
    }

    window.removeSkill = function (skill) {
        skills = skills.filter(s => s !== skill);
        renderSkills();
    }

    function renderSkills() {
        const container = document.getElementById('skillsContainer');
        const hiddenField = document.getElementById('skillsField');
        container.innerHTML = '';

        skills.forEach(skill => {
            const escapedSkill = skill.replace(/'/g, "\\'");
            const tag = document.createElement('div');
            tag.className =
                'flex items-center gap-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm';

            tag.innerHTML = `
                <span>${skill}</span>
                <button type="button"
                        onclick="removeSkill('${escapedSkill}')"
                        class="text-red-600 font-bold">×</button>
            `;
            container.appendChild(tag);
        });

        hiddenField.value = skills.join(',');
    }

    renderSkills();
});
</script>
<style>
    /* =========================
       QUILL SIZE LABELS
    ========================= */
    .ql-snow .ql-picker.ql-size .ql-picker-item::before {
        content: attr(data-value) !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label::before {
        content: attr(data-value) !important;
    }

    /* =========================
       FONT DROPDOWN LABELS
    ========================= */
    .ql-snow .ql-picker.ql-font .ql-picker-label::before {
        content: 'Arial' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="arial"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="arial"]::before {
        content: 'Arial' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="times-new-roman"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="times-new-roman"]::before {
        content: 'Times New Roman' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="courier-new"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="courier-new"]::before {
        content: 'Courier New' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="georgia"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="georgia"]::before {
        content: 'Georgia' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="verdana"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="verdana"]::before {
        content: 'Verdana' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="tahoma"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="tahoma"]::before {
        content: 'Tahoma' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="trebuchet-ms"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="trebuchet-ms"]::before {
        content: 'Trebuchet MS' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="roboto"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="roboto"]::before {
        content: 'Roboto' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="poppins"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="poppins"]::before {
        content: 'Poppins' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="inter"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="inter"]::before {
        content: 'Inter' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="syne"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="syne"]::before {
        content: 'Syne' !important;
    }

    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="monospace"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="monospace"]::before {
        content: 'Monospace' !important;
    }

    /* =========================
       SIZE DROPDOWN LABELS
    ========================= */
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before {
        content: '10px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before {
        content: '12px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before {
        content: '14px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before {
        content: '16px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before {
        content: '18px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="20px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="20px"]::before {
        content: '20px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="22px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="22px"]::before {
        content: '22px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before {
        content: '24px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="26px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="26px"]::before {
        content: '26px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="28px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="28px"]::before {
        content: '28px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="30px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="30px"]::before {
        content: '30px' !important;
    }

    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="32px"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="32px"]::before {
        content: '32px' !important;
    }

    /* =========================
       APPLY ACTUAL FONT FAMILIES
    ========================= */
    .ql-font-arial { font-family: Arial, sans-serif; }
    .ql-font-times-new-roman { font-family: "Times New Roman", serif; }
    .ql-font-courier-new { font-family: "Courier New", monospace; }
    .ql-font-georgia { font-family: Georgia, serif; }
    .ql-font-verdana { font-family: Verdana, sans-serif; }
    .ql-font-tahoma { font-family: Tahoma, sans-serif; }
    .ql-font-trebuchet-ms { font-family: "Trebuchet MS", sans-serif; }
    .ql-font-roboto { font-family: Roboto, sans-serif; }
    .ql-font-poppins { font-family: Poppins, sans-serif; }
    .ql-font-inter { font-family: Inter, sans-serif; }
    .ql-font-syne { font-family: Syne, sans-serif; }
    .ql-font-monospace { font-family: monospace; }

    /* =========================
       DROPDOWN WIDTHS
    ========================= */
    .ql-snow .ql-picker.ql-font {
        width: 150px !important;
    }

    .ql-snow .ql-picker.ql-size {
        width: 90px !important;
    }

    /* =========================
       PICKER LABELS
    ========================= */
    .ql-snow .ql-picker.ql-font .ql-picker-label,
    .ql-snow .ql-picker.ql-size .ql-picker-label {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        min-width: 100% !important;
        padding-right: 18px !important;
    }

    /* =========================
       DROPDOWN MENU
    ========================= */
    .ql-snow .ql-picker-options {
        z-index: 9999 !important;
        background: #fff !important;
        border: 1px solid #d1d5db !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
        max-height: 220px !important;
        overflow-y: auto !important;
    }

    /* =========================
       EDITOR WRAPPER
    ========================= */
    #editor {
        position: relative;
        z-index: 2;
    }

    .editor-error,
    .editor-error .ql-toolbar,
    .editor-error .ql-container {
        border-color: #ef4444 !important;
    }

    /* =========================
       SINGLE LINE TOOLBAR FIX
    ========================= */
    .ql-toolbar.ql-snow {
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        white-space: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        gap: 0 !important;
        padding: 8px 10px !important;
    }

    .ql-toolbar.ql-snow .ql-formats {
        display: inline-flex !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        margin-right: 6px !important;
        margin-bottom: 0 !important;
        vertical-align: middle !important;
        flex-shrink: 0 !important;
    }

    .ql-toolbar button,
    .ql-toolbar .ql-picker,
    .ql-toolbar .ql-picker-label {
        flex-shrink: 0 !important;
    }

    .ql-toolbar button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: middle !important;
    }

    .ql-toolbar .ql-picker {
        display: inline-flex !important;
        align-items: center !important;
        vertical-align: middle !important;
    }

    .ql-toolbar .ql-formats:last-child {
        margin-right: 0 !important;
    }

    /* =========================
       CLEAN BUTTON / TX FIX
    ========================= */
    .ql-toolbar .ql-clean {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-left: 0 !important;
        padding: 0 6px !important;
    }

    /* =========================
       SCROLLBAR
    ========================= */
    .ql-toolbar.ql-snow::-webkit-scrollbar {
        height: 6px;
    }

    .ql-toolbar.ql-snow::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 999px;
    }
    /* =========================
   FIX QUILL DROPDOWN OVER TEXTAREA
========================= */

/* Let toolbar and editor wrapper allow dropdown to escape */
.ql-toolbar.ql-snow,
#editor {
    overflow: visible !important;
}
/* FIX DESCRIPTION BOX SCROLL */
#editor {
    height: 280px !important;
    overflow: hidden !important;
}

#editor .ql-toolbar.ql-snow {
    overflow-x: auto !important;
    overflow-y: hidden !important;
}

#editor .ql-container.ql-snow {
    height: calc(100% - 42px) !important;
    overflow: hidden !important;
}

#editor .ql-editor {
    height: 100% !important;
    max-height: 100% !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    word-break: break-word !important;
}
#editor .ql-editor::-webkit-scrollbar {
    width: 8px;
}

#editor .ql-editor::-webkit-scrollbar-track {
    background: #f1f1f1;   /* light grey track */
}

#editor .ql-editor::-webkit-scrollbar-thumb {
    background: #a8a8a8;   /* main grey thumb */
    border-radius: 999px;
}

#editor .ql-editor::-webkit-scrollbar-thumb:hover {
    background: #8c8c8c;   /* darker on hover */
}
#editor .ql-editor {
    scrollbar-width: thin;              /* Firefox */
    scrollbar-color: #a8a8a8 #f1f1f1;   /* thumb + track */
}

/* Main Quill wrapper must allow absolute dropdowns */
.ql-snow {
    overflow: visible !important;
}

/* Picker itself should be relative so options position correctly */
.ql-snow .ql-picker {
    position: relative !important;
}

/* Dropdown menu should appear above everything */
.ql-snow .ql-picker-options {
    position: absolute !important;
    z-index: 99999 !important;
    background: #fff !important;
    border: 1px solid #d1d5db !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
    max-height: 220px !important;
    overflow-y: auto !important;
}

/* Extra safety for editor area */
.ql-container.ql-snow {
    position: relative !important;
    z-index: 1 !important;
}

.ql-toolbar.ql-snow {
    position: relative !important;
    z-index: 20 !important;
}

/* If parent card or wrapper is clipping content */
.bg-white,
.rounded-md,
.rounded-lg,
.shadow-lg,
.max-w-3xl {
    overflow: visible !important;
}

.fake-browser-error.hidden {
    display: none !important;
}
.fake-browser-error {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    padding: 8px 12px;
    background: #efefef;
    border: 1px solid #a3a3a3;
    border-radius: 6px;
    color: #111;
    font-size: 14px;
    line-height: 1.2;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    width: fit-content;
}

.fake-browser-error-icon {
    width: 22px;
    height: 22px;
    border-radius: 4px;
    background: #f59e0b;
    color: #fff;
    font-weight: bold;
    font-size: 14px;
    line-height: 22px;
    text-align: center;
}

.fake-browser-error-arrow {
    position: absolute;
    top: -6px;
    left: 20px;
    width: 10px;
    height: 10px;
    background: #efefef;
    border-top: 1px solid #a3a3a3;
    border-left: 1px solid #a3a3a3;
    transform: rotate(45deg);
}
</style>
@endsection