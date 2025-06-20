<div class="d-cols-3">
    <div class="form-group ">
        <label class="form-label" for="{{ $model }}">{{ $label }}</label>
        <select wire:model="{{ $model }}" id="{{ $model }}" class="form-select @error($model) is-invalid @enderror">
            {{ $slot }}
        </select>
        @error($model)
            <span class="input-text-error">{{ $message }}</span>
        @enderror
    </div>
</div>
