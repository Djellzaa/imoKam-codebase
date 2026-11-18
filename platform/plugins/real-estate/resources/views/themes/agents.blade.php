<h1>{{ __('Agents') }}</h1>
{!! Theme::breadcrumb()->render() !!}

<div class="mb-4">
    <input 
        type="text" 
        id="agentSearch" 
        class="form-control" 
        placeholder="{{ __('custom.Search agent by name') }}"
    >
</div>

<div id="noResultsMessage" class="text-center mt-4" style="display:none;">
    {{ __('No agents found.') }}
</div>

<div id="agentsContainer" class="row">
    @foreach($accounts as $account)
        <div class="box col-lg-3 col-sm-6 agent-item" data-name="{{ strtolower($account->name) }}">
            @include('plugins/real-estate::themes.partials.agents.item', ['account' => $account])
        </div>
    @endforeach
</div>

<br>

{!! $accounts->withQueryString()->links() !!}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('agentSearch');
    const agentsContainer = document.getElementById('agentsContainer');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const paginationWrapper = document.querySelector('.pagination') ? document.querySelector('.pagination').parentElement : null;

    const originalAgentsHTML = agentsContainer.innerHTML;
    const originalPaginationHTML = paginationWrapper ? paginationWrapper.innerHTML : '';

    let debounceTimer = null;

    function doSearch(query) {
        query = query.trim();

        if (query === '') {
            agentsContainer.innerHTML = originalAgentsHTML;
            if (paginationWrapper) paginationWrapper.innerHTML = originalPaginationHTML;
            noResultsMessage.style.display = 'none';
            return;
        }

        const searchUrl = `{{ url('agents/search') }}?q=${encodeURIComponent(query)}`;
        console.log('Fetching from:', searchUrl);

        fetch(searchUrl)
            .then(function(res) {
                console.log('Response status:', res.status);
                if (!res.ok) throw new Error('Network response was not ok - status: ' + res.status);
                return res.json();
            })
            .then(function(data) {
                console.log('Search results count:', data.count);
                agentsContainer.innerHTML = data.html || '';
                if (data.count === 0) {
                    noResultsMessage.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'none';
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