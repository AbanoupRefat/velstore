                    <h5 class="mb-3">{{ __('store.shop.brands') }}</h5>
                    @foreach($brands as $brand)
                    <div class="form-check mb-3">
                        <input class="form-check-input filter-input" type="checkbox" name="brand[]" value="{{ $brand->id }}">
                        <label class="form-check-label">
                            {{ mb_convert_case($brand->translation->name ?? $brand->slug, MB_CASE_TITLE, "UTF-8") }}
                        </label>
                        <span class="text-muted">({{ $brand->products_count }})</span>
                    </div>
                    @endforeach

                    <h5 class="mb-3">{{ __('store.shop.categories') }}</h5>
                    @foreach($categories as $category)
                    <div class="form-check mb-3">
                        <input class="form-check-input filter-input" type="checkbox" name="category[]" value="{{ $category->id }}">
                        <label class="form-check-label">
                            {{ mb_convert_case($category->translation->name ?? $category->slug, MB_CASE_TITLE, "UTF-8") }}
                        </label>
                        <span class="text-muted">({{ $category->products_count }})</span>
                    </div>
                    @endforeach
                    
                    <h5>{{ __('store.shop.price') }}</h5>
                    <div class="price-filter mb-3">
                        <p id="priceRange" class="text-center">{{ $currency->symbol }}<span id="minPriceText">{{ $minPrice }}</span> - {{ $currency->symbol }}<span id="maxPriceText">{{ $maxPrice }}</span></p>
                        <div class="range-slider">
                            <input type="range" name="price_min" id="minPrice" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('price_min', $minPrice) }}" step="10">
                            <input type="range" name="price_max" id="maxPrice" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('price_max', $maxPrice) }}" step="10">
                        </div>
                    </div>

                    <h5 class="mb-3">{{ __('store.shop.colors') }}</h5>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @php
                            $webColors = config('web_colors');
                        @endphp
                        @foreach($colors as $color)
                            @php
                                $colorName = strtolower($color->value);
                                $hex = $webColors[$colorName] ?? $colorName;
                                $isChecked = in_array($colorName, request('color', []));
                            @endphp
                            <div class="form-check p-0" style="margin-right: 8px; margin-bottom: 8px;">
                                <input class="form-check-input filter-input d-none" type="checkbox" name="color[]" id="color-{{ $color->id }}" value="{{ $colorName }}" {{ $isChecked ? 'checked' : '' }}>
                                <label class="form-check-label color-circle-filter" for="color-{{ $color->id }}" 
                                       style="background-color: {{ $hex }}; width: 30px; height: 30px; display: block; border-radius: 50%; border: 2px solid {{ $isChecked ? 'var(--burgundy-main)' : '#ddd' }}; cursor: pointer; position: relative;"
                                       title="{{ $color->translated_value }}">
                                       @if($isChecked)
                                        <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: {{ in_array($colorName, ['white', 'yellow', 'cream', 'ivory']) ? 'black' : 'white' }}; font-size: 14px;">✓</span>
                                       @endif
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <h5 class="mt-4">{{ __('store.shop.size') }}</h5>
                    @foreach($sizes as $size)
                    <div class="form-check">
                        <input class="form-check-input filter-input" type="checkbox" name="size[]" value="{{ $size->value }}" {{ in_array($size->value, request('size', [])) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $size->translated_value }}</label>
                    </div>
                    @endforeach
