@if (theme_option('real_estate_enable_filter_by_square', 'yes') == 'yes')
    @php
        $minSquare = get_min_square() ?: 0;
        $maxSquare = get_max_square() ?: 10000;
    @endphp

    @if ($minSquare < $maxSquare)
        <div @class(['widget-price form-search-square', $class ?? null])>
            <div class="box-title-price">
                <span class="title-price">{{ __('custom.Square Range') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="form-group me-2">
                    <label class="form-label">{{ __('custom.Min Square') }}</label>
                    <div class="input-group">
                        <input type="number" 
                            class="form-control" 
                            name="min_square" 
                            value="{{ BaseHelper::stringify(request()->query('min_square')) }}" 
                            min="{{ $minSquare }}" 
                            max="{{ $maxSquare }}" 
                            placeholder="{{ $minSquare }}" 
                        />
                        <span class="input-group-text">{{ setting('real_estate_square_unit', 'm²') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('custom.Max Square') }}</label>
                    <div class="input-group">
                        <input type="number" 
                            class="form-control" 
                            name="max_square" 
                            value="{{ BaseHelper::stringify(request()->query('max_square')) }}" 
                            min="{{ $minSquare }}" 
                            max="{{ $maxSquare }}" 
                             placeholder="{{ __('0') }}" 
                        />
                        <span class="input-group-text">{{ setting('real_estate_square_unit', 'm²') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
