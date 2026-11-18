@php
    Theme::set('pageTitle', $category->name);

    $query = \Botble\RealEstate\Models\Property::query()
        ->whereHas('categories', function ($q) use ($category) {
            $q->where('re_categories.id', $category->id);
        })
        ->with(RealEstateHelper::getPropertyRelationsQuery())
        ->where(RealEstateHelper::getPropertyDisplayQueryConditions());

    $sortBy = request('sort_by');
    if ($sortBy === 'price_asc') {
        $query->orderBy('price', 'ASC');
    } elseif ($sortBy === 'price_desc') {
        $query->orderBy('price', 'DESC');
    } elseif ($sortBy === 'name_asc') {
        $query->orderByRaw('LOWER(name) ASC');
    } elseif ($sortBy === 'name_desc') {
        $query->orderByRaw('LOWER(name) DESC');
    } else {
        $query->orderByDesc('created_at');
    }

    $perPage = request('per_page', 12);
    $properties = $query->paginate($perPage);
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<div class="flat-section">
    <section class="flat-recommended">
        <div class="container">
            <form action="{{ request()->url() }}" method="get" class="filter-form mb-4" id="filter-form">
                <div class="d-flex justify-content-end align-items-center flex-wrap mb-3 gap-2">
                    <div class="filters-wrapper d-flex gap-2 align-items-center">

                        {{-- Layout toggle --}}
                        <div class="layout-toggle d-flex gap-2 align-items-center">
                            <button type="button" class="btn btn-sm p-2" data-layout="grid"
                                onclick="document.querySelector('[name=layout]').value='grid'; document.getElementById('filter-form').submit();">
                                <i class="fas fa-th" style="font-size:1.8rem; opacity:0.6;"></i>
                            </button>
                            <button type="button" class="btn btn-sm p-2" data-layout="list"
                                onclick="document.querySelector('[name=layout]').value='list'; document.getElementById('filter-form').submit();">
                                <i class="fas fa-list" style="font-size:1.8rem; opacity:0.6;"></i>
                            </button>
                            <input type="hidden" name="layout" value="{{ request('layout', 'grid') }}" />
                        </div>

                        {{-- Sort by --}}
                        <select name="sort_by" class="form-select select_js" style="min-width: 160px" onchange="this.form.submit()">
                            <option value="">{{ trans('custom.Selection') }}</option>
                            <option value="price_asc" @selected(request('sort_by') == 'price_asc')>{{ trans('custom.Price (Low to High)') }}</option>
                            <option value="price_desc" @selected(request('sort_by') == 'price_desc')>{{ trans('custom.Price (High to Low)') }}</option>
                            <option value="name_asc" @selected(request('sort_by') == 'name_asc')>{{ trans('custom.Name (A-Z)') }}</option>
                            <option value="name_desc" @selected(request('sort_by') == 'name_desc')>{{ trans('custom.Name (Z-A)') }}</option>
                        </select>

                        {{-- Per page --}}
                        <select name="per_page" class="form-select select_js" style="min-width: 120px" onchange="this.form.submit()">
                            <option value="12" @selected(request('per_page', 12) == 12)>{{ trans('custom.12 per page') }}</option>
                            <option value="24" @selected(request('per_page') == 24)>{{ trans('custom.24 per page') }}</option>
                            <option value="36" @selected(request('per_page') == 36)>{{ trans('custom.36 per page') }}</option>
                            <option value="48" @selected(request('per_page') == 48)>{{ trans('custom.48 per page') }}</option>
                        </select>

                    </div>
                </div>
            </form>
        </div>

        @php
            $itemLayout = request('layout', 'grid');
            $itemLayout = in_array($itemLayout, ['grid', 'list']) ? $itemLayout : 'grid';
        @endphp

        @if ($properties->isNotEmpty())
            @if ($itemLayout === 'list')
                <div class="container list-properties">
                    @foreach($properties as $property)
                        @include(Theme::getThemeNamespace('views.real-estate.properties.item-list'))
                    @endforeach
                </div>
            @else
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
                        @foreach($properties as $property)
                            <div class="col">
                                @include(Theme::getThemeNamespace('views.real-estate.properties.item-grid'))
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($properties->hasPages())
                <div class="container justify-content-center wd-navigation mt-5">
                    {{ $properties->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
                </div>
            @endif
        @else
            <div class="container">
                <div class="alert alert-warning" role="alert">
                    {{ trans('custom.No properties found in this category.') }}
                </div>
            </div>
        @endif
    </section>
</div>
