@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-black">
        Course Enrollments
    </h2>
</div>

<div class="bg-white text-black rounded-lg shadow-lg overflow-hidden">

    <table class="min-w-full border-collapse">
        <thead class="bg-black text-white">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">
                    #
                </th>
                <th class="px-4 py-3 text-left text-sm font-semibold">
                    For Course
                </th>
                <!-- <th class="px-4 py-3 text-left text-sm font-semibold">
    Launch Date
</th> -->

                <th class="px-4 py-3 text-left text-sm font-semibold">
    Type
</th>

                <th class="px-4 py-3 text-left text-sm font-semibold">
                    Name
                </th>
                <!-- <th class="px-4 py-3 text-left text-sm font-semibold">
                    Email
                </th> -->
                <!-- <th class="px-4 py-3 text-left text-sm font-semibold">
                    Phone
                </th> -->
                
                <!-- <th class="px-4 py-3 text-left text-sm font-semibold">
                    Message
                </th> -->
                <th class="px-4 py-3 text-center text-sm font-semibold">
                    Submitted
                </th>
                <th class="px-4 py-3 text-center">Status</th>
<th class="px-4 py-3 text-center">Action</th>

            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse($enrollments as $index => $enroll)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3 text-sm">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-4 py-3 font-medium">
                        {{ $enroll->course_name }}
                    </td>
                    <!-- <td class="px-4 py-3 text-sm">
    <i class="bi bi-calendar-event me-1 text-gray-500"></i>
    {{ $enroll->launch_date
        ? \Carbon\Carbon::parse($enroll->launch_date)->format('d M Y')
        : '—' }}
</td> -->

                    <td class="px-4 py-3 text-sm">
    <span class="px-3 py-1 rounded-full text-xs font-semibold
        {{ $enroll->registration_type === 'Corporate'
            ? 'bg-blue-100 text-blue-700'
            : 'bg-gray-100 text-gray-700' }}">
        {{ $enroll->registration_type ?? 'Individual' }}
    </span>
</td>


                    <td class="px-4 py-3">
                        {{ $enroll->name }}
                    </td>
