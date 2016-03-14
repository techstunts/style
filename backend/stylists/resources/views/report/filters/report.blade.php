<div class="report-title">Report Output</div>
<table class="report-main-table" border="2">
    @foreach ($reportEntity->getAttributes() as $attributeKey => $attribute)
    <tr>
        <td class="attr">
            {{$attribute->getDisplayName()}}
        </td>
        <td>
            <table class="report-attr-val-table" border="1">
                <tr class="report-attr-title">
                    <?php $attributeCounter = 0; ?>
                    @foreach ($attribute->getFilterValues() as $id => $val)
                        <?php $attributeCounter++; ?>
                        <td class="{{$attributeKey}}-{{$id}}
                            @if($attributeCounter > 7 )
                                hide extra-attr
                            @endif
                        ">
                            {{$val}}
                            @if($attributeCounter == 7 )
                                <a class="show-all-attr">Show All</a>
                            @endif
                        </td>
                    @endforeach
                </tr>

                <tr class="report-attr-val">
                    <?php $attributeCounter = 0; ?>
                    @foreach ($attribute->getFilterValues() as $id => $val)
                        <?php $attributeCounter++; ?>
                        <td class="{{$attributeKey}}-{{$id}}
                            @if($attributeCounter >= 7 )
                                hide extra-attr
                            @endif
                            ">
                            &nbsp;
                        </td>
                    @endforeach
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
</table>