<?php
    use App\Report\Entities\Enums\AttributeType as AttributeType;
    $columnLimit = 12;
?>
<div class="report-title">Report Output</div>
<table class="report-main-table" border="2">
    @foreach ($reportEntity->getAttributes() as $attributeKey => $attribute)
        @if( $attribute->getType() === AttributeType::REF)
            @if( $attribute->getShowInReport() === true)
                @include('report.attribute.ref-attribute-report', ['attribute' => $attribute, 'attributeKey' =>$attributeKey])
            @endif
        @endif
    @endforeach
</table>