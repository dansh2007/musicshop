<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Instrument;
use App\Models\InstrumentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Instrument::query()
            ->with(['brand', 'category', 'images'])
            ->latest();

        // Комбінований фільтр як на jam.ua
        $filters = [
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'price_from' => $request->input('price_from'),
            'price_to' => $request->input('price_to'),
            'q' => $request->input('q'),
        ];

        if ($filters['category']) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category']));
        }

        if ($filters['brand']) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $filters['brand']));
        }

        if ($filters['price_from']) {
            $query->where('price', '>=', $filters['price_from']);
        }

        if ($filters['price_to']) {
            $query->where('price', '<=', $filters['price_to']);
        }

        if ($filters['q']) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%'.$filters['q'].'%')
                    ->orWhere('short_description', 'like', '%'.$filters['q'].'%')
                    ->orWhere('full_description', 'like', '%'.$filters['q'].'%');
            });
        }

        $perPage = $request->routeIs('admin.*') ? 20 : 12;
        $instruments = $query->paginate($perPage)->withQueryString();

        if ($request->routeIs('admin.*')) {
            return view('admin.instruments.index', compact('instruments'));
        }

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $favoriteIds = auth()->check()
            ? Favorite::where('user_id', auth()->id())->pluck('instrument_id')->toArray()
            : [];

        return view('instruments.index', compact('instruments', 'categories', 'brands', 'filters', 'favoriteIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.instruments.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:instruments,slug'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'full_description' => ['required', 'string'],
            'specs' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'main_image' => ['nullable', 'image', 'max:4096'],
            'gallery.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $slug = $this->makeUniqueSlug($validated['title'], $validated['slug'] ?? null);
        $specs = $this->parseSpecs($validated['specs'] ?? null);

        $instrument = new Instrument([
            'title' => $validated['title'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'],
            'specs' => $specs,
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
        ]);

        if ($request->hasFile('main_image')) {
            $instrument->main_image = $request->file('main_image')->store('instruments', 'public');
        }

        $instrument->save();

        if ($request->hasFile('gallery')) {
            // Якщо приходять нові фото – спочатку чистимо попередні
            foreach ($instrument->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            foreach ($request->file('gallery') as $image) {
                $path = $image->store('instruments/gallery', 'public');
                $instrument->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.instruments.index')->with('status', 'Інструмент додано.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instrument $instrument)
    {
        $instrument->load(['brand', 'category', 'images']);

        return view('instruments.show', compact('instrument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instrument $instrument)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $instrument->load('images');

        return view('admin.instruments.edit', compact('instrument', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:instruments,slug,'.$instrument->id],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'short_description' => ['required', 'string', 'max:255'],
            'full_description' => ['required', 'string'],
            'specs' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'main_image' => ['nullable', 'image', 'max:4096'],
            'gallery.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $slug = $this->makeUniqueSlug($validated['title'], $validated['slug'] ?? null, $instrument->id);
        $specs = $this->parseSpecs($validated['specs'] ?? null);

        $instrument->fill([
            'title' => $validated['title'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'],
            'specs' => $specs,
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
        ]);

        if ($request->hasFile('main_image')) {
            if ($instrument->main_image) {
                Storage::disk('public')->delete($instrument->main_image);
            }
            $instrument->main_image = $request->file('main_image')->store('instruments', 'public');
        }

        $instrument->save();

        if ($request->hasFile('gallery')) {
            // Видаляємо старі фото та записи галереї перед додаванням нових
            foreach ($instrument->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            foreach ($request->file('gallery') as $image) {
                $path = $image->store('instruments/gallery', 'public');
                $instrument->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.instruments.index')->with('status', 'Інструмент оновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instrument $instrument)
    {
        if ($instrument->main_image) {
            Storage::disk('public')->delete($instrument->main_image);
        }

        foreach ($instrument->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $instrument->delete();

        return redirect()->route('admin.instruments.index')->with('status', 'Інструмент видалено.');
    }

    private function parseSpecs(?string $specs): ?array
    {
        if (!$specs) {
            return null;
        }

        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $specs)));

        return $lines ? array_values($lines) : null;
    }

    private function makeUniqueSlug(string $title, ?string $providedSlug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($providedSlug ?: $title);
        $slug = $base;
        $counter = 2;

        // Створюємо унікальний slug без гонок та конфліктів при оновленні
        while (
            Instrument::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
