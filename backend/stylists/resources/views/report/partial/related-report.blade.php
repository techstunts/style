@if ($relatedLinks && count($relatedLinks))
    <span class="title"> Related reports: </span>
    <select id="related-report-selector">
        <option value="">Select</option>
        @foreach ($relatedLinks as $relatedLink)
            <option value="{!! url($relatedLink->getLink()) !!}">{{$relatedLink->getDisplayName()}}</option>
        @endforeach
    </select>
@endif
