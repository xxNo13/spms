<div>
    <div id="target"></div>
</div>


@push('target')
    <script>
        var options = {
            chart: {
                type: 'bar',
                height: '250px',
                zoom: {
                    enabled: false,
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Target Finished',
                data: @json($targets)
            }],
            xaxis: {
                categories: @json($days)
            },
        }

        var chart = new ApexCharts(document.querySelector("#target"), options);

        chart.render();
    </script>
@endpush
