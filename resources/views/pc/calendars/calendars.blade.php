<table style="width: 100%">
    <thead>
    <tr>
        <th></th>
        <th><span class="th_country">国家</span></th>
        <th>指标名称</th>
        <th>重要性</th>
        <th>前值</th>
        <th>预测值</th>
        <th>公布值</th>
        <th>解读</th>
    </tr>
    </thead>
    <tbody>
    @if(count($data))
        @foreach($data as $v)
            <tr>
                <td>{{ date('H:i',$v['publish_at']) }}</td>
                <td><img style="width: 21px;height: 14px" src="{{ $v['flag'] }}" alt="{{ $v['area_name'] }}"
                         title="{{ $v['area_name'] }}">{{ $v['area_name'] }}</td>
                <td>{{ $v['title'] }}</td>
                <td>
                <span class="star high">
                    @for($i=0;$i<$v['importance'];$i++)
                        <i class="i-red"></i>
                    @endfor
                    @for($i=0;$i<5-$v['importance'];$i++)
                        <i></i>
                    @endfor
                </span>
                </td>
                <td>
                    @if(empty($v['value_before']))待公布
                    @else {{ $v['value_before'] }}
                    @endif
                </td>
                <td>
                    @if(empty($v['value_predict']))待公布
                    @else {{ $v['value_predict'] }}
                    @endif
                </td>
                <td>
                    @if(empty($v['value']))待公布
                    @else {{ $v['value'] }}
                    @endif
                </td>
                <td><a data-toggle="modal" onclick="calendarsDetail({{ $v['area_uni_code'] }},{{ $v['eco_indr_uni_code'] }})" class="show-btn" style="cursor: pointer" data-target=".bs-example-modal-lg">查看</a></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td></td>
            <td>暂无数据</td>
        </tr>
    @endif
    </tbody>
</table>

<script>
    function calendarsDetail(area_code,indr_code){
        var them=this;
        $('#myModal').modal('show');
        $.get('{{ url("pc/calendarsDetail") }}?area='+area_code+'&indr='+indr_code,function(data){
            $('.modal-content').html(data);
        });
        $('#showModal').on('show.bs.modal',function(e){

        });
    };
</script>