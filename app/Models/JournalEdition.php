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
            // O slug será gerado no controller para garantir unicidade
            // Este método só será usado como fallback
            if (empty($journalEdition->slug)) {
                $baseSlug = Str::slug($journalEdition->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $journalEdition->slug = $slug;
            }
        });

        static::updating(function ($journalEdition) {
            // O slug será gerado no controller se o título mudar
            // Este método só será usado como fallback
            if ($journalEdition->isDirty('title') && empty($journalEdition->slug)) {
                $baseSlug = Str::slug($journalEdition->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $journalEdition->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $journalEdition->slug = $slug;
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
