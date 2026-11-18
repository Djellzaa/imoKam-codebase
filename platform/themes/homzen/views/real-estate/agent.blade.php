@php
    Theme::set('breadcrumbEnabled', 'no');
    Theme::set('pageTitle', $account->name);

    // Query për pronat e agjentit
    $query = \Botble\RealEstate\Models\Property::query()
        ->whereHas('author', function ($q) use ($account) {
            $q->where('id', $account->id);
        })
        ->with(RealEstateHelper::getPropertyRelationsQuery())
        ->where(RealEstateHelper::getPropertyDisplayQueryConditions());

    // Sortimi
    $sortBy = request('sort_by');
    switch ($sortBy) {
        case 'price_asc':
            $query->orderBy('price', 'ASC');
            break;
        case 'price_desc':
            $query->orderBy('price', 'DESC');
            break;
        case 'name_asc':
            $query->orderByRaw('LOWER(name) ASC');
            break;
        case 'name_desc':
            $query->orderByRaw('LOWER(name) DESC');
            break;
        default:
            $query->orderByDesc('created_at');
            break;
    }

    // Pagination
    $perPage = (int) request('per_page', 12);
    $properties = $query->paginate($perPage)->appends(request()->query());
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<section class="agent-detail-section">
    <div class="agent-header">
        <div class="agent-avatar">
            {{ RvMedia::image($account->avatar_url, $account->name, 'medium-square') }}
        </div>
        <div class="agent-info">
            <h2 class="agent-name">{{ $account->name }}</h2>
            @if($account->company)
                <p class="agent-company">{!! BaseHelper::clean(__('Company Agent at :company', ['company' => "<strong>$account->company</strong>"])) !!}</p>
            @endif
            <div class="agent-contact-info">
                @if($account->phone && ! setting('real_estate_hide_agency_phone', false))
                    <a href="tel:{{ $account->phone }}" class="agent-info-item">
                        <x-core::icon name="ti ti-phone" />
                        {{ $account->phone }}
                    </a>
                @endif
                @if($account->email && ! setting('real_estate_hide_agency_email', false))
                    <a href="mailto:{{ $account->email }}" class="agent-info-item">
                        <x-core::icon name="ti ti-mail" />
                        {{ $account->email }}
                    </a>
                @endif
                <div class="agent-info-item">
                    <x-core::icon name="ti ti-calendar" />
                    {{ __('Joined') }} {{ $account->created_at->diffForHumans() }}
                </div>
            </div>

            {!! Theme::partial('shortcodes.agents.partials.social-links', compact('account')) !!}
        </div>
    </div>

    @if($account->description)
        <div class="agent-about-section">
            <h5>{{ __('About Agent') }}</h5>
            <p class="agent-description">{!! BaseHelper::clean($account->description) !!}</p>
        </div>
    @endif
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

    @if ($properties->isNotEmpty())
        <div class="agent-properties-section">
            <h5>{{ __('Properties by this agent') }}</h5>
            @include(Theme::getThemeNamespace('views.real-estate.properties.index'))
        </div>
    @endif

    {!! apply_filters('real_estate_agent_details', null, $account) !!}
</section>
