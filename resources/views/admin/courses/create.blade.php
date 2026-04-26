@extends('layouts.admin')

@section('content')

<div class="max-w-3xl">

<h2 class="text-2xl font-semibold text-black mb-6">
Add New Course
</h2>

<div class="bg-white text-black rounded-lg shadow-lg p-6" style="overflow: visible;">




<form id="courseForm"
method="POST"
enctype="multipart/form-data"
action="{{ route('admin.courses.store') }}"
class="space-y-5"
novalidate
> 
@csrf


{{-- Title --}}
<div>

<label class="block text-sm font-medium mb-1">
Course Title
</label>

<input
type="text"
name="title"
required
placeholder="Enter course title"
class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">

</div>



{{-- Category --}}
<div>

<label class="block text-sm font-medium mb-1">
Course Category
</label>

<select
name="training_category_id"
required
class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">

<option value="">Select Category</option>

@foreach($categories as $category)

<option value="{{ $category->id }}">
{{ $category->name }}
</option>

@endforeach

</select>

</div>



{{-- Description --}}
{{-- Description --}}
{{-- Description --}}
<div id="descriptionFieldWrapper">

<label class="block text-sm font-medium mb-1">
Course Description
</label>

<div id="editorWrapper" class="rounded-md border border-gray-300 bg-white">
<div id="editor" style="height:280px;"></div>
</div>

<input type="hidden"
name="description"
id="description"
required
tabindex="-1"
value="{{ old('description') }}">
<input
    type="text"
    id="descriptionValidator"
    required
    tabindex="-1"
    aria-hidden="true"
    style="position:absolute; top:0; left:0; width:1px; height:1px; opacity:0;">

</div>


{{-- Level --}}
<div>

<label class="block text-sm font-medium mb-1">
Course Level
</label>

<select
name="level"
required
class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">

<option value="">Select Level</option>

<option value="Beginner">Beginner</option>

<option value="Intermediate">Intermediate</option>

<option value="Advanced">Advanced</option>

</select>

</div>



{{-- Duration --}}
<div>

<label class="block text-sm font-medium mb-1">
Duration
</label>

<div class="flex gap-3">

<input
type="number"
name="duration_value"
required
min="1"
placeholder="e.g. 6"
class="w-1/2 rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">


<select
name="duration_unit"
required
class="w-1/2 rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">

<option value="">Select Unit</option>

<option value="hours">Hours</option>
<option value="days">Days</option>
<option value="weeks">Weeks</option>
<option value="months">Months</option>

</select>

</div>

</div>



{{-- Price --}}
<div>

<label class="block text-sm font-medium mb-1">
Price ($)
</label>

<input
type="number"
step="0.01"
name="price"
required
placeholder="e.g. 199.99"
class="w-full rounded-md border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-yellow-500">

</div>



{{-- Skills --}}
<div>

<label class="block text-sm font-medium mb-2">
Skills / Prerequisites
</label>

<div class="flex gap-2">

<input
type="text"
id="skillInput"
placeholder="Type skill then click Add"
class="flex-1 rounded-md border border-gray-300 px-4 py-2">

<button
type="button"
onclick="addSkill()"
class="bg-black text-white px-4 py-2 rounded-md hover:bg-yellow-500 hover:text-black">

Add

</button>

</div>


<div id="skillsContainer"
class="flex flex-wrap gap-2 mt-3"></div>


<input type="hidden"
name="skills"
id="skillsField">

</div>



{{-- Sort order --}}
<div>

<label class="block text-sm font-medium mb-1">
Sort Order
</label>

@php
$defaultSortOrder=(\App\Models\Course::max('sort_order') ?? 0)+10;
@endphp


<input
type="number"
name="sort_order"
value="{{ old('sort_order',$defaultSortOrder) }}"
class="w-full rounded-md border border-gray-300 px-4 py-2">

</div>



{{-- Image --}}
<div>

<label class="block text-sm font-medium mb-1">
Course Image
</label>

<input
type="file"
name="image"
required
class="w-full border border-gray-300 rounded-md file:bg-black file:text-white file:px-4 file:py-2 hover:file:bg-yellow-500 hover:file:text-black">

</div>



{{-- Status --}}
<div class="flex items-center gap-2">

<input
type="checkbox"
name="is_active"
checked
class="h-4 w-4 text-yellow-500">

