<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Title
    </label>
    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
           type="text"
           name="title"
           value="{{ $project->title ?? '' }}"
           placeholder="Title">
    @error('title')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Description
    </label>
    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
           type="text"
           name="description"
           placeholder="Description"
           rows="10">{{ $project->description ?? '' }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <button type="submit" class="btn-blue">{{ $button_text }}</button>
</div>
