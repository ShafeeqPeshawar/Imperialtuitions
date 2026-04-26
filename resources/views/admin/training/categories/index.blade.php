@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-black">
        Training Categories
    </h2>

    <a href="{{ route('training.categories.create') }}"
       class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-md font-semibold">
        Add Category
    </a>
</div>

@if(session('success'))
    <div style="background:#d1fae5;color:#065f46;padding:10px;margin-bottom:10px;border-radius:4px">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white text-black rounded-lg shadow-lg overflow-hidden">

    <table class="min-w-full border-collapse">
        <thead class="bg-black text-white">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Order</th>
                <th class="px-4 py-3 text-left text-sm font-semibold">Slug</th>
                <th class="px-4 py-3 text-right text-sm font-semibold">Action</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3 font-medium">
                        {{ $category->name }}
                    </td>

                    <td class="px-4 py-3 font-semibold text-gray-700">
                        {{ $category->sort_order }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $category->slug }}
                    </td>

                    <td class="px-4 py-3 text-right space-x-3">
                        <a href="{{ route('training.categories.edit', $category) }}"
                           class="text-blue-600 hover:underline text-sm">
                            Edit
                        </a>

                        <button type="button"
                                onclick="openDeleteModal({{ $category->id }})"
                                class="text-red-600 hover:underline text-sm">
                            Delete
                        </button>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        No categories found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<!-- Hidden Delete Forms -->
@foreach($categories as $category)
    <form id="del-{{ $category->id }}"
          action="{{ route('training.categories.destroy', $category) }}"
          method="POST"
          style="display:none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<!-- Delete Confirmation Modal -->
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
        <h3>Delete Category?</h3>
        <p style="color:#555;margin-bottom:20px;">
            Are you sure you want to delete this category permanently?
        </p>

        <button onclick="confirmDelete()"
                style="background:#dc3545;color:white;border:none;padding:10px 20px;border-radius:6px;">
            Delete
        </button>

        <button onclick="closeDeleteModal()"
                style="background:#6c757d;color:white;border:none;padding:10px 20px;border-radius:6px;">
            Cancel
        </button>
    </div>
</div>

<script>
let deleteId = null;

function openDeleteModal(id){
    deleteId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal(){
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete(){
    if(deleteId){
        document.getElementById('del-' + deleteId).submit();
    }
}
</script>

@endsection