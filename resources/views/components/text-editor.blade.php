@props(['id', 'name' => null, 'value' => ''])

<textarea id="{{ $id }}" @if($name) name="{{ $name }}" @endif
          {{ $attributes->merge(['class' => 'form-control app-text-editor']) }}>{{ $value }}</textarea>
