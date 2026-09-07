<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Helpers;

class Icon
{
    public const DIRECTORY = '/resources/images/icons/';

    public static function render(string $name, string $class = 'size-[1em]'): string
    {
        return self::inline(get_template_directory() . self::DIRECTORY . "{$name}.svg", $class);
    }

    public static function inline(string $path, string $class): string
    {
        if (! file_exists($path)) {
            return '';
        }

        $svg = file_get_contents($path);

        if (false === $svg || false === strpos($svg, '<svg')) {
            return '';
        }

        $attributes = sprintf(' aria-hidden="true" focusable="false" class="%s"', esc_attr($class));

        if (strpos($svg, 'class="') !== false) {
            $attributes = ' aria-hidden="true" focusable="false"';
        }

        return preg_replace('/<svg\b/', '<svg' . $attributes, $svg, 1);
    }
}
