@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $isEdit = isset($instrument);
@endphp

@csrf
<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Назва</span>
            <input type="text" name="title" value="{{ old('title', $instrument->title ?? '') }}" class="input" required>
            @error('title') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Slug (опційно)</span>
            <input type="text" name="slug" value="{{ old('slug', $instrument->slug ?? '') }}" class="input" placeholder="fender-strat">
            @error('slug') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
        <div class="grid grid-cols-2 gap-4">
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Категорія</span>
                <select name="category_id" class="input" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $instrument->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </label>
            <label class="space-y-2 block">
                <span class="text-sm text-gray-700">Бренд</span>
                <select name="brand_id" class="input" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $instrument->brand_id ?? '') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
            </label>
        </div>
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Ціна</span>
            <input type="number" name="price" step="0.01" value="{{ old('price', $instrument->price ?? '') }}" class="input" required>
            @error('price') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Ціна зі знижкою (опційно)</span>
            <input type="number" name="sale_price" step="0.01" value="{{ old('sale_price', $instrument->sale_price ?? '') }}" class="input" placeholder="14999">
            <p class="text-xs text-gray-500">Вкажіть акційну ціну, щоб задати знижку. Порожнє поле = без знижки.</p>
            @error('sale_price') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
    </div>
    <div class="space-y-4">
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Короткий опис</span>
            <input type="text" name="short_description" value="{{ old('short_description', $instrument->short_description ?? '') }}" class="input" required>
            @error('short_description') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Повний опис</span>
            <textarea name="full_description" rows="4" class="input" required>{{ old('full_description', $instrument->full_description ?? '') }}</textarea>
            @error('full_description') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
        <label class="space-y-2 block">
            <span class="text-sm text-gray-700">Характеристики (кожна з нового рядка)</span>
            <textarea name="specs" rows="4" class="input" placeholder="Тіло: Alder&#10;Лади: 22">{{ old('specs', isset($instrument) && $instrument->specs ? implode("\n", $instrument->specs) : '') }}</textarea>
            @error('specs') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </label>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-6 mt-6">
    <label class="space-y-2 block">
        <span class="text-sm text-gray-700">Головне зображення</span>
        <input type="file" name="main_image" class="input file:mr-3 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-blue-600 file:text-white file:font-semibold">
        @error('main_image') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        @if ($isEdit && $instrument->main_image)
            <img src="{{ Str::startsWith($instrument->main_image, ['http', 'https']) ? $instrument->main_image : Storage::url($instrument->main_image) }}" alt="Головне фото" class="mt-3 h-32 rounded-xl object-cover border border-gray-200">
        @endif
    </label>
    <label class="space-y-2 block">
        <span class="text-sm text-gray-700">Галерея (можна кілька)</span>
        <input type="file" name="gallery[]" multiple class="input file:mr-3 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-blue-600 file:text-white file:font-semibold">
        @error('gallery.*') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        @if ($isEdit && $instrument->images->count())
            <div class="flex flex-wrap gap-3 mt-2">
                @foreach ($instrument->images as $image)
                    <img src="{{ Str::startsWith($image->image_path, ['http', 'https']) ? $image->image_path : Storage::url($image->image_path) }}" alt="Галерея" class="h-20 w-28 object-cover rounded-lg border border-gray-200">
                @endforeach
            </div>
        @endif
    </label>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">{{ $isEdit ? 'Оновити' : 'Створити' }}</button>
    <a href="{{ route('admin.instruments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Скасувати</a>
</div>
