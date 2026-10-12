@php($listOptions = config('admin_lists.'.class_basename($listModel), []))
@php($listOptions = array_replace_recursive(config('admin_lists_defaults'), $listOptions))
<form method="GET" action="{{ $listRoute }}" class="admin-list-controls" data-auto-submit>
    <label>
        <span>{{ __('admin_lists.search') }}</span>
        <input type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin_lists.search') }}" data-search-submit>
    </label>

    @foreach(($listOptions['filters'] ?? []) as $filterName => $filter)
        @if(isset($filter['options']))
            <label>
                <span>{{ __('admin_lists.'.$filterName) }}</span>
                <select name="{{ $filterName }}" data-auto-submit-change>
                    <option value="">{{ __('admin_lists.all') }}</option>
                    @foreach($filter['options'] as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}" @selected((string) request($filterName) === (string) $optionValue)>{{ __('admin_lists.values.'.$optionLabel) }}</option>
                    @endforeach
                </select>
            </label>
        @elseif(($filter['options_source'] ?? null) === 'restaurants')
            <label>
                <span>{{ __('admin_lists.restaurant_id') }}</span>
                <select name="{{ $filterName }}" data-auto-submit-change>
                    <option value="">{{ __('admin_lists.all') }}</option>
                    @foreach($filterOptions['restaurants'] ?? [] as $option)
                        <option value="{{ $option->id }}" @selected((string) request($filterName) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
            </label>
        @elseif(($filter['options_source'] ?? null) === 'categories')
            <label>
                <span>{{ __('admin_lists.category_id') }}</span>
                <select name="{{ $filterName }}" data-auto-submit-change>
                    <option value="">{{ __('admin_lists.all') }}</option>
                    @foreach($filterOptions['categories'] ?? [] as $option)
                        <option value="{{ $option->id }}" @selected((string) request($filterName) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
            </label>
        @else
            <label>
                <span>{{ __('admin_lists.'.$filterName) }}</span>
                <input type="{{ ($filter['type'] ?? '') === 'date' ? 'date' : 'text' }}" name="{{ $filterName }}" value="{{ request($filterName) }}" @if(($filter['type'] ?? '') === 'date') data-auto-submit-change @endif>
            </label>
        @endif
    @endforeach

    <label>
        <span>{{ __('admin_lists.sort_by') }}</span>
        <select name="sort" data-auto-submit-change>
            @foreach($listOptions['sort'] ?? [] as $column)
                <option value="{{ $column }}" @selected(request('sort') === $column)>{{ __('admin_lists.columns.'.$column) }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span>{{ __('admin_lists.direction') }}</span>
        <select name="direction" data-auto-submit-change>
            <option value="asc" @selected(request('direction', 'asc') === 'asc')>{{ __('admin_lists.ascending') }}</option>
            <option value="desc" @selected(request('direction') === 'desc')>{{ __('admin_lists.descending') }}</option>
        </select>
    </label>
    <label>
        <span>{{ __('admin_lists.per_page') }}</span>
        <select name="per_page" data-auto-submit-change>
            @foreach($listOptions['per_page_options'] ?? config('admin_lists_defaults.per_page_options', [10, 20, 50, 100]) as $size)
                <option value="{{ $size }}" @selected((int) request('per_page', $listOptions['per_page'] ?? 20) === $size)>{{ $size }}</option>
            @endforeach
        </select>
    </label>
    <button type="submit">{{ __('admin_lists.apply') }}</button>
    <a href="{{ $listRoute }}">{{ __('admin_lists.reset') }}</a>
</form>
<script>
    (() => {
        const form = document.currentScript.previousElementSibling;
        if (!form?.matches('[data-auto-submit]')) return;

        form.querySelectorAll('[data-auto-submit-change]').forEach((control) => {
            control.addEventListener('change', () => form.requestSubmit());
        });

        const search = form.querySelector('[data-search-submit]');
        let searchTimeout;
        search?.addEventListener('input', () => {
            window.clearTimeout(searchTimeout);
            searchTimeout = window.setTimeout(() => form.requestSubmit(), 350);
        });
    })();
</script>
