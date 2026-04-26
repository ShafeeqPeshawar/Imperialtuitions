@extends('layouts.admin')

@section('content')

<div class="max-w-3xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-black">
            Edit Topic
            <span class="block text-sm font-normal text-gray-800">
                {{ $course->title }}
            </span>
        </h2>

        <a href="{{ route('admin.courses.topics', $course) }}"
           class="text-sm text-gray-800 hover:text-black">
            ← Back to Topics
        </a>
    </div>

    <div class="bg-white text-black rounded-lg shadow-lg p-6">

        <form id="topicEditForm"
              method="POST"
              action="{{ route('admin.topics.update', $topic) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium mb-1">Topic Title</label>
                <input
                    type="text"
                    name="title"
                    value="{{ $topic->title }}"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>

                <div id="topicEditor"
                     class="bg-white border border-gray-300 rounded-md"
                     style="height: 220px;"></div>

                <input type="hidden" name="description" id="topicDescription">
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium mb-1">Sort Order</label>
                <input
                    type="number"
                    name="sort_order"
                    value="{{ $topic->sort_order }}"
                    required
                    class="w-full rounded-md border border-gray-300 px-4 py-2
                           focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Status -->
            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       {{ $topic->is_active ? 'checked' : '' }}
                       class="h-4 w-4 text-yellow-500 focus:ring-yellow-500">
                <span class="text-sm font-medium">Active</span>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <button class="bg-yellow-500 text-black font-semibold
                               px-6 py-2 rounded-md hover:bg-yellow-400">
                    Update Topic
                </button>

                <a href="{{ route('admin.courses.topics', $course) }}"
                   class="text-sm text-gray-600 hover:text-black">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<!-- Quill -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
      var Size = Quill.import('attributors/style/size');
Size.whitelist = ['10px','12px','14px','16px','18px','24px','32px'];
Quill.register(Size, true);
    const quill = new Quill('#topicEditor', {
        theme: 'snow',
        placeholder: 'Edit topic description...',
     modules: {
    toolbar: [
        [{ font: [] }],          // <-- font dropdown added
          [{ size: ['10px','12px','14px','16px','18px','24px','32px'] }],
        ['bold', 'italic', 'underline'],
        [{ header: [1, 2, 3, false] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link'],
        ['clean']
    ]
}
    });

    // ✅ Load existing description
    const existingHtml = `{!! addslashes($topic->description ?? '') !!}`;
    if (existingHtml.trim() !== '') {
        quill.clipboard.dangerouslyPasteHTML(existingHtml);
    }

    // ✅ Bind ONLY this form
    document.getElementById('topicEditForm').addEventListener('submit', function () {
        let html = quill.root.innerHTML;

        if (html === '<p><br></p>' || html.trim() === '') {
            html = '';
        }

        document.getElementById('topicDescription').value = html;
    });
</script>
<style>
.ql-snow .ql-picker.ql-size .ql-picker-item::before {
    content: attr(data-value) !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-label::before {
    content: attr(data-value) !important;
}
</style>
@endsection
