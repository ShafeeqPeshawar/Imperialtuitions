<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    protected $fillable = [
        'course_id','title','description','sort_order','is_active'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * HTML safe for display: trim outer whitespace and remove leading/trailing
     * Quill empty paragraphs (<p><br></p>, <p></p>, etc.).
     */
    public function getTrimmedDescriptionAttribute(): string
    {
        $html = (string) ($this->attributes['description'] ?? '');
        if ($html === '') {
            return '';
        }

        $html = trim($html);
        $emptyP = '\s*<p[^>]*>(?:\s|&nbsp;|&#160;|<br\s*\/?\s*>)*<\/p>\s*';
        $html = preg_replace('/^(?:' . $emptyP . ')+/iu', '', $html) ?? '';
        $html = preg_replace('/(?:' . $emptyP . ')+$/iu', '', $html) ?? '';

        return trim($html);
    }
}
