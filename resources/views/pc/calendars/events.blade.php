<table style="width: 100%">
    <thead>
    <tr>
        <th></th>
        <th><span class="th_country">国家</span></th>
        <th>地点</th>
        <th>发生时间</th>
        <th>重要性</th>
        <th style="width:65%;">事件</th>
    </tr>
    </thead>
    <tbody>
    @if(count($data))
        @foreach($data as $v)
            <tr>
                <td></td>
                <td><span class="country"><img style="width: 21px;height: 14px" src="{{ $v['flag'] }}" alt="{{ $v['country_name'] }}"
                                               title="{{ $v['country_name'] }}">{{ $v['country_name'] }}</span></td>
                <td><span class="address">{{ $v['prom_place'] }}</span></td>
                <td><span class="time">{{ date('Y-m-d',$v['publish_at']) }}</span></td>
                <td>
                <span class="star">
                    @for($i=0;$i<$v['importance'];$i++)
                        <i class="i-red"></i>
                    @endfor
                    @for($i=0;$i<5-$v['importance'];$i++)
                        <i></i>
                    @endfor
                </span>
                </td>
                <td>{{ $v['content'] }}</td>
            </tr>
        @endforeach
        @else <tr><td></td><td>今日无相关数据公布！</td></tr>
    @endif
    </tbody>
</table>