<span class="text-sm font-medium">
Active
</span>

</div>



{{-- Submit --}}
<div class="flex gap-4 pt-4">

<button
type="submit"
class="bg-yellow-500 text-black font-semibold px-6 py-2 rounded-md hover:bg-yellow-400">

Save Course

</button>


<a href="{{ route('admin.courses.index') }}"
class="text-sm text-gray-600 hover:text-black">

Cancel

</a>

</div>

</form>

</div>

</div>



<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css"
rel="stylesheet">

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>



<script>

document.addEventListener('DOMContentLoaded',function(){


/* FONT DROPDOWN */

var Font=Quill.import('formats/font');

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

Quill.register(Font,true);



/* FONT SIZE DROPDOWN */

var Size=Quill.import('attributors/style/size');

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

Quill.register(Size,true);



/* INIT EDITOR */

const quill=new Quill('#editor',{

theme:'snow',

placeholder:'Write course description here...',

modules:{

toolbar:[

[{ font: Font.whitelist }],
[{ size: Size.whitelist }],

['bold','italic','underline'],

[{header:[1,2,3,false]}],

[{list:'ordered'},{list:'bullet'}],

['link'],

['clean']

]

}

});
quill.on('text-change', function () {
    if (!isQuillEmpty(quill)) {
        const error = document.querySelector('.fake-browser-error');
        if (error) error.remove();

        document.getElementById('editorWrapper')
            .classList.remove('description-error-border');
    }
});
/* DESCRIPTION VALIDATION */

const courseForm = document.getElementById('courseForm');
const descriptionInput = document.getElementById('description');
const descriptionWrapper = document.getElementById('descriptionFieldWrapper');
const descriptionValidator = document.getElementById('descriptionValidator');
const editorWrapper = document.getElementById('editorWrapper');

function isQuillEmpty(quillInstance) {
    const text = quillInstance.getText().trim();
    const html = quillInstance.root.innerHTML
        .replace(/<(.|\n)*?>/g, '')
        .trim();
    return text.length === 0 || html.length === 0;
}

// Function to show error message below the field (matching browser style)
function showErrorMessage(field, isQuill = false) {
    if (isQuill) {

        editorWrapper.classList.add('description-error-border');

        // remove old error
        const oldError = document.querySelector('.fake-browser-error');
        if (oldError) oldError.remove();

        // create fake browser style message
        const error = document.createElement('div');
        error.className = 'fake-browser-error';

        error.innerHTML = `
            <span class="fake-browser-error-icon">!</span>
            <span>Please select an item in the list.</span>
        `;

        descriptionWrapper.appendChild(error);

        descriptionWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });

        return;
    }

    if (field) {
        field.classList.add('border-red-600');
        field.setCustomValidity('Please select an item in the list.');
        field.reportValidity();
        field.setCustomValidity('');
    }
}
// Clear all error styles and messages
function clearAllErrors() {

    document.querySelectorAll('.border-red-600').forEach(el => {
        el.classList.remove('border-red-600');
    });

    editorWrapper.classList.remove('description-error-border');

    // remove fake error
    const oldError = document.querySelector('.fake-browser-error');
    if (oldError) oldError.remove();
}

