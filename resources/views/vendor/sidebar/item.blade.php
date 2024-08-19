<sidebar-item classname="@if($item->getItemClass()){{ $item->getItemClass() }}@endif" routename="{{ $item->getRouteName() }}" icon="{{ $item->getIcon() }}" child_url="{{ json_encode($item->getChildRoutes()) }}" has_child="{{count($items)}}">
    @foreach($appends as $append)
        {!! $append !!}
    @endforeach

    <sidebar-child :to="{name: '{{ $item->getRouteName() }}'}" routename="{{ $item->getRouteName() }}" classname="@if(count($appends) > 0) hasAppend @endif  @if($item->hasItems()) has-arrow @endif waves-effect waves-dark item" @if($item->getNewTab())target="_blank" @endif style="display:block!important" has_child="{{count($items)}}" child_url="{{ json_encode($item->getChildRoutes()) }}">  
        <i class="{{ $item->getIcon() }}"></i>
        <span class="hide-menu">{{ $item->getName() }}</span>

        @foreach($badges as $badge)
            {!! $badge !!}
        @endforeach
    </sidebar-child>

    @if(count($items) > 0)
        <sidebar-ul aria-expanded="false" child_url="{{ json_encode($item->getChildRoutes()) }}" classname="collapse treeview-menu" style="margin-top: -6px;">
            @foreach($items as $item)
                {!! $item !!}
            @endforeach
        </sidebar-ul>
    @endif
</sidebar-item>
