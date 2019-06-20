<?php 

    echo $this->gcharts->ColumnChart('Finances')->outputInto('money_div');
    echo $this->gcharts->div();

    if($this->gcharts->hasErrors())
    {
        echo $this->gcharts->getErrors();
    }
	
	//die;
?>