courseForm.addEventListener('submit', function (e) {
    // Clear previous errors
    clearAllErrors();
    
    // Update description value
    descriptionInput.value = quill.root.innerHTML;
    
    // VALIDATE IN TOP-TO-BOTTOM ORDER
    // 1. Title
    const title = document.querySelector('input[name="title"]');
    if (!title.value.trim()) {
        e.preventDefault();
        title.classList.add('border-red-600');
        title.setCustomValidity('Please enter course title.');
        title.reportValidity();
        title.setCustomValidity('');
        title.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 2. Category
    const category = document.querySelector('select[name="training_category_id"]');
    if (!category.value) {
        e.preventDefault();
        category.classList.add('border-red-600');
        category.setCustomValidity('Please select an item in the list.');
        category.reportValidity();
        category.setCustomValidity('');
        category.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 3. Description (Quill editor)
    if (isQuillEmpty(quill)) {
        e.preventDefault();
        showErrorMessage(null, true);
        return false;
    }
    
    // 4. Level
    const level = document.querySelector('select[name="level"]');
    if (!level.value) {
        e.preventDefault();
        level.classList.add('border-red-600');
        level.setCustomValidity('Please select an item in the list.');
        level.reportValidity();
        level.setCustomValidity('');
        level.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 5. Duration Value
    const durationValue = document.querySelector('input[name="duration_value"]');
    if (!durationValue.value.trim()) {
        e.preventDefault();
        durationValue.classList.add('border-red-600');
        durationValue.setCustomValidity('Please enter duration value.');
        durationValue.reportValidity();
        durationValue.setCustomValidity('');
        durationValue.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 6. Duration Unit
    const durationUnit = document.querySelector('select[name="duration_unit"]');
    if (!durationUnit.value) {
        e.preventDefault();
        durationUnit.classList.add('border-red-600');
        durationUnit.setCustomValidity('Please select an item in the list.');
        durationUnit.reportValidity();
        durationUnit.setCustomValidity('');
        durationUnit.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 7. Price
    const price = document.querySelector('input[name="price"]');
    if (!price.value.trim()) {
        e.preventDefault();
        price.classList.add('border-red-600');
        price.setCustomValidity('Please enter course price.');
        price.reportValidity();
        price.setCustomValidity('');
        price.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // 8. Image
    const image = document.querySelector('input[name="image"]');
    if (!image.files.length) {
        e.preventDefault();
        image.classList.add('border-red-600');
        image.setCustomValidity('Please upload course image.');
        image.reportValidity();
        image.setCustomValidity('');
        image.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // If all validations pass
    descriptionInput.setCustomValidity('');
});
/* SKILLS */

let skills=[];

window.addSkill=function(){

const input=document.getElementById('skillInput');

const skill=input.value.trim();

if(!skill||skills.includes(skill))return;

skills.push(skill);

input.value='';

renderSkills();

};



window.removeSkill=function(skill){

skills=skills.filter(s=>s!==skill);

renderSkills();

};



function renderSkills(){

const container=document.getElementById('skillsContainer');

const hiddenField=document.getElementById('skillsField');

container.innerHTML='';

skills.forEach(skill=>{

const escapedSkill=skill.replace(/'/g,"\\'");

const tag=document.createElement('div');

tag.className='flex items-center gap-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm';

tag.innerHTML=`

<span>${skill}</span>

<button type="button"
onclick="removeSkill('${escapedSkill}')"
class="text-red-600 font-bold">×</button>

`;

container.appendChild(tag);

});

hiddenField.value=skills.join(',');

}



/* FREE COURSE PRICE AUTO */

const categorySelect=document.querySelector('select[name="training_category_id"]');

const priceInput=document.querySelector('input[name="price"]');

categorySelect.addEventListener('change',function(){

const selectedText=categorySelect.options[categorySelect.selectedIndex].text;

if(selectedText.toLowerCase()==='free courses'){

priceInput.value=0;

priceInput.readOnly=true;

}

else{

priceInput.readOnly=false;

}

});



});
// Custom error alert timer (3 seconds)
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.custom-error-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500); // 0.5s me remove
        }, 3000); // 3 seconds
    });
});
document.addEventListener('DOMContentLoaded', function () {

    const firstError = document.querySelector('.text-red-600');

    if (firstError) {
        setTimeout(() => {
            firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 200);
    }

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
   FONT LABELS
========================= */
/* =========================
   FONT LABELS
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
   SIZE LABELS
========================= */
.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before {
    content: '10px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before {
    content: '12px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before {
    content: '14px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before {
    content: '16px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before {
    content: '18px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before {
    content: '24px' !important;
}

.ql-snow .ql-picker.ql-size .ql-picker-item[data-value="32px"]::before,
.ql-snow .ql-picker.ql-size .ql-picker-label[data-value="32px"]::before {
    content: '32px' !important;
}

/* =========================
   APPLY ACTUAL FONT FAMILIES
========================= */
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
   PICKER LABELS LAYOUT
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
   TOOLBAR SINGLE LINE FIX
========================= */
.ql-toolbar.ql-snow {
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    white-space: nowrap !important;
    overflow-x: auto !important;
    overflow-y: visible !important;
    gap: 0 !important;
    padding: 8px 10px !important;
    position: relative !important;
    z-index: 20 !important;
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
.ql-toolbar .ql-picker-label,
.ql-toolbar .ql-clean {
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
    position: relative !important;
}

.ql-toolbar .ql-formats:last-child {
    margin-right: 0 !important;
}

.ql-toolbar .ql-clean {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-left: 0 !important;
    padding: 0 6px !important;
}

/* =========================
   DROPDOWN OVER EDITOR FIX
========================= */
.ql-snow,
.ql-toolbar.ql-snow,
#editor {
    overflow: visible !important;
}

.ql-snow .ql-picker-options {
    position: absolute !important;
    z-index: 99999 !important;
    background: #fff !important;
    border: 1px solid #d1d5db !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
    max-height: 220px !important;
    overflow-y: auto !important;
}

.ql-container.ql-snow {
    position: relative !important;
    z-index: 1 !important;
}
/* FIX DESCRIPTION SCROLL */
#editorWrapper {
    height: 280px;
    overflow: hidden;
}

#editorWrapper .ql-toolbar.ql-snow {
    overflow-x: auto !important;
    overflow-y: hidden !important;
}

#editorWrapper .ql-container.ql-snow {
    height: calc(100% - 42px) !important;
    overflow: hidden !important;
}

