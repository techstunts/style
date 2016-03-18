<span class="title">{{$attribute->getDisplayName()}}</span>
<br/>
<input type="text" id="{{$attributeKey}}_from_date" name="{{$attributeKey}}[from_date]" value="" placeholder="From Date" class="report-date-range report-date-range-js " data-attributename="{{$attribute->getDisplayName()}}" data-attributekey="{{$attributeKey}}" >
To
<input type="text" id="{{$attributeKey}}_to_date" name="{{$attributeKey}}[to_date]" value="" placeholder="To Date" class="report-date-range" data-attributename="{{$attribute->getDisplayName()}}" data-attributekey="{{$attributeKey}}" >
