@php
    Theme::asset()->container('footer')->usePath()->add('nouislider', 'js/nouislider.min.js');
    Theme::asset()->container('footer')->usePath()->add('wnumb', 'js/wNumb.min.js');
    Theme::asset()->container('footer')->usePath()->add('nice-select', 'js/jquery.nice-select.min.js');

    $style ??= 1;
    $noLeftRound ??= false;
    $centeredTabs ??= false;

    $allTabs = [
        'new' => __('custom.new_construction'),
        'rent' => __('custom.for_rent'),
        'sale' => __('custom.for_sale'),
    ];

    $selectedTabs = explode(',', $shortcode->tabs ?: 'new,rent,sale');
    $tabs = collect($allTabs)->only($selectedTabs);

    $tabs = collect(['new' => __('custom.new_construction')])->merge($tabs);

    $defaultSearchType = $shortcode->default_search_type ?: 'new';
    if (!in_array($defaultSearchType, $tabs->keys()->toArray())) {
        $defaultSearchType = $tabs->keys()->first();
    }
@endphp

@if ($tabs->isNotEmpty())
    <div class="flat-tab flat-tab-form">
        @if (count($tabs) > 1)
            <ul @class(['nav-tab-form', 'style-1' => $style === 1, 'style-2' => in_array($style, [2, 3]), 'style-3' => $style === 4, 'justify-content-center' => $centeredTabs]) role="tablist">
                @foreach ($tabs as $key => $tab)
                    <li class="nav-tab-item" role="presentation">
                        <a
                            href="javascript:void(0);"
                            @class(['nav-link-item', 'active' => $key === $defaultSearchType])
                            data-bs-toggle="tab"
                            data-bb-toggle="change-search-type"
                            data-value="{{ $key }}"
                        >
                            {{ $tab }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="tab-content">
            <div class="tab-pane fade active show" role="tabpanel">
                <form
                    @class(['form-sl', 'flat-filter-form' => $style === 3])
                    action="{{ RealEstateHelper::getPropertiesListPageUrl() }}"
                    method="get"
                    id="hero-search-form"
                >
                    <input
                        name="type"
                        type="hidden"
                        value="{{ $defaultSearchType }}"
                        id="selected-search-type"
                    >
                    @include(Theme::getThemeNamespace('views.real-estate.partials.filters.base'))

                    @if (theme_option('real_estate_enable_advanced_search', 'yes') == 'yes')
                        <div class="wd-search-form property-search-form">
                            <div class="grid-2 group-box group-price">
                                @include(Theme::getThemeNamespace('views.real-estate.partials.filters.price'), ['maxPrice' => get_max_properties_price()])
                                @include(Theme::getThemeNamespace('views.real-estate.partials.filters.square'))
                            </div>
                            <div class="group-box">
                                <div class="group-select grid-3">
                                    @include(Theme::getThemeNamespace('views.real-estate.partials.filters.bathroom'))
                                    @include(Theme::getThemeNamespace('views.real-estate.partials.filters.bedroom'))
                                    @include(Theme::getThemeNamespace('views.real-estate.partials.filters.floor'))
                                    @include(Theme::getThemeNamespace('views.real-estate.partials.filters.projects'))
                                </div>
                            </div>
                            @include(Theme::getThemeNamespace('views.real-estate.partials.filters.features'))
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let searchForm = document.getElementById("hero-search-form");
        let searchTypeInput = document.getElementById("selected-search-type");

        function updateSearchType(selectedType) {
            console.log("Changing search type to:", selectedType);
            searchTypeInput.value = selectedType;
            document.querySelectorAll(".nav-link-item").forEach(item => item.classList.remove("active"));
            document.querySelector(`[data-value='${selectedType}']`).classList.add("active");
            localStorage.setItem("selectedSearchType", selectedType);
        }

        document.querySelectorAll("[data-bb-toggle='change-search-type']").forEach(tab => {
            tab.addEventListener("click", function () {
                let selectedType = this.getAttribute("data-value");
                updateSearchType(selectedType);
            });
        });

        let savedType = localStorage.getItem("selectedSearchType");
        if (savedType) {
            updateSearchType(savedType);
        }
    });
    </script>
@endif