<!-- 
                    <td class="px-4 py-3">
                        {{ $enroll->email }}
                    </td> -->

                    <!-- <td class="px-4 py-3">
                        {{ $enroll->phone }}
                    </td> -->

                    

                    <!-- <td class="px-4 py-3 text-sm max-w-xs truncate">
                        {{ $enroll->message ?? '—' }}
                    </td> -->

                    <td class="px-4 py-3 text-center text-sm text-gray-600">
                        {{ $enroll->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 text-center">
    @if($enroll->status === 'approved')
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
            Approved
        </span>
    @elseif($enroll->status === 'rejected')
        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
            Rejected
        </span>
    @else
        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
            Pending
        </span>
    @endif
</td>
<td class="px-4 py-3 text-center flex items-center justify-center gap-3">

    <!-- VIEW -->
    <button type="button"onclick="openEnrollmentModal({{ $enroll->id }})"
        class="text-blue-600 hover:underline text-sm">
        View Details
    </button>

    <!-- DELETE -->
   <!-- DELETE -->
<button type="button" onclick="openDeleteModal({{ $enroll->id }})"
    class="text-red-600 hover:underline text-sm">
    Delete
</button>

<form id="del-{{ $enroll->id }}" method="POST"
      action="{{ route('admin.course-enrollments.destroy', $enroll->id) }}"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

</td>



                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                        No enrollments found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t border-gray-200">
        {{ $enrollments->links() }}
    </div>

</div>
<!--  -->
<div id="enrollmentModal"
     class="fixed inset-0 bg-black/70 hidden z-50 flex items-start justify-center overflow-y-auto">

<div class="bg-black text-white w-full max-w-2xl mt-16 rounded-xl shadow-2xl border border-gray-800">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-500/90
            flex items-center justify-center text-black text-lg shadow">
    📋
</div>

                <div>
                    <h3 class="text-lg font-semibold">Enrollment Details</h3>
                    <p class="text-xs text-gray-400">Review & take action</p>
                </div>
            </div>

            <button onclick="closeEnrollmentModal()"
                class="text-gray-400 hover:text-white text-xl leading-none">
                ×
            </button>
        </div>

        <!-- BODY -->
        <div class="px-6 py-5 space-y-4">

            <!-- DETAILS GRID -->
            <div id="enrollmentDetails"
                 class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <!-- JS injects content here -->
            </div>
            

            <!-- DIVIDER -->
            <div class="border-t border-gray-700 my-4"></div>

            <!-- REPLY FORM -->
            <form method="POST" id="replyForm" class="space-y-3">
                @csrf

                <label class="text-xs text-gray-400 uppercase tracking-wide">
                    Reply Message
                </label>

                <textarea id="replyMessage" name="reply_message"
    class="w-full min-h-[110px] p-3 rounded-md
           bg-gray-900 text-white text-sm
           border border-gray-700
           focus:outline-none focus:ring-2 focus:ring-yellow-500"
    placeholder="Write your reply here..."></textarea>


                <!-- ACTION BUTTONS -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">

                    <button type="submit" id="replyBtn"
    class="border border-yellow-500 text-yellow-500
           hover:bg-yellow-500 hover:text-black
           px-5 py-2 rounded-md text-sm font-semibold transition">
    Send Reply
</button>


                    <div class="flex gap-2">
                        <button type="submit" id="approveBtn"
    class="bg-yellow-500 hover:bg-yellow-400
           text-black
           px-5 py-2 rounded-md text-sm font-semibold transition shadow">
    Approve
</button>


                        <button type="submit" id="rejectBtn"
                            class="bg-red-600 hover:bg-red-500
                                   px-5 py-2 rounded-md text-sm font-semibold">
                            Reject
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
<!-- DELETE CONFIRM MODAL -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:12px; width:350px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <h3>Delete Enrollment?</h3>
        <p style="color:#555; margin-bottom:20px;">Are you sure you want to delete this enrollment permanently?</p>
        <button onclick="confirmDelete()" style="background:#dc3545;color:white;border:none;padding:10px 20px;border-radius:6px;">Delete</button>
        <button onclick="closeDeleteModal()" style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:6px;">Cancel</button>
    </div>
</div>

@endsection
@push('scripts')
<script>
function openEnrollmentModal(id) {
    fetch(`/admin/course-enrollments/${id}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => {
        if (!res.ok) throw new Error('Request failed: ' + res.status);
        return res.json();
    })
    .then(data => {
        document.getElementById('enrollmentDetails').innerHTML = `
            <div>
                <span class="text-gray-400">Name</span>
                <p class="font-medium text-gray-100">${data.name}</p>
            </div>

            <div>
                <span class="text-gray-400">Email</span>
                <p class="font-medium">${data.email}</p>
            </div>

            <div>
                <span class="text-gray-400">Phone</span>
                <p class="font-medium">${data.phone ?? '—'}</p>
            </div>

            <div>
                <span class="text-gray-400">Course</span>
                <p class="font-medium">${data.course_name}</p>
            </div>

            <div>
                <span class="text-gray-400">Level</span>
                <p class="font-medium">${data.level ?? '—'}</p>
            </div>

            <!-- ✅ NEW -->
            <div>
                <span class="text-gray-400">Preferred Date</span>
                <p class="font-medium">
                    ${data.preferred_date ?? '—'}
                </p>
            </div>

            <!-- ✅ NEW -->
            <div>
                <span class="text-gray-400">Preferred Time</span>
                <p class="font-medium">
                    ${data.preferred_time ?? '—'}
                </p>
            </div>

            <div class="sm:col-span-2">
                <span class="text-gray-400">Message</span>
                <p class="font-medium">${data.message ?? '—'}</p>
            </div>

            <div>
                <span class="text-gray-400">Status</span>
                <p class="font-medium capitalize">${data.status}</p>
            </div>
        `;

        document.getElementById('replyForm').action =
            `/admin/course-enrollments/${id}/reply`;

        document.getElementById('approveBtn').formAction =
            `/admin/course-enrollments/${id}/approve`;

        document.getElementById('rejectBtn').formAction =
            `/admin/course-enrollments/${id}/reject`;

        document.getElementById('enrollmentModal').classList.remove('hidden');
    })
    .catch(err => {
        console.error(err);
        alert('Could not load enrollment details. Check console.');
    });
}

function closeEnrollmentModal() {
    document.getElementById('enrollmentModal').classList.add('hidden');
}

const replyBtn = document.getElementById('replyBtn');
const approveBtn = document.getElementById('approveBtn');
const rejectBtn = document.getElementById('rejectBtn');
const replyMessage = document.getElementById('replyMessage');

// When clicking "Send Reply" → message REQUIRED
replyBtn.addEventListener('click', () => {
    replyMessage.setAttribute('required', 'required');
});

// When clicking Approve → message NOT required
approveBtn.addEventListener('click', () => {
    replyMessage.removeAttribute('required');
});

// When clicking Reject → message NOT required
rejectBtn.addEventListener('click', () => {
    replyMessage.removeAttribute('required');
});
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
