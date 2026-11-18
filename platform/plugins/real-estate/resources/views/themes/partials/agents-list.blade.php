@foreach($accounts as $account)
    <div class="box col-lg-3 col-sm-6 agent-item" data-name="{{ strtolower($account->name) }}">
        @include('plugins/real-estate::themes.partials.agents.item', ['account' => $account])
    </div>
@endforeach
