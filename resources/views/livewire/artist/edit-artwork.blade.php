<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Edit Artwork') }}: {{ $title }}
                </h2>
                <a href="{{ route('artist.artworks') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                    &larr; {{ __('Back to My Artworks') }}
                </a>
            </div>

            <form wire:submit.prevent="update" class="space-y-6">
                <!-- Title -->
                <div>
                    <x-label for="title" value="{{ __('Title') }}" />
                    <x-input id="title" class="block mt-1 w-full" type="text" wire:model="title" required autofocus />
                    <x-input-error for="title" class="mt-2" />
                </div>

                <!-- Price -->
                <div>
                    <x-label for="price" value="{{ __('Price (Rs.)') }}" />
                    <x-input id="price" class="block mt-1 w-full" type="number" step="0.01" wire:model="price" required />
                    <x-input-error for="price" class="mt-2" />
                </div>

                <!-- Description -->
                <div>
                    <x-label for="description" value="{{ __('Description') }}" />
                    <textarea id="description"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full h-32"
                        wire:model="description"
                        required></textarea>
                    <x-input-error for="description" class="mt-2" />
                </div>

                <!-- Image -->
                <div>
                    <x-label for="image" value="{{ __('Artwork Image (Leave blank to keep current)') }}" />

                    <div class="mt-2 flex items-start space-x-4">
                        @if ($existingImage)
                        <div class="shrink-0">
                            <p class="text-xs text-gray-500 mb-1">Current Image</p>
                            <img src="{{ $existingImage }}" class="w-32 h-32 object-cover rounded-lg border dark:border-gray-700">
                        </div>
                        @endif

                        @if ($image)
                        <div class="shrink-0">
                            <p class="text-xs text-gray-500 mb-1">New Preview</p>
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border-2 border-indigo-500">
                        </div>
                        @else
                        <div class="flex-1">
                            <input type="file" id="image" wire:model="image"
                                class="block w-full text-sm text-gray-500 mt-6
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" />
                            <p class="text-xs text-gray-400 mt-2">PNG, JPG up to 10MB</p>
                        </div>
                        @endif
                    </div>

                    @if ($image)
                    <button type="button" wire:click="$set('image', null)" class="mt-2 text-xs text-red-600 hover:text-red-500">
                        Remove new image
                    </button>
                    @endif

                    <x-input-error for="image" class="mt-2" />
                </div>

                <div class="flex items-center justify-end border-t border-gray-100 dark:border-gray-700 pt-6">
                    <x-button class="bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Save Changes') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>