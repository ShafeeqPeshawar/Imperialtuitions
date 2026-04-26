@extends('layouts.admin')

@section('content')
<div id="toast-container" class="admin-toast-container"></div>

<style>
@keyframes progress { from { width:100%; } to { width:0%; } }
.animate-progress { animation: progress 3s linear forwards; }
.admin-toast-container { position: fixed; top: 24px; right: 24px; z-index: 50; display: flex; flex-direction: column; gap: 12px; }
.admin-page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; }
.admin-page-title { font-size: 24px; font-weight: 700; color: #0f172a; margin: 0; }
.admin-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; font-size: 16px; font-weight: 600;
    background: #09515D; color: #fff;
    border: none; border-radius: 10px;
    text-decoration: none; cursor: pointer;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(9, 81, 93, 0.3);
}
.admin-btn-primary:hover { background: #0a6573; color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(9, 81, 93, 0.4); }
.admin-btn-accent {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; font-size: 15px; font-weight: 600;
    background: #F47B1E; color: #0f172a;
    border: none; border-radius: 10px;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.admin-btn-accent:hover { background: #f59e0b; transform: translateY(-1px); }
.admin-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 0 1px rgba(15, 23, 42, 0.06);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table thead { background: linear-gradient(180deg, #09515D 0%, #0a6573 100%); color: #fff; }
.admin-table th {
    padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700;
    letter-spacing: 0.02em; text-transform: uppercase;
}
.admin-table th.text-center { text-align: center; }
.admin-table tbody tr { border-bottom: 1px solid #e2e8f0; transition: background 0.15s; }
.admin-table tbody tr:hover { background: #f8fafc; }
.admin-table tbody tr:last-child { border-bottom: none; }
.admin-table td { padding: 16px 20px; font-size: 15px; color: #334155; vertical-align: middle; }
.admin-table td.text-center { text-align: center; }
.admin-table .admin-cell-title { font-weight: 600; color: #0f172a; }
.admin-table .admin-cell-muted { font-size: 13px; color: #64748b; margin-top: 4px; }
.admin-course-img { width: 56px; height: 56px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; }
.admin-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; }
.admin-badge-active { background: #d1fae5; color: #065f46; }
.admin-badge-inactive { background: #fee2e2; color: #991b1b; }
.admin-actions {
    display: inline-flex;
    align-items: stretch;
    justify-content: center;
    flex-wrap: wrap;
    gap: 4px;
    padding: 4px;
    background: #f1f5f9;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
}
.admin-btn-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    min-height: 36px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s, transform 0.15s;
}
.admin-btn-sm svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    opacity: 0.92;
}
.admin-actions .admin-btn-sm {
    box-sizing: border-box;
    width: 7.25rem;
    min-width: 7.25rem;
    max-width: 7.25rem;
    padding-left: 8px;
    padding-right: 8px;
}
.admin-btn-edit {
    background: #fff;
    color: #1e40af;
    border-color: #bfdbfe;
    box-shadow: 0 1px 2px rgba(30, 64, 175, 0.06);
}
.admin-btn-edit:hover {
    background: #eff6ff;
    border-color: #93c5fd;
    box-shadow: 0 2px 8px rgba(30, 64, 175, 0.12);
    transform: translateY(-1px);
    color: #1d4ed8;
}
.admin-btn-topics {
    background: #fff;
    color: #6d28d9;
    border-color: #ddd6fe;
    box-shadow: 0 1px 2px rgba(109, 40, 217, 0.06);
}
.admin-btn-topics:hover {
    background: #f5f3ff;
    border-color: #c4b5fd;
    box-shadow: 0 2px 8px rgba(109, 40, 217, 0.12);
    transform: translateY(-1px);
    color: #5b21b6;
}
.admin-btn-delete {
    background: #fff;
    color: #b91c1c;
    border-color: #fecaca;
    box-shadow: 0 1px 2px rgba(185, 28, 28, 0.06);
}
.admin-btn-delete:hover {
    background: #fef2f2;
    border-color: #fca5a5;
    box-shadow: 0 2px 8px rgba(185, 28, 28, 0.12);
    transform: translateY(-1px);
    color: #991b1b;
}
.admin-form-actions { margin-bottom: 20px; }
.admin-empty { padding: 48px 24px; text-align: center; color: #64748b; font-size: 16px; }
</style>

<script>
function showToast(message, type) {
    var container = document.getElementById('toast-container');
    if (!container) return;
    var border = type === 'success' ? '#28a745' : '#dc3545';
    var bg = type === 'success' ? 'rgba(40,167,69,0.95)' : 'rgba(220,53,69,0.95)';
    var toast = document.createElement('div');
    toast.setAttribute('role', 'toast');
    toast.style.cssText = 'background:#0f172a;color:#fff;border:2px solid ' + border + ';border-radius:12px;box-shadow:0 12px 32px rgba(0,0,0,0.2);max-width:380px;overflow:hidden;';
    toast.innerHTML = '<div style="display:flex;align-items:flex-start;padding:16px;gap:12px;">' +
        '<div style="width:40px;height:40px;border-radius:50%;background:' + bg + ';display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;">' + (type === 'success' ? '✓' : '!') + '</div>' +
        '<div style="flex:1;"><strong style="color:' + (type === 'success' ? '#86efac' : '#fca5a5') + ';">' + (type === 'success' ? 'Success' : 'Error') + '</strong><p style="margin:4px 0 0;font-size:14px;color:#e2e8f0;">' + message + '</p></div>' +
        '<button onclick="this.closest(\'[role=toast]\').remove()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:20px;line-height:1;">×</button></div>' +
        '<div class="animate-progress" style="height:4px;background:' + border + ';"></div>';
    container.appendChild(toast);
    setTimeout(function() { toast.querySelector('.animate-progress').style.animation = 'progress 3s linear forwards'; }, 50);
    setTimeout(function() { toast.style.opacity = '0'; toast.style.transform = 'translateX(24px)'; toast.style.transition = '0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 3000);
}
</script>
@if(session('success'))
<script> document.addEventListener("DOMContentLoaded", function () { showToast({{ json_encode(session('success')) }}, 'success'); }); </script>
@endif

<div class="admin-page-header">
    <h2 class="admin-page-title">Courses</h2>
    <a href="{{ route('admin.courses.create') }}" class="admin-btn-primary">+ Add Course</a>
</div>

<form id="popularForm" method="POST" action="{{ route('admin.courses.makePopular') }}">
    @csrf
    <div class="admin-form-actions">
        <button type="submit" class="admin-btn-accent">⭐ Make Popular</button>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Course</th>
                    <th>Level</th>
                    <th>Category</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th class="text-center">Sort</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Select</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td>
                        <img src="{{ asset('images/' . $course->image) }}" alt="" class="admin-course-img" onerror="this.style.display='none'">
                    </td>
                    <td>
                        <div class="admin-cell-title">{{ $course->title }}</div>
                        @if($course->skills)
                            <div class="admin-cell-muted">Skills: {{ \Illuminate\Support\Str::limit($course->skills, 40) }}</div>
                        @endif
                    </td>
                    <td>{{ $course->level ?? '–' }}</td>
                    <td>{{ $course->category?->name ?? 'Uncategorized' }}</td>
                    <td>
                        @if($course->duration)
                            {{ $course->duration }}
                        @elseif(isset($course->duration_value, $course->duration_unit))
                            {{ $course->duration_value }} {{ ucfirst($course->duration_unit) }}
                        @else
                            –
                        @endif
                    </td>
                    <td>{{ $course->price ? '$' . number_format($course->price, 2) : '–' }}</td>
                    <td class="text-center">{{ $course->sort_order }}</td>
                    <td class="text-center">
                        @if($course->is_active)
                            <span class="admin-badge admin-badge-active">Active</span>
                        @else
                            <span class="admin-badge admin-badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="selected_courses[]" value="{{ $course->id }}" class="course-checkbox">
                    </td>
                    <td class="text-center">
                        <div class="admin-actions" role="group" aria-label="Course actions">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="admin-btn-sm admin-btn-edit" title="Edit course">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </a>
                            <a href="{{ route('admin.courses.topics', $course) }}" class="admin-btn-sm admin-btn-topics" title="Manage topics">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                Topics
                            </a>
                        <button type="button"
onclick="openDeleteModal({{ $course->id }})"
class="admin-btn-sm admin-btn-delete"
title="Delete course">       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="admin-empty">No courses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>

@foreach($courses as $course)
<form id="del-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
<!-- DELETE CONFIRM MODAL -->
<div id="deleteModal" style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
z-index:9999;
align-items:center;
justify-content:center;
">

<div style="
background:#fff;
padding:30px;
border-radius:12px;
width:350px;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.2);
">

<h3 style="margin-bottom:10px;">Delete Course?</h3>

<p style="color:#555;margin-bottom:20px;">
Are you sure you want to delete this course permanently?
</p>

<button onclick="confirmDelete()"
style="
background:#dc3545;
color:white;
border:none;
padding:10px 20px;
border-radius:6px;
margin-right:10px;
cursor:pointer;
">
Delete
</button>

<button onclick="closeDeleteModal()"
style="
background:#6c757d;
color:white;
border:none;
padding:10px 20px;
border-radius:6px;
cursor:pointer;
">
Cancel
</button>

</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    var form = document.getElementById('popularForm');

    if (!form) return;

    form.addEventListener('submit', function(e) {

        var checkboxes = document.querySelectorAll('.course-checkbox:checked');

        if (checkboxes.length === 0) {
            e.preventDefault();
            showToast('Please select at least one course first.', 'error');
        }

    });

});


function deleteCourse(id) {

    let confirmed = window.confirm("Delete this course?");

    if (confirmed) {
        document.getElementById('del-' + id).submit();
    }

}
</script>
<script>

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
@endsection
