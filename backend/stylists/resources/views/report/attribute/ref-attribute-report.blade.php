<tr class="{{$attributeKey}}-report-row report-row">
    <td class="attr {{$attributeKey}}-title-col" data-attrdisplayname="{{$attribute->getDisplayName()}}" data-attrkey="{{$attributeKey}}}">
        {{$attribute->getDisplayName()}}
    </td>
    <?php
        $filterValuesCount = is_array($attribute->getFilterValues())? count($attribute->getFilterValues()) : 0;
    ?>
    <td class="attr-val">
        <table class="report-attr-val-table" border="1">
            <tr class="report-attr-title {{$attributeKey}}-title-row">
                <?php $attributeCounter = 0; ?>
                @foreach ($attribute->getFilterValues() as $id => $val)
                    <?php $attributeCounter++; ?>
                    <td class="{{$attributeKey}}-{{$id}}
                        @if(($filterValuesCount > $columnLimit) &&  ($attributeCounter > $columnLimit ))
                            hide extra-attr
                       @endif
                            ">
                        {{$val}}
                        @if(($filterValuesCount > $columnLimit) && ($attributeCounter == $columnLimit ))
                            <a class="show-all-attr">Show All</a>
                        @endif
                    </td>
                @endforeach
            </tr>

            <tr class="report-attr-val {{$attributeKey}}-val-row">
                <?php $attributeCounter = 0; ?>
                @foreach ($attribute->getFilterValues() as $id => $val)
                    <?php $attributeCounter++; ?>
                    <td class="{{$attributeKey}}-val-col-{{$id}} {{$attributeKey}}-val-col
                        @if(($filterValuesCount > $columnLimit) && ($attributeCounter > $columnLimit ))
                            hide extra-attr
                         @endif ">
                        &nbsp;
                    </td>
                @endforeach
            </tr>
        </table>
    </td>
</tr>