<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(15);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug'],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Brand::create([
            'name' => $validated['name'],
            'slug' => $this->makeUniqueSlug($validated['name'], $validated['slug'] ?? null),
            'country' => $validated['country'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.brands.index')->with('status', 'Бренд створено.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return redirect()->route('admin.brands.edit', $brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,'.$brand->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:brands,slug,'.$brand->id],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $brand->update([
            'name' => $validated['name'],
            'slug' => $this->makeUniqueSlug($validated['name'], $validated['slug'] ?? null, $brand->id),
            'country' => $validated['country'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.brands.index')->with('status', 'Бренд оновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('status', 'Бренд видалено.');
    }

    private function makeUniqueSlug(string $name, ?string $providedSlug = null, ?int $ignoreId = null): string
    {
        $base = Str::slug($providedSlug ?: $name);
        $slug = $base;
        $counter = 2;

        while (
            Brand::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
