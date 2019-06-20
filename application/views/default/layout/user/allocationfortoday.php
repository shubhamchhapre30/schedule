<script type="text/javascript">
	
var chart = AmCharts.makeChart( "chartdivcat", {
  "type": "serial",
  "theme": "light",
  "depth3D": 20,
  "angle": 30,
  "legend": {
    "horizontalGap": 10,
    "useGraphSettings": true,
    "markerSize": 10
  },
  "dataProvider": [ 
  <?php if($timeallocationchart){
  	
	foreach ($timeallocationchart as $key => $value) {
	?>
  			
  {
    "Date": "<?php echo date("d/m",strtotime($key));?>"
    <?php  foreach ($value as $key_est => $value_est) { ?>
    ,"<?php echo ($key_est!='0')?get_category_name($key_est):"No Category";?>": <?php echo $value_est['task_time_estimate'];?>
    	<?php }  ?>
  },
  <?php } }  ?>  
   ],
  "valueAxes": [ {
    "stackType": "regular",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "minimum": 0,
    "maximum": 1200,
    "autoGridCount":false,
    "gridCount": 12,
    "labelFunction": function(value) {
      return Math.round(value/60);
    }
  } ],
  "graphs": [
  
  <?php if($timeallocationchart){
  	foreach ($categories as $key => $value) {
  		
		?>
	    {
	   "balloonFunction": function(item) {
	      return "<span style='font-size:14px'><b>"+item.graph.title+"</b> : <b>" + minutesToTime(item.values.value) + "</b></span>";
	    },
	    "fillAlphas": 0.8,
	    "labelText": "",
	    "lineAlpha": 0.3,
	    "title": "<?php echo ($value!='0')?get_category_name($value):"No Category";?>",
	    "type": "column",
	    "color": "#000000",
	    "valueField": "<?php echo ($value!='0')?get_category_name($value):"No Category";?>"
	  },
	  
  <?php } }  ?>
  ],
  "categoryField": "Date",
  "categoryAxis": {
    "gridPosition": "start",
    "axisAlpha": 0,
    "gridAlpha": 0,
    "position": "left"
  }

} );

function minutesToTime(minutes) {
	
	var hr = Math.floor(minutes / 60);
	var min = minutes - (hr * 60);
	
	if(hr=='0' && min=='0'){
		return '0m';
	} else if(hr!='0' && min =='0'){
		return hr + 'h';
	} else if(hr == '0' && min !='0'){
		return min + 'm';
	} else {
		return hr + 'h'+ min + 'm';
	}
	
}

</script>


<div class="ajax_category_data chartdiv_dashboard" id="chartdivcat" ></div>
<div class="chartdiv_dashboard" id="chartdivcat1" style="display: none" ></div>