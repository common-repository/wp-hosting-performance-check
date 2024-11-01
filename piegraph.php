
<H2>APDEX TTFB ms (time to first byte)</H2>
APDEX is a measure of the user experience. Green is the users are happy, yellow they are tollerating , and orange/red they are frustrated. So you want to see lots of green. If you see lots of orange/red your WordPress website should be considered unusable at that concurrent user level as tested. <br />
I am currently excluding the connect time, so as to avoid artifical geo/test latency, and things like https negotiation.<br />
Any failed responses from the webserver are counted as red.<br />
You can find which URLs are red in the detailed report. <br />

<div style="width: 600px; height: 400px" id="chart-container"></div>


<script>
    new RGraph.SVG.Pie({
    id: 'chart-container',
	    data: [<?php echo $wphpc_piedata;?>],
        options: {
            key: ['<200','<400','<600','<800','<1000','<1200','<1400','<16000','<1800','<2000','2000+'],
            shadow: true,
		textSize: 8,

            colors: [
                'Gradient(#00FF00)',
                'Gradient(#32FF00)',
                'Gradient(#65FF00)',
                'Gradient(#99FF00)',
                'Gradient(#CCFF00)',
                'Gradient(#FFFF00)',
                'Gradient(#FFCC00)',
                    'Gradient(#FF9900)',
                    'Gradient(#FF6600)',
                    'Gradient(#FF3200)',
                    'Gradient(#FF0000)'

            ]
        }
    }).draw();
</script>
