@csrf
@isset($method)
    @method($method)
@endisset

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium" for="type">Report type</label>
        <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="type" name="type" required>
            @foreach (['lost' => 'Lost item', 'found' => 'Found item'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $report->type) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium" for="category">Category</label>
        <select class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="category" name="category" required>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $report->category) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('category') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium" for="title">Item title</label>
        <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="title" name="title" value="{{ old('title', $report->title) }}" required>
        @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium" for="location">Location</label>
        <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="location" name="location" value="{{ old('location', $report->location) }}" required>
        @error('location') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium" for="item_date">Date lost or found</label>
        <input class="mt-1 h-11 w-full rounded-md border border-stone-300 px-3 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="item_date" name="item_date" type="date" value="{{ old('item_date', optional($report->item_date)->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>
        @error('item_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium" for="image">Item image</label>
        <input class="mt-1 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:font-medium file:text-stone-800 hover:file:bg-stone-200 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="image" name="image" type="file" accept="image/*">
        <p class="mt-1 text-xs text-stone-500">Optional JPG, PNG, or WebP up to 2 MB.</p>
        @error('image') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-5">
    <label class="block text-sm font-medium" for="description">Description</label>
    <textarea class="mt-1 min-h-36 w-full rounded-md border border-stone-300 px-3 py-2 focus:border-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-700/20" id="description" name="description" required>{{ old('description', $report->description) }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button class="h-11 rounded-md bg-teal-700 px-5 font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-700 focus:ring-offset-2" type="submit">{{ $button }}</button>
    <a class="inline-flex h-11 items-center rounded-md border border-stone-300 px-5 font-semibold text-stone-800 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-700" href="{{ route('dashboard') }}">Cancel</a>
</div>
