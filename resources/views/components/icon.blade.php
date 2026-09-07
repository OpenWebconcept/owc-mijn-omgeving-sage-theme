@props([
    'name' => '',
    'class' => 'size-[1em]',
])

{!! \OWC\MijnOmgeving\Helpers\Icon::render($name, $class) !!}
