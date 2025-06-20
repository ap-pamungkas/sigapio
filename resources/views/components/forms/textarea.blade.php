<div class="form-group mb-3">
    <label for="{{ $model }}">{{ $label ?? 'label' }}</label>
    <textarea
        id="{{ $model }}"
        name="{{ $model }}"
        class="form-control @error($model) is-invalid @enderror"
        placeholder="{{ $placeholder ?? 'placeholder' }}"
        wire:model="{{ $model }}"
    >{{ old($model) }}</textarea>
    @error($model)
        <span class="input-text-error">{{ $message }}</span>
    @enderror
</div>

