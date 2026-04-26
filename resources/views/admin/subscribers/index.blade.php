@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-black">
        Subscribers
    </h2>

    <button onclick="openMessageModal()"
        class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-md font-semibold">
        Message
    </button>
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
<th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
<th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
<th class="px-4 py-3 text-center text-sm font-semibold">Select</th>
<th class="px-4 py-3 text-center text-sm font-semibold">
Select All
<input type="checkbox"
id="selectAll"
class="ml-2 w-4 h-4 accent-yellow-500">
</th>
</tr>
</thead>

<tbody class="divide-y divide-gray-200">
@forelse($subscribers as $subscriber)

<tr class="hover:bg-gray-50 transition">

<td class="px-4 py-3 font-medium">
{{ $subscriber->email }}
</td>

<td class="px-4 py-3 text-center text-sm">
<span class="text-green-600 font-semibold">Active</span>
</td>

<td class="px-4 py-3 text-center">
<input type="checkbox"
class="subscriber-checkbox w-4 h-4 accent-yellow-500"
value="{{ $subscriber->email }}">
</td>

<td class="px-4 py-3 text-center text-gray-400">
—
</td>

</tr>

@empty

<tr>
<td colspan="4"
class="px-4 py-6 text-center text-gray-500">
No subscribers found.
</td>
</tr>

@endforelse
</tbody>
</table>

</div>


{{-- MESSAGE MODAL --}}
<div id="messageModal"
class="fixed inset-0 bg-black/70 hidden z-50 flex items-start justify-center overflow-y-auto">

<div class="bg-black text-white w-full max-w-2xl mt-16 rounded-xl shadow-2xl border border-gray-800">

<div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">

<div class="flex items-center gap-3">

<div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center text-black">
✉️
</div>

<div>
<h3 class="text-lg font-semibold">Send Message</h3>
<p class="text-xs text-gray-400">Send to selected subscribers</p>
</div>

</div>

<button onclick="closeMessageModal()"
class="text-gray-400 hover:text-white text-xl">
×
</button>

</div>

<div class="px-6 py-5 space-y-4">

<form id="sendMessageForm">
@csrf

<label class="text-xs text-gray-400 uppercase">
Message
</label>

<div id="quillEditor"
class="bg-white text-black rounded-md min-h-[150px]"></div>

<button type="submit"
class="mt-4 bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-md font-semibold">
Send Message
</button>

</form>

</div>
</div>
</div>


<div id="responseModal"
     class="fixed inset-0 bg-black/70 hidden z-50 flex items-center justify-center">

    <div class="bg-black text-white w-[400px] rounded-xl shadow-2xl p-6 text-center">
        <div id="responseIcon"
             class="w-14 h-14 mx-auto mb-3 flex items-center justify-center rounded-full bg-green-100 text-green-600 text-2xl">
            ✔
        </div>

        <h3 id="responseTitle" class="text-lg font-semibold mb-2">
            Message Sent
        </h3>

        <p id="responseMessage" class="text-gray-200 text-sm mb-4">
            Your message was sent successfully.
        </p>

        <button onclick="closeResponseModal()"
                class="bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-2 rounded-md font-semibold">
            OK
        </button>
    </div>
</div>


@endsection


@push('scripts')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>


<script>

let quill;


function openMessageModal(){

document.getElementById('messageModal')
.classList.remove('hidden');

if(!quill){

quill=new Quill('#quillEditor',{

theme:'snow',

placeholder:'Write your message here...'

});

}

}


function closeMessageModal(){

document.getElementById('messageModal')
.classList.add('hidden');

}



function showResponseModal(title,message,type='success'){

document.getElementById('responseTitle').innerText=title;

document.getElementById('responseMessage').innerText=message;

const icon=document.getElementById('responseIcon');

if(type==='error'){

icon.className=
'w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-full bg-red-500 text-white text-2xl';

icon.innerHTML='⚠';

}else{

icon.className=
'w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-full bg-yellow-500 text-black text-2xl';

icon.innerHTML='✔';

}

document.getElementById('responseModal')
.classList.remove('hidden');

}



function closeResponseModal(){

document.getElementById('responseModal')
.classList.add('hidden');

}



document.getElementById('sendMessageForm')
.addEventListener('submit',function(e){

e.preventDefault();

const emails=

Array.from(
document.querySelectorAll('.subscriber-checkbox:checked')
).map(cb=>cb.value);


if(emails.length===0){

showResponseModal(
"Selection Required",
"Please select at least one subscriber",
"error"
);

return;

}


fetch("{{ route('admin.subscribers.send') }}",{

method:"POST",

headers:{
'X-CSRF-TOKEN':
document.querySelector('input[name=_token]').value,
'Content-Type':'application/json'
},

body:JSON.stringify({

emails:emails,
message:quill.root.innerHTML

})

})

.then(res=>res.json())

.then(data=>{

showResponseModal(
"Message Sent",
data.message,
"success"
);

closeMessageModal();

quill.setText('');

document.querySelectorAll('.subscriber-checkbox')
.forEach(cb=>cb.checked=false);

document.getElementById('selectAll').checked=false;

})

.catch(()=>{

showResponseModal(
"Error",
"Failed to send message. Please try again.",
"error"
);

});

});


document.getElementById('selectAll')
.addEventListener('change',function(){

document.querySelectorAll('.subscriber-checkbox')
.forEach(cb=>{

cb.checked=this.checked;

});

});

</script>

@endpush