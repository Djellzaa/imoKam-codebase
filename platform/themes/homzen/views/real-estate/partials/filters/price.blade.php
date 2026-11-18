@if (theme_option('real_estate_enable_filter_by_price', 'yes') == 'yes')
    <div @class(['widget-price form-search-price', $class ?? null])>
        <div class="box-title-price">
            <span class="title-price">{{ __('Price Range') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-group me-2">
                <label class="form-label">{{ __('custom.Min Price') }}</label>
                <div class="input-group">
                    @if (get_application_currency()->is_prefix_symbol)
                        <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                    @endif
                    <input 
                        type="number" 
                        class="form-control" 
                        name="min_price" 
                        value="{{ BaseHelper::stringify(request()->float('min_price')) }}" 
                        min="0" 
                        max="{{ $maxPrice ?? get_max_properties_price() }}" 
                        placeholder="0"
                    />
                    @if (!get_application_currency()->is_prefix_symbol)
                        <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('custom.Max Price') }}</label>
                <div class="input-group">
                    @if (get_application_currency()->is_prefix_symbol)
                        <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                    @endif
                    <input 
                        type="number" 
                        class="form-control" 
                        name="max_price" 
                        value="{{ BaseHelper::stringify(request()->float('max_price')) }}" 
                        min="0" 
                        max="{{ $maxPrice ?? get_max_properties_price() }}" 
                        placeholder="{{ __('0') }}" 
                    />
                    @if (!get_application_currency()->is_prefix_symbol)
                        <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
