@php
    Theme::set('pageTitle', __('Agents'));
@endphp

<section class="flat-section flat-agents">
 <div class="container">
    <div class="mb-4">
        <input 
            type="text" 
            id="agentSearch" 
            class="form-control" 
            placeholder="{{ __('custom.Search agent by name') }}"
        >
    </div>
</div>
<style>
#agentSearch::placeholder {
    color: red;
    opacity: 1; 
    font-weight: 500;
}
</style>


        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4" id="agentsContainer">
            @foreach($accounts as $account)
                <div class="box col mb-4 agent-item" data-name="{{ strtolower($account->name) }}">
                    <div class="box-agent hover-img wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                        <a href="{{ $account->url }}" class="d-block box-img img-style mb-2">
                            {{ RvMedia::image($account->avatar_url, $account->name) }}
                            {!! Theme::partial('shortcodes.agents.partials.social-links', compact('account')) !!}
                        </a>
                        <div class="content">
                            <div class="info">
                                <a href="{{ $account->url }}">
                                    <h6 class="link">{{ $account->name }}</h6>
                                </a>
                                {!! Theme::partial('shortcodes.agents.partials.info', compact('account')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noResultsMessage" class="text-center mt-4" style="display:none;">
            {{ __('No agents found.') }}
        </div>

        {{ $accounts->links(Theme::getThemeNamespace('partials.pagination')) }}
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('agentSearch');
    if (!searchInput) return;
    const agentsContainer = document.getElementById('agentsContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const paginationWrapper = document.querySelector('.pagination') ? document.querySelector('.pagination').parentElement : null;

    const originalAgentsHTML = agentsContainer ? agentsContainer.innerHTML : '';
    const originalPaginationHTML = paginationWrapper ? paginationWrapper.innerHTML : '';

    let debounceTimer = null;

    function doSearch(query) {
        query = query.trim();

        if (query === '') {
            if (agentsContainer) agentsContainer.innerHTML = originalAgentsHTML;
            if (paginationWrapper) paginationWrapper.innerHTML = originalPaginationHTML;
            if (noResultsMessage) noResultsMessage.style.display = 'none';
            return;
        }

        const searchUrl = `{{ route('public.agents.search') }}?q=${encodeURIComponent(query)}`;
        console.log('Fetching from:', searchUrl);

        fetch(searchUrl)
            .then(function(res) {
                console.log('Response status:', res.status);
                if (!res.ok) throw new Error('Network response was not ok - status: ' + res.status);
                return res.json();
            })
            .then(function(data) {
                console.log('Search results count:', data.count);
                if (agentsContainer) agentsContainer.innerHTML = data.html || '';
                if (data.count === 0) {
                    if (noResultsMessage) noResultsMessage.style.display = 'block';
                } else {
                    if (noResultsMessage) noResultsMessage.style.display = 'none';
                }
                if (paginationWrapper) paginationWrapper.innerHTML = '';
            })
            .catch(function(err) {
                console.error('Agent search failed:', err, err.message);
            });
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();

        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            doSearch(query);
        }, 300);
    });
});
</script>
