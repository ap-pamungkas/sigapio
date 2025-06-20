@props(['active'=> false, 'url'=>'', 'label'=>''])

@php
    $class = ($active ?? false) ? 'active' : '';

@endphp

<a  href="{{  route("$url")}}" {{ $attributes->merge(['class' => $class]) }}>
    <i class="nav-icon {{ $icon ?? 'bi bi-chevron-right' }} "></i>
    <p>{{ $label }} </p>
  </a>

