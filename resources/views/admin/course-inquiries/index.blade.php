@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-black">
        Course Inquiries
    </h2>
</div>

<div class="bg-white text-black rounded-lg shadow-lg overflow-hidden">

<table class="min-w-full border-collapse">
    <thead class="bg-black text-white">
        <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold"> For Course</th>
            <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
            <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
            <!-- <th class="px-4 py-3 text-left text-sm font-semibold">Phone</th> -->
            <!-- <th class="px-4 py-3 text-left text-sm font-semibold">Message</th> -->
             <!-- <th class="px-4 py-3 text-left text-sm font-semibold">Level</th>
             <th class="px-4 py-3 text-center text-sm font-semibold">Launch Date</th> -->

            <th class="px-4 py-3 text-center text-sm font-semibold"> Submitted Date</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Replied</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Viewed</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Action</th>

        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @forelse($inquiries as $inquiry)
            <tr class="hover:bg-gray-50 transition">

                <td class="px-4 py-3 font-medium">
                    {{ $inquiry->course_title }}
                </td>

                <td class="px-4 py-3">
                    {{ $inquiry->name }}
                </td>

                <td class="px-4 py-3">
                    {{ $inquiry->email }}
                </td>
                <!-- <td class="px-4 py-3 text-sm">
    {{ $inquiry->level ?? '—' }}
</td>

<td class="px-4 py-3 text-center text-sm">
    {{ $inquiry->launch_date
        ? \Carbon\Carbon::parse($inquiry->launch_date)->format('d M Y')
        : '—' }}
</td>
 -->

                <!-- <td class="px-4 py-3">
                    {{ $inquiry->phone }}
                </td> -->
<!-- 
                <td class="px-4 py-3 text-sm text-gray-700">
                    {{ \Illuminate\Support\Str::limit($inquiry->message, 60) }}
                </td> -->

                <td class="px-4 py-3 text-center text-sm">
                    {{ $inquiry->created_at->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-center text-sm">
    @if($inquiry->reply_status === 'replied')
        <span class="text-green-600 font-semibold">Replied</span>
    @else
        <span class="text-red-500 font-semibold">Pending</span>
    @endif
</td>

<td class="px-4 py-3 text-center text-sm">
    @if($inquiry->is_viewed)
        <span class="text-green-600 font-semibold">Viewed</span>
    @else
        <span class="text-gray-400">—</span>
    @endif
</td>

<td class="px-4 py-3 text-center">
    <div class="flex items-center justify-center gap-4 flex-nowrap">

        <!-- VIEW -->
        <button
            onclick="openInquiryModal({{ $inquiry->id }})"
            class="text-blue-600 hover:underline text-sm shrink-0">
            View
        </button>

        <!-- DELETE -->
      <!-- DELETE -->
<form method="POST"
      id="del-{{ $inquiry->id }}"
      action="{{ route('admin.course-inquiries.destroy', $inquiry) }}"
      class="shrink-0">
    @csrf
    @method('DELETE')

    <button type="button"
        onclick="openDeleteModal({{ $inquiry->id }})"
        class="text-red-600 hover:underline text-sm font-semibold">
        Delete
    </button>
</form>

    </div>
</td>



            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                    No inquiries found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>

<div class="mt-4">
    {{ $inquiries->links() }}
</div>
<div id="inquiryModal"
class="fixed inset-0 bg-black/70 hidden z-50 flex items-start justify-center overflow-y-auto">

<div class="bg-black text-white w-full max-w-2xl mt-16 rounded-xl shadow-2xl border border-gray-800">

    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
        <h3 class="text-lg font-semibold">Inquiry Details</h3>
        <button onclick="closeInquiryModal()" class="text-xl">×</button>
    </div>

    <div class="px-6 py-5 space-y-4">

        <div id="inquiryDetails"
            class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
        </div>

        <div class="border-t border-gray-700"></div>

        <form method="POST" id="inquiryReplyForm">
            @csrf

            <label class="text-xs text-gray-400 uppercase">Reply Message</label>

            <textarea name="reply_message" required
                class="w-full min-h-[120px] p-3 rounded-md bg-gray-900 border border-gray-700"></textarea>

            <button type="submit"
                class="mt-4 bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-md font-semibold">
                Send Reply
            </button>
        </form>

    </div>
</div>
</div>
<!-- DELETE CONFIRM MODAL -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:12px; width:350px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <h3>Delete Inquiry?</h3>
        <p style="color:#555; margin-bottom:20px;">Are you sure you want to delete this inquiry permanently?</p>
        <button onclick="confirmDelete()" style="background:#dc3545;color:white;border:none;padding:10px 20px;border-radius:6px;">Delete</button>
        <button onclick="closeDeleteModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:6px;">Cancel</button>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openInquiryModal(id) {
    fetch(`/admin/course-inquiries/${id}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('inquiryDetails').innerHTML = `
    <div>
        <span class="text-gray-400">Course</span>
        <p class="font-medium">${data.course_title}</p>
    </div>

    <div>
        <span class="text-gray-400">Level</span>
        <p class="font-medium">${data.level ?? '—'}</p>
    </div>

   

    <div>
        <span class="text-gray-400">Name</span>
        <p class="font-medium">${data.name}</p>
    </div>

    <div>
        <span class="text-gray-400">Email</span>
        <p class="font-medium">${data.email}</p>
    </div>

    <div>
        <span class="text-gray-400">Phone</span>
        <p class="font-medium">${data.phone ?? '—'}</p>
    </div>

    <div class="sm:col-span-2">
        <span class="text-gray-400">Message</span>
        <p class="font-medium">${data.message}</p>
    </div>
`;


        document.getElementById('inquiryReplyForm').action =
            `/admin/course-inquiries/${id}/reply`;

        document.getElementById('inquiryModal').classList.remove('hidden');
    });
}

function closeInquiryModal() {
    document.getElementById('inquiryModal').classList.add('hidden');
}
let deleteId = null;

function openDeleteModal(id) {
    deleteId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete() {
    if(deleteId){
        document.getElementById('del-' + deleteId).submit();
    }
}
</script>
@endpush
