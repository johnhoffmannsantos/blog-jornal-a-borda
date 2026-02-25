<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JournalEdition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'pdf_file',
        'published_date',
        'edition_number',
        'views',
        'downloads',
        'is_featured',
    ];

    protected $casts = [
        'published_date' => 'date',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'downloads' => 'integer',
        'edition_number' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($journalEdition) {
            if (empty($journalEdition->slug)) {
                $journalEdition->slug = Str::slug($journalEdition->title);
            }
        });

        static::updating(function ($journalEdition) {
            if ($journalEdition->isDirty('title') && empty($journalEdition->slug)) {
                $journalEdition->slug = Str::slug($journalEdition->title);
            }
        });
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function incrementDownloads()
    {
        $this->increment('downloads');
    }
}
