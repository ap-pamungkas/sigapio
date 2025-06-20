<div class="form-group mb-3">
    <label class="form-label" for="{{ $model }}">{{ $label ?? 'label' }}</label>
    <input 
        name="{{ $model }}"
        id="{{ $model }}"
        accept="{{ $accept ?? '' }}"
        type="{{ $type ?? 'text' }}"
        @isset($wirelive)
            wire:model.live="{{ $model }}"
        @else
            wire:model="{{ $model }}"
        @endisset
        wire:keyup="{{ $keyup ?? '' }}"
        class="form-control @error($model) is-invalid @enderror"
        placeholder="{{ $placeholder ?? 'placeholder' }}"
        @isset($readonly) readonly @endisset
    >
    <span class="text-muted">{{ $description ?? '' }}</span>
    @error($model)
        <span class="input-text-error">{{ $message }}</span>
    @enderror
</div>
