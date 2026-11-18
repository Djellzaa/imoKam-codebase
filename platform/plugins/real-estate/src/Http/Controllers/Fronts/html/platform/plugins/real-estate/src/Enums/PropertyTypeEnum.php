<?php

namespace Botble\RealEstate\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static PropertyTypeEnum SALE()
 * @method static PropertyTypeEnum RENT()
 * @method static PropertyTypeEnum NEW_CONSTRUCTION()
 */
class PropertyTypeEnum extends Enum
{
    public const SALE = 'sale';
    public const RENT = 'rent';
    public const NEW_CONSTRUCTION = 'new'; // Keep this lowercase for database consistency

    public static $langPath = 'plugins/real-estate::property.types';

    /**
     * Get labels for dropdown or display
     */
    public static function labels(): array
    {
        return [
            self::SALE => __('Sale'),
            self::RENT => __('Rent'),
            self::NEW_CONSTRUCTION => __('New'), 
        ];
    }

    /**
     * Render HTML badge for property type
     */
    public function toHtml(): HtmlString|string|null
    {
        $color = match ($this->value) {
            self::SALE => 'success',
            self::RENT => 'info',
            self::NEW_CONSTRUCTION => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
