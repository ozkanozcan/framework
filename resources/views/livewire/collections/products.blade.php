<div>
    <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-secondary-800">
        <div class="sm:flex sm:items-center sm:justify-between p-4 sm:py-5 sm:px-6">
            <h3 class="text-base text-secondary-900 leading-6 font-medium dark:text-white">{{ __('Products') }}</h3>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                @if($collection->isManual())
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.products-lists', {{ json_encode([$collection->id, $this->productsIds]) }})" type="button">
                            {{ __('Browse') }}
                        </x-shopper::buttons.primary>
                    </span>
                @endif
                <div class="relative">
                    <x-shopper::label for="sort" class="sr-only" :value="__('Sort products by')" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                            {{ __('Sort by:') }}
                        </span>
                    </div>
                    <x-shopper::forms.select wire:model.lazy="sortBy" id="sort" class="pl-18 pr-10 py-2">
                        <option value="alpha_asc">{{ __('Alpha Asc') }}</option>
                        <option value="alpha_desc">{{ __('Alpha Desc') }}</option>
                        <option value="price_desc">{{ __('Price Desc') }}</option>
                        <option value="price_asc">{{ __('Price ASC') }}</option>
                        <option value="created_desc">{{ __('Created Desc') }}</option>
                        <option value="created_asc">{{ __('Created Asc') }}</option>
                    </x-shopper::forms.select>
                </div>
            </div>
        </div>
        <div class="border-t border-secondary-200 p-4 sm:px-6 sm:py-5 dark:border-secondary-700" wire:poll.visible>
            @if($products->isNotEmpty())
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    @foreach($products as $product)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center">
                                @if($product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                                    <span class="shrink-0 h-10 w-10 rounded-md overflow-hidden">
                                        <img class="object-cover object-center w-full h-full block" src="{{ $product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="{{ $product->name }}" />
                                    </span>
                                @else
                                    <span class="flex items-center justify-center h-10 w-10 bg-secondary-100 text-secondary-300 rounded-md dark:bg-secondary-700 dark:text-secondary-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                @endif
                                <p class="ml-4 text-sm font-medium text-secondary-500 dark:text-secondary-400">
                                    {{ $product->name }}
                                </p>
                            </div>
                            <button wire:key="product_{{ $loop->index }}" wire:click="removeProduct({{ $product->id }})" type="button" class="text-secondary-500 text-sm font-medium inline-flex items-center dark:text-secondary-400">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-5 w-full max-w-xs mx-auto flex flex-col items-center justify-center">
                    <span class="shrink-0 w-10 h-10">
                        <svg class="w-full h-full text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    <p class="mt-2.5 text-sm text-secondary-500 leading-5 text-center dark:text-secondary-400">
                        {{ __('There are no products in this collection. Add or change conditions to dynamically add products.') }}
                    </p>
                    @if($collection->isAutomatic())
                        <div class="relative mt-3">
                            <span class="inline-flex rounded-md shadow-sm">
                                <x-shopper::buttons.primary type="button">
                                    {{ __('Update conditions') }}
                                    <span class="ml-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                      {{ __('Soon') }}
                                    </span>
                                </x-shopper::buttons.primary>
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
