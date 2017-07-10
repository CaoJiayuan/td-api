<div class="modal-header text-center">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <span>{{ $data['title'] }}</span>
</div>
<div class="modal-body clearfix">
    <div class="text-center" id="echarts" style="width: 100%;height: 300px">

    </div>
    <div class="desc clearfix">
        <div class="col-xs-4">
            <div><label >下次公布:&nbsp;</label><span>{{ date('Y-m-d H:i',$data['next_time']) }}</span></div>
            <div><label >发布频率:&nbsp;</label><span>{{ $data['frequency'] }}</span></div>
        </div>
        <div class="col-xs-4">
            <div><label >公布机构:&nbsp;</label><span>{{ $data['mechanism'] }}</span></div>
            <div><label >数据影响:&nbsp;</label><span>{{ $data['effect'] }}</span></div>
        </div>
        <div class="col-xs-4">
            <div><label >统计方法:&nbsp;</label><span>{{ $data['stst_method'] }}</span></div>
        </div>


    </div>
    <ul style="display: block;">
        <li>
            <label for="">数据释义</label>
            <p>{{ $data['interpretation'] }}</p>
        </li>
        <li>
            <label for="">关注原因</label>
            <p>{{ $data['attention'] }}</p>
        </li>
    </ul>
</div>
<script type="application/javascript">
    var myChart = echarts.init(document.getElementById('echarts'));

    option = {
        tooltip: {
            trigger: 'axis'
        },
        xAxis:  {
            type: 'category',
            boundaryGap: true,
            data: [{{ $data['time'] }}]
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '{value}'
            }
        },
        series: [
            {
                name:'前值',
                type:'line',
                data:[{{ $data['data'] }}],
            }

        ]
    };
    myChart.setOption(option);

</script>