@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-black">
        Contact Submissions
    </h2>
</div>

<div class="bg-white text-black rounded-lg shadow-lg overflow-hidden">

<table class="min-w-full border-collapse">
    <thead class="bg-black text-white">
        <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
            <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Date</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Replied</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Viewed</th>
            <th class="px-4 py-3 text-center text-sm font-semibold">Action</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @forelse($contacts as $contact)
        <tr class="hover:bg-gray-50 transition">

            <td class="px-4 py-3 font-medium">{{ $contact->name }}</td>
            <td class="px-4 py-3">{{ $contact->email }}</td>

            <td class="px-4 py-3 text-center text-sm">
                {{ $contact->created_at->format('d M Y') }}
            </td>

            {{-- Replied --}}
            <td class="px-4 py-3 text-center text-sm">
                @if($contact->reply_status === 'replied')
                    <span class="text-green-600 font-semibold">Replied</span>
                @else
                    <span class="text-red-500 font-semibold">Pending</span>
                @endif
            </td>

            {{-- Viewed --}}
            <td class="px-4 py-3 text-center text-sm">
                @if($contact->is_viewed)
                    <span class="text-green-600 font-semibold">Viewed</span>
                @else
                    <span class="text-gray-400 font-semibold">—</span>
                @endif
            </td>

           <td class="px-4 py-3 text-center">
    <button
        onclick="openContactModal({{ $contact->id }})"
        class="text-blue-600 hover:underline text-sm">
        View Details /
    </button>

   

  <!-- DELETE -->
<form method="POST"
      id="del-{{ $contact->id }}"
      action="{{ route('admin.contacts.destroy', $contact->id) }}"
      style="display:inline;">
    @csrf
    @method('DELETE')

    <button type="button"
            onclick="openDeleteModal({{ $contact->id }})"
            class="text-red-600 hover:underline text-sm font-semibold">
        Delete
    </button>
</form>
</td>

        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                No contact submissions found.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>

<div class="mt-4">
    {{ $contacts->links() }}
</div>
<!-- model -->
 <div id="contactModal"
class="fixed inset-0 bg-black/70 hidden z-50 flex items-start justify-center overflow-y-auto">

<div class="bg-black text-white w-full max-w-2xl mt-16 rounded-xl shadow-2xl border border-gray-800">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-black">
                ✉️
            </div>
            <div>
                <h3 class="text-lg font-semibold">Contact Details</h3>
                <p class="text-xs text-gray-400">View & reply</p>
            </div>
        </div>

        <button onclick="closeContactModal()" class="text-gray-400 hover:text-white text-xl">×</button>
    </div>

    <!-- BODY -->
    <div class="px-6 py-5 space-y-4">

        <div id="contactDetails"
             class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
        </div>

        <div class="border-t border-gray-700 my-4"></div>

        <!-- REPLY FORM -->
        <form method="POST" id="contactReplyForm">
            @csrf

            <label class="text-xs text-gray-400 uppercase">Reply Message</label>

            <textarea name="reply_message" required
                class="w-full min-h-[120px] p-3 rounded-md bg-gray-900 border border-gray-700 focus:ring-2 focus:ring-yellow-500"
                placeholder="Write your reply..."></textarea>

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
        <h3>Delete Contact?</h3>
        <p style="color:#555; margin-bottom:20px;">Are you sure you want to delete this contact submission permanently?</p>
        <button onclick="confirmDelete()" style="background:#dc3545;color:white;border:none;padding:10px 20px;border-radius:6px;">Delete</button>
        <button onclick="closeDeleteModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:6px;">Cancel</button>
    </div>
</div>
@endsection
@push('scripts')
<script>
function openContactModal(id) {
    fetch(`/admin/contacts/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('contactDetails').innerHTML = `
                <div><span class="text-gray-400">Name</span><p>${data.name}</p></div>
                <div><span class="text-gray-400">Email</span><p>${data.email}</p></div>
                <div class="sm:col-span-2">
                    <span class="text-gray-400">Message</span>
                    <p>${data.message}</p>
                </div>
            `;

            document.getElementById('contactReplyForm').action =
                `/admin/contacts/${id}/reply`;

            document.getElementById('contactModal').classList.remove('hidden');
        });
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
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
