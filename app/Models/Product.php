<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'name_ar',
        'category',
        'category_ar',
        'type',
        'price',
        'currency',
        'image_path',
        'is_visible',
        'fallback_placeholder',
        'short_description',
        'short_description_ar',
        'full_description',
        'full_description_ar',
        'flavor_profile',
        'flavor_profile_ar',
        'allergens',
        'allergens_ar',
        'highlights',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'price' => 'decimal:2',
        'flavor_profile' => 'array',
        'flavor_profile_ar' => 'array',
        'allergens' => 'array',
        'allergens_ar' => 'array',
        'highlights' => 'array',
    ];

    /**
     * Locale-aware field helpers. Each falls back to the English field
     * if the Arabic column is empty, so a product missing a translation
     * never renders blank on the AR storefront.
     */
    public function localizedName(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ar' && filled($this->name_ar)
            ? $this->name_ar
            : $this->name;
    }

    public function localizedCategory(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ar' && filled($this->category_ar)
            ? $this->category_ar
            : $this->category;
    }

    public function localizedShortDescription(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ar' && filled($this->short_description_ar)
            ? $this->short_description_ar
            : $this->short_description;
    }

    public function localizedFullDescription(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ar' && filled($this->full_description_ar)
            ? $this->full_description_ar
            : $this->full_description;
    }

    public function localizedFlavorProfile(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        $arValue = $this->flavor_profile_ar;

        return $locale === 'ar' && filled($arValue)
            ? $arValue
            : ($this->flavor_profile ?? []);
    }

    public function localizedAllergens(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        $arValue = $this->allergens_ar;

        return $locale === 'ar' && filled($arValue)
            ? $arValue
            : ($this->allergens ?? []);
    }
}