#editorWrapper .ql-editor {
    height: 100% !important;
    max-height: 100% !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    word-break: break-word !important;
}
/* ===== PERFECT MATCH SCROLLBAR ===== */
#editorWrapper .ql-editor::-webkit-scrollbar,
#editor .ql-editor::-webkit-scrollbar {
    width: 8px;
}

#editorWrapper .ql-editor::-webkit-scrollbar-track,
#editor .ql-editor::-webkit-scrollbar-track {
    background: #f1f1f1;   /* light grey track */
}

#editorWrapper .ql-editor::-webkit-scrollbar-thumb,
#editor .ql-editor::-webkit-scrollbar-thumb {
    background: #a8a8a8;   /* medium grey thumb */
    border-radius: 999px;
}

#editorWrapper .ql-editor::-webkit-scrollbar-thumb:hover,
#editor .ql-editor::-webkit-scrollbar-thumb:hover {
    background: #8c8c8c;   /* darker on hover */
}

/* =========================
   ERROR ALERT
========================= */
.custom-error-alert {
    background: #fee2e2;
    border-left: 6px solid #ef4444;
    color: #991b1b;
    padding: 14px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 18px;
}
/* Description validation error state */
.description-error-border {
    border: 1px solid #ef4444 !important;
    border-radius: 0.375rem !important;
}

.description-error-border .ql-toolbar.ql-snow,
.description-error-border .ql-container.ql-snow {
    border-color: #ef4444 !important;
}

#descriptionFieldWrapper {
    scroll-margin-top: 120px;
}
/* Consistent error styling for all fields */
.border-red-600 {
    border-color: #ef4444 !important;
}

input.border-red-600, 
select.border-red-600 {
    border: 1px solid #ef4444 !important;
}

/* Make sure select elements show error border properly */
select.border-red-600 {
    border: 1px solid #ef4444 !important;
}
/* Error message styling matching the screenshot */
.field-error-message {
    color: #dc2626 !important;
    font-size: 13px !important;
    margin-top: 6px !important;
    display: block !important;
    font-weight: 500 !important;
}

.description-error-message {
    color: #dc2626 !important;
    font-size: 13px !important;
    margin-top: 6px !important;
    display: block !important;
    font-weight: 500 !important;
}

/* Red border for error state */
.border-red-600,
input.border-red-600,
select.border-red-600 {
    border: 1px solid #dc2626 !important;
    outline: none !important;
}

/* Description editor error border */
.description-error-border {
    border: 1px solid #dc2626 !important;
    border-radius: 0.375rem !important;
}

.description-error-border .ql-toolbar.ql-snow,
.description-error-border .ql-container.ql-snow {
    border-color: #dc2626 !important;
}

/* Ensure the error message appears below the field */
#descriptionFieldWrapper {
    scroll-margin-top: 120px;
    position: relative;
}

/* Style for browser's native validation messages to match */
:invalid {
    box-shadow: none;
}

/* Make sure select elements show error properly */
select.border-red-600 {
    border: 1px solid #dc2626 !important;
}
.fake-browser-error {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #d1d5db;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 14px;
    color: #111827;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 8px;
    width: fit-content;
}

.fake-browser-error-icon {
    background: #f97316;
    color: white;
    font-weight: bold;
    border-radius: 4px;
    padding: 2px 6px;
}
</style>

@endsection