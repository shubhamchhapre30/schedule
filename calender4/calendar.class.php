<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Calendar version 1.0.2 (28.08.2009)                                  #
##  Developed by:  ApPhp <info@apphp.com>                                      #
##  License:       GNU GPL v.2                                                 #
##  Site:          http://www.apphp.com/php-calendar/                          #
##  Copyright:     ApPHP Calendar (c) 2009. All rights reserved.               #
##  Last changes:  14.11.2013                                                  #
##                                                                             #
################################################################################

class Calendar2{
	
	
	// PUBLIC
	// --------
	// __construct()
	// __destruct()
	// Show()
	// SetCalendarDimensions
	// SetCaption
	// SetWeekStartedDay
	// SetWeekDayNameLength
	// 
	// STATIC
	// ----------
	// Version
	// 
	// PRIVATE
	// --------
	// SetDefaultParameters
	// GetCurrentParameters
	// DrawJsFunctions
	// DrawYear
	// DrawMonth	
	// DrawMonthSmall
	// DrawWeek
	// DrawDay
	// DrawTypesChanger
	// DrawDateJumper
	// DrawTodayJumper
	// --------
	// isYear
	// isMonth
	// isDay
	// ConvertToDecimal

	//--- PUBLIC DATA MEMBERS --------------------------------------------------
	public $error;
	
	//--- PROTECTED DATA MEMBERS -----------------------------------------------
	protected $weekDayNameLength;
	
	//--- PRIVATE DATA MEMBERS -------------------------------------------------
	private  $arrWeekDays;
	private  $arrMonths;
	private  $arrViewTypes;
	private  $defaultView;
	private  $defaultAction;
	
	private  $arrParameters;
	private  $arrToday;
	private  $prevYear;
	private  $nextYear;
	private  $prevMonth;
	private  $nextMonth;
	private  $prevWeek;
	private  $nextWeek;
	private  $prevDay;
	private  $nextDay;
	
	private  $isDrawNavigation;
	
	private  $crLt;	
	private  $caption;		
	private  $calWidth;		
	private  $calHeight;
	private  $cellHeight;

	static private $version = "1.0.2";
	
		
	//--------------------------------------------------------------------------
    // CLASS CONSTRUCTOR
	//--------------------------------------------------------------------------
	function __construct()
	{
		$this->defaultView   = "monthly";
		$this->defaultAction = "view";
		
		// possible values 1,2,....7
		$this->weekStartedDay = get_default_day_no_of_company();
		
		$this->weekDayNameLength = "short"; // short|long
		
		$this->arrWeekDays = array();
		$this->arrWeekDays[0] = array("short"=>"Sun", "long"=>"Sunday");
		$this->arrWeekDays[1] = array("short"=>"Mon", "long"=>"Monday");
		$this->arrWeekDays[2] = array("short"=>"Tue", "long"=>"Tuesday");
		$this->arrWeekDays[3] = array("short"=>"Wed", "long"=>"Wednesday");
		$this->arrWeekDays[4] = array("short"=>"Thu", "long"=>"Thursday");
		$this->arrWeekDays[5] = array("short"=>"Fri", "long"=>"Friday");
		$this->arrWeekDays[6] = array("short"=>"Sat", "long"=>"Saturday");
		
		$this->arrMonths = array();
		$this->arrMonths["1"] = "January";
		$this->arrMonths["2"] = "February";
		$this->arrMonths["3"] = "March";
		$this->arrMonths["4"] = "April";
		$this->arrMonths["5"] = "May";
		$this->arrMonths["6"] = "June";
		$this->arrMonths["7"] = "July";
		$this->arrMonths["8"] = "August";
		$this->arrMonths["9"] = "September";
		$this->arrMonths["10"] = "October";
		$this->arrMonths["11"] = "November";
		$this->arrMonths["12"] = "December";
		
		$this->arrViewTypes = array();
		$this->arrViewTypes["daily"]   = "Daily";
		$this->arrViewTypes["weekly"]  = "Weekly";
		$this->arrViewTypes["monthly"] = "Monthly";
		$this->arrViewTypes["yearly"]  = "Yearly";
		
		$this->arrParameters = array();
		$this->SetDefaultParameters();

		$this->arrToday  = array();
		$this->prevYear  = array();
		$this->nextYear  = array();
		$this->prevMonth = array();
		$this->nextMonth = array();
		$this->prevWeek  = array();
		$this->nextWeek  = array();
		$this->prevDay   = array();
		$this->nextDay   = array();
		
		$this->isDrawNavigation = true;
		
		$this->crLt = "\n";
		$this->caption = "";
		$this->calWidth = "800px";
		$this->calHeight = "470px";
		$this->celHeight = number_format(((int)$this->calHeight)/6, "0")."px";
	}
	
	//--------------------------------------------------------------------------
    // CLASS DESTRUCTOR
	//--------------------------------------------------------------------------
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }

	
	//==========================================================================
    // PUBLIC DATA FUNCTIONS
	//==========================================================================			
	/**
	 *	Show Calendar 
	 *
	*/	
	function Show($calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$capacity,$date_formate,$company_flags,$completed_id)
	{
		$this->GetCurrentParameters();
		$this->DrawJsFunctions();
		//print_r($this);die;
		echo "<div id='calendar' style='margin-right:5px;'>".$this->crLt;		
		
		// draw calendar header
		//echo "<table id='calendar_header'>".$this->crLt;
		//echo "<tr>";
		//echo "<th class='caption_left'>".$this->DrawTodayJumper(false)."</th>";
		//echo "<th class='caption'>".$this->caption."</th>";
		//echo "<th class='types_changer'>".$this->DrawTypesChanger(false)."</th>";
	//	echo "</tr>".$this->crLt;
		//echo "</table>";

		
		switch($this->arrParameters["view_type"])
		{			
			case "daily":
				$this->DrawDay();
				break;
			case "weekly":
				$this->DrawWeek();
				break;
			case "yearly":
				$this->DrawYear();
				break;			
			default:
			case "monthly":				
				$this->DrawMonth($calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$capacity,$date_formate,$company_flags,$completed_id);
				
				break;
		}
		
		
		
	}
	
	
	/**
	 *	Set calendar dimensions
	 *  	@param $width
	 *  	@param $height
	*/	
	function SetCalendarDimensions($width = "", $height = "")
	{
		$this->calWidth = ($width != "") ? $width : "1170px";
		$this->calHeight = ($height != "") ? $height : "470px";
		$this->celHeight = number_format(((int)$this->calHeight)/6, "0")."px";
	}

	/**
	 *	Check if parameters is 4-digit year
	 *  	@param $year - string to be checked if it's 4-digit year
	*/	
	function SetCaption($caption_text = "")
	{
		$this->caption = $caption_text;	
	}
	
	/**
	 *	Set week started day
	 *  	@param $started_day - started day of week 1...7
	*/	
	function SetWeekStartedDay($started_day = "1")
	{
		if(is_numeric($started_day) && (int)$started_day >= 1 && (int)$started_day <= 7){
			$this->setWeekStartedDay = (int)$started_day;				
		}
	}

	/**
	 *	Set week day name length 
	 *  	@param $length_name - "short"|"long"
	*/	
	function SetWeekDayNameLength($length_name = "short")
	{
		if(strtolower($length_name) == "long"){
			$this->weekDayNameLength = "long";
		}
	}
	
	//==========================================================================
    // STATIC
	//==========================================================================		
	/**
	 *	Return current version
	*/	
	static function Version()
	{
		return self::$version;
	}
	
	
	
	//==========================================================================
    // PRIVATE DATA FUNCTIONS
	//==========================================================================		
	/**
	 *	Set default parameters
	 *
	*/	
	function SetDefaultParameters()
	{
		$this->arrParameters["year"]  = @date("Y");
		$this->arrParameters["month"] = @date("m");
		$this->arrParameters["month_full_name"] = @date("F");
		$this->arrParameters["day"]   = @date("d");
		$this->arrParameters["view_type"] = $this->defaultView;
		$this->arrParameters["action"] = "display";
		$this->arrToday = @getdate();

		// get current file
		$this->arrParameters["current_file"] = $_SERVER["SCRIPT_NAME"];
		$parts = explode('/', $this->arrParameters["current_file"]);
		$this->arrParameters["current_file"] = $parts[count($parts) - 1];		
	}

	/**
	 *	Get current parameters - read them from URL
	 *
	*/	
	function GetCurrentParameters()
	{	
		$year 		= (isset($_REQUEST['year']) && $this->isYear($_REQUEST['year'])) ? $this->remove_bad_chars($_REQUEST['year']) : @date("Y");
		$month 		= (isset($_REQUEST['month']) && $this->isMonth($_REQUEST['month'])) ? $this->remove_bad_chars($_REQUEST['month']) : @date("m");
		$day 		= (isset($_REQUEST['day']) && $this->isDay($_REQUEST['day'])) ? $this->remove_bad_chars($_REQUEST['day']) : @date("d");
        $view_type 	= (isset($_REQUEST['view_type'])) ? $_REQUEST['view_type'] : '';
		$view_type 	= (array_key_exists($view_type, $this->arrViewTypes)) ? $this->remove_bad_chars($view_type) : "monthly";
	
		$cur_date = @getdate(mktime(0,0,0,$month,$day,$year));
		
		///echo "<br>3--";
		///print_r($cur_date);
		
		$this->arrParameters["year"]  = $cur_date['year'];
		$this->arrParameters["month"] = $this->ConvertToDecimal($cur_date['mon']);
		$this->arrParameters["month_full_name"] = $cur_date['month'];
		$this->arrParameters["day"]   = $day;
		$this->arrParameters["view_type"] = $view_type;
		$this->arrParameters["action"] = "display";
		$this->arrToday = @getdate();

		$this->prevYear = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"],$this->arrParameters['year']-1));
		$this->nextYear = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"],$this->arrParameters['year']+1));

		$this->prevMonth = @getdate(mktime(0,0,0,$this->arrParameters['month']-1,$this->arrParameters["day"],$this->arrParameters['year']));
		$this->nextMonth = @getdate(mktime(0,0,0,$this->arrParameters['month']+1,$this->GetDayForMonth($this->arrParameters['month']+1,$this->arrParameters['day']),$this->arrParameters['year']));

		$this->prevWeek = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"]-7,$this->arrParameters['year']));
		$this->nextWeek = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"]+7,$this->arrParameters['year']));

		$this->prevDay = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"]-1,$this->arrParameters['year']));
		$this->nextDay = @getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters["day"]+1,$this->arrParameters['year']));
	}

	/**
	 *	Draw javascript functions
	 *
	*/	
	private function DrawJsFunctions()
	{
		/*echo "<script type='text/javascript'>";
		echo "
			function JumpToDate(){
				var jump_day   = (document.getElementById('jump_day')) ? document.getElementById('jump_day').value : '';
				var jump_month = (document.getElementById('jump_month')) ? document.getElementById('jump_month').value : '';
				var jump_year  = (document.getElementById('jump_year')) ? document.getElementById('jump_year').value : '';
				var view_type  = (document.getElementById('view_type')) ? document.getElementById('view_type').value : '';
				
				__doPostBack('view', view_type, jump_year, jump_month, jump_day);
			}
		
			function __doPostBack(action, view_type, year, month, day)
			{			
				var action    = (action != null) ? action : 'view';
				var view_type = (view_type != null) ? view_type : 'monthly';
				var year      = (year != null) ? year : '".$this->arrToday["year"]."';
				var month     = (month != null) ? month : '".$this->ConvertToDecimal($this->arrToday["mon"])."';
				var day       = (day != null) ? day : '".$this->arrToday["mday"]."';
			
				window.location.href = '".$this->arrParameters["current_file"]."?action='+action+'&view_type='+view_type+'&year='+year+'&month='+month+'&day='+day;		
			}
		";
		echo "</script>";*/
		
	}

	/**
	 *	Draw yearly calendar
	 *
	*/	
	private function DrawYear()
	{
		
		$this->celHeight = "20px";
		echo "<table class='year_container'>".$this->crLt;
		echo "<tr>".$this->crLt;
			echo "<th colspan='3'>";
				echo "<table class='table_navbar'>".$this->crLt;
				echo "<tr>";
				echo "<th class='tr_navbar_left' valign='middle'>
					  ".$this->DrawDateJumper(false, false, false)."
					  </th>".$this->crLt;
				echo "<th class='tr_navbar'></th>".$this->crLt;
				echo "<th class='tr_navbar_right'>				
					 
					  </th>".$this->crLt;
				echo "</tr>".$this->crLt;
				echo "</table>".$this->crLt;
			echo "</td>".$this->crLt;
		echo "</tr>".$this->crLt;

		echo "<tr>";
		for($i = 1; $i <= 12; $i++){
			echo "<td align='center' valign='top'>";
			echo "<a href=\"javascript:__doPostBack('view', 'monthly', '".$this->arrParameters['year']."', '".$this->ConvertToDecimal($i)."', '".$this->arrParameters['day']."')\"><b>".$this->arrMonths["$i"]."</b></a>";
			$this->DrawMonthSmall($this->arrParameters['year'], $this->ConvertToDecimal($i));
			echo "</td>";
			if(($i != 1) && ($i % 3 == 0)) echo "</tr><tr>";
		}
		echo "</tr>";
		echo "<tr><td nowrap height='5px'></td></tr>";
		echo "</table>";
	}

	/**
	 *	Draw monthly calendar
	 *
	*/	
	private function DrawMonth($calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,$capacity,$date_formate,$company_flags,$completed_id)
	{
		///echo $cal_user_color_id."++++++";
		$actday = 0;
	
		$year = $this->arrParameters['year'];
		//print_r($this->arrParameters);die;
		//$calender_tasks2 = get_calender_task_list($this->arrParameters['month'],$this->arrParameters['year'],$calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date);
		//pr($this);
		// today, first day and last day in month
		$firstDay = @getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$lastDay  = @getdate(mktime(0,0,0,$this->arrParameters['month']+1,0,$this->arrParameters['year']));
		if($calender_team_user_id == '#'){
                    $color_codes = get_user_color_codes(get_authenticateUserID());
                }else{
                    $color_codes = get_user_color_codes($calender_team_user_id);
                }
                $company_id = get_company_id();
                $task_status = get_taskStatus($company_id,'Active'); 
                
//                if($this->session->userdata('Temp_calendar_user_id')=='0'){
//                    $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
//                }else{
//                   $user_swimlanes = get_user_swimlanes($this->session->userdata('Temp_calendar_user_id')); 
//                }
               $user_swimlanes = get_user_swimlanes(get_authenticateUserID());
		//company off days array
		$off_days_arr = array();
		$off_days = get_company_offdays();
		if($off_days!=''){
			$off_days_arr = explode(',', $off_days);
		}
		//pr($off_days_arr);
		// Create a table with the necessary header informations
                echo "<table class='month'>".$this->crLt;
                echo "<tr>";
			echo "<th colspan='7'>";
				echo "<table class='table_navbar'>".$this->crLt;
				echo "<tr>";
				// echo "<th class='tr_navbar_left'>
					  // ".$this->DrawDateJumper(false)."	
					  // </th>".$this->crLt;
					 
				echo "<th class='tr_navbar' style='text-align:center;'>";
				echo "<a href=\"javascript:__doPostBack('view','monthly','".$this->prevMonth['year']."','".$this->ConvertToDecimal($this->prevMonth['mon'])."','".$this->ConvertToDecimal($this->prevMonth['mday'])."')\" title='Previous' class='calprev'><i class='calenderstrip calprev'> </i></a>";
				echo '<div class="calendar-filter" style="margin-right: 45%;margin-top: 7px;">
                                                            <ul class="list-unstyled">
                                                                <li  class="datetimepicker_month_view" onclick="cal_fill()"><span class="cstm_week_view_sp pull-left" style="margin-right: 5px;">';
                                                               echo " ".$this->arrParameters['month_full_name']." - ".$year." ";        
                                echo '</span>
                                            <i class="fa fa-calendar-o" style="margin-right: 5px;"></i><i class="fa fa-sort-desc"></i></li>
                                           </ul>
                                      </div>';
                                
				echo "<a href=\"javascript:__doPostBack('view','monthly','".$this->nextMonth['year']."','".$this->ConvertToDecimal($this->nextMonth['mon'])."','".$this->ConvertToDecimal($this->nextMonth['mday'])."')\" title='Next' class='calnext'><i class='calenderstrip calnext'> </i></a>";
				
				echo '<div class="calendar-filter">
									<ul class="list-unstyled">
                                                                                <li> <a class="tooltips " data-placement="bottom" data-original-title="Weekly View" onclick="save_last_calender_view(1);" href="'.site_url('calendar/weekView').'"> <i class="stripicon weekicon"> </i> </a> </li>
										<li> <a class="tooltips " data-placement="bottom" data-original-title="Next-Five Day View" onclick="save_last_calender_view(2);" href="'.site_url('calendar/NextFiveDayView').'"> <i class="stripicon dayicon"> </i> </a> </li>
										<li class="active"> <a class="tooltips "  data-placement="bottom" data-original-title="Monthly View" onclick="save_last_calender_view(3);" href="'.site_url('calendar/myCalendar').'"> <i class="stripicon monthicon"> </i> </a> </li>
									</ul>
								</div>';
				
				echo "</th>".$this->crLt;
				//echo "<th class='tr_navbar_right'>				
				//	  <a href=\"javascript:__doPostBack('view', 'monthly', '".$this->prevYear['year']."', '".$this->arrParameters['month']."', '".$this->arrParameters['day']."')\">".$this->prevYear['year']."</a> |
					//  <a href=\"javascript:__doPostBack('view', 'monthly', '".$this->nextYear['year']."', '".$this->arrParameters['month']."', '".$this->arrParameters['day']."')\">".$this->nextYear['year']."</a>
					 // </th>".$this->crLt;
				echo "</tr>".$this->crLt;
				echo "</table>".$this->crLt;
			echo "</td>".$this->crLt;
		echo "</tr>".$this->crLt;
		echo "<tr class='tr_days'>";
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				echo "<td class='monthly_calendar-header'>".$this->arrWeekDays[($i % 7)][$this->weekDayNameLength]."</td>";		
			}
		echo "</tr>".$this->crLt;
                echo "</table>".$this->crLt;
                        
		echo "<div class='monthly-calendar_css'><table class='month'>".$this->crLt;
		
		//pr($this);
		// Display the first calendar row with correct positioning
		//echo $this->weekStartedDay;
		
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);
		if($max_empty_days<0){
			$max_empty_days = 7 + $max_empty_days;
		}
		
		$last_empty_days = ($this->weekStartedDay - $lastDay['wday']) - 2;
		if($last_empty_days<0){
			$last_empty_days = 7 + $last_empty_days;
		}
		
		$allow_past_task = '1';
		if($company_flags){
			$allow_past_task = $company_flags['allow_past_task'];
		}
		
		$start_date = date("Y-m-d",strtotime("-".$max_empty_days." days", $firstDay[0]));
		$end_date = date("Y-m-d",strtotime("+".$last_empty_days." days",$lastDay[0]));
		//$last_fifth_day = date("Y-m-d",strtotime("-5 days",$lastDay[0]));
		//echo $last_fifth_day;
		//pr($this);
		//echo $start_date."=======".$end_date;
		$month_start_date = $start_date;
		$month_end_date = $end_date;
		
		if($max_empty_days>0){
			$last_day_of_prev_moth = date("w",strtotime(date("Y-m-t",strtotime($start_date))));
			//echo $last_day_of_prev_moth;
			if($last_day_of_prev_moth>0){
				$second_last_day_of_prev_month = $last_day_of_prev_moth-1;
				if($second_last_day_of_prev_month>0){
					$third_last_day_of_prev_month = $second_last_day_of_prev_month-1;
					if($third_last_day_of_prev_month>0){
						$fourth_last_day_of_prev_month = $third_last_day_of_prev_month-1;
						if($fourth_last_day_of_prev_month>0){
							$fifth_last_day_of_prev_month = $fourth_last_day_of_prev_month-1;
						} else {
							$fifth_last_day_of_prev_month = 6;
						}
					} else {
						$fourth_last_day_of_prev_month = 6;
						$fifth_last_day_of_prev_month = $fourth_last_day_of_prev_month-1;
					}
				} else {
					$third_last_day_of_prev_month = 6;
					$fourth_last_day_of_prev_month = $third_last_day_of_prev_month-1;
					$fifth_last_day_of_prev_month = $fourth_last_day_of_prev_month-1;
				}
			} else {
				$second_last_day_of_prev_month = 6;
				$third_last_day_of_prev_month = $second_last_day_of_prev_month-1;
				$fourth_last_day_of_prev_month = $third_last_day_of_prev_month-1;
				$fifth_last_day_of_prev_month = $fourth_last_day_of_prev_month-1;
				
			}
			
			if(count($off_days_arr)>2){
				if(in_array($fifth_last_day_of_prev_month, $off_days_arr) && $max_empty_days>5){
					$d = "-".($max_empty_days -5) ;
				} else if(in_array($fourth_last_day_of_prev_month, $off_days_arr) && $max_empty_days>4){
					$d = "-".($max_empty_days -4) ;
				} else if(in_array($third_last_day_of_prev_month, $off_days_arr) && $max_empty_days>3){
					$d = "-".($max_empty_days -3) ;
				} else if(in_array($second_last_day_of_prev_month, $off_days_arr) && $max_empty_days>2){
					$d = "-".($max_empty_days -2) ;
				} elseif(in_array($last_day_of_prev_moth, $off_days_arr) && $max_empty_days>1){
					$d = "-".($max_empty_days -1) ;
				} else {
					$d = "-".$max_empty_days;
				}
			} else {
				if($max_empty_days>5){
					if(in_array($last_day_of_prev_moth, $off_days_arr) && $max_empty_days>1){
						$d = "-".($max_empty_days -1);
					} else {
						$l = month_total_working_day($this->prevMonth['mon'],$this->prevMonth['year'],'',$off_days)-5;
						$d = "-".$max_empty_days;
					}
					// if(in_array($last_day_of_prev_moth, $off_days_arr)){
						// $d = "-".($max_empty_days -1) ;
					// } else {
						// $d = "-".$max_empty_days;
					// }
				} else {
					if(in_array($second_last_day_of_prev_month, $off_days_arr) && $max_empty_days>2){
						$d = "-".($max_empty_days -2) ;
					} elseif(in_array($last_day_of_prev_moth, $off_days_arr) && $max_empty_days>1){
						$d = "-".($max_empty_days -1) ;
					} else {
						$d = "-".$max_empty_days;
					}
				}
			}
		}

		//echo $user_color_id."********";
		$calender_tasks = get_calender_weekly_tasks($start_date,$end_date,$calender_project_id,$left_task_status_id,$calender_team_user_id,$calender_date,$cal_user_color_id,'',$completed_id);
		$all_reorting_user=get_list_user_report_to_adminstartor();
//echo date_default_timezone_get();
		//pr($calender_tasks);
		$wd = '0';
		$a = '0';
		$b = '0';
		$c = '0';
		
		$remain_day = month_total_working_day($firstDay['mon'],$firstDay['year'],'',$off_days)-4;
		$event_str = '';
		$task_list_str = '';
		
               
                if($calender_team_user_id=='#' || $calender_team_user_id == '0' )
                 {   
                    $Mon_capacity=0;
                    $Tue_capacity=0;
                    $Wed_capacity=0;
                    $Thu_capacity=0;
                    $Fri_capacity=0;
                    $Sat_capacity=0;
                    $Sun_capacity=0;
                    $users_list = get_user_under_project($calender_project_id);
                    $team_capacity = array();
                    if(!empty($users_list)){
                        foreach($users_list as $data_id){
                            if(!empty($data_id)){
                               $team_capacity[]= getUserCapacity($data_id->user_id);
                            }
                        }
                    }
                                                                                                 
                    //$team_capacity[] = getUserCapacity(get_authenticateUserID());
                    for($i=0;$i<count($team_capacity); $i++){
                    $Mon_capacity += $team_capacity[$i]['MON_hours'] ;
                    $Tue_capacity += $team_capacity[$i]['TUE_hours'] ;
                    $Wed_capacity += $team_capacity[$i]['WED_hours'] ;
                    $Thu_capacity += $team_capacity[$i]['THU_hours'] ;
                    $Fri_capacity += $team_capacity[$i]['FRI_hours'] ;
                    $Sat_capacity += $team_capacity[$i]['SAT_hours'] ;
                    $Sun_capacity += $team_capacity[$i]['SUN_hours'] ;
                    }
                    }else{
//                        if($this->session->userdata('Temp_calendar_user_id')=='0'){
//                        $capacity = getUserCapacity(get_authenticateUserID());
//                        }else{
//                        $capacity = getUserCapacity($this->session->userdata('Temp_calendar_user_id')); 
//                        }
                        if($capacity){
                            $Mon_capacity = $capacity['MON_hours'];
                            $Tue_capacity = $capacity['TUE_hours'];
                            $Wed_capacity = $capacity['WED_hours'];
                            $Thu_capacity = $capacity['THU_hours'];
                            $Fri_capacity = $capacity['FRI_hours'];
                            $Sat_capacity = $capacity['SAT_hours'];
                            $Sun_capacity = $capacity['SUN_hours'];
                        }
                    }
                while(strtotime($start_date)<=strtotime($end_date)){
			echo "<tr class='tr' style='height:".$this->celHeight.";'>".$this->crLt;
			for($i = 1; $i <= 7; $i++){
				$a++;
				
				if($max_empty_days>0 && $a<=$max_empty_days){
					if(isset($l) && $l>0){
						$wd = $l;
						$l = 0;
					} else {
						$wd = $d;
					}
					$d++;
					$class = " class='td_empty'";
				} else if(($a-$max_empty_days) > $lastDay['mday']){
					$b++;
					$wd = $b;
					$class = " class='td_empty'";
				} else {
					if($wd==$remain_day){
						$c = "-5";
						$wd = $c;
					} else {
						$c++;
						$wd = $c;
					}
					$class = " class='td'";
				}
				
				$date = date('Y-m-d',strtotime($start_date));
				$day = date('D',strtotime($start_date));
				$actday = date('j',strtotime($start_date));
				$event_str = '';
				$task_list_str = '';
				$allocated = 0; 
				$due = 0;
				$overdue = 0;
				$completed = 0;
				$schedulled = 0;
				$labalclass = '';
				
				if($allow_past_task == "0" && strtotime(date('Y-m-d'))>strtotime(str_replace(array("/"," ",","), "-", $date))){
					$href = '<a href="javascript:void(0);" style="opacity:0.5; display:none;"> <i class="calenderstrip caladdicon"> </i> </a>';
				} else {
					$href = '<a href="javascript:void(0);"  onclick="add_task(\''.strtotime($date).'\',\''.date($date_formate,strtotime($date)).'\')" > <i class="calenderstrip caladdicon"> </i> </a>';
				}
			//	$sort_class = 'sortable';
				if(in_array((date('w',strtotime(str_replace(array("/"," ",","), "-", $date)))),$off_days_arr) ){
					$tdstyle = 'style="background-color:#CED2D8;"';
					$sort_class = 'unsorttd';
					$wd_str = "";
					if(isset($d))$d--;
					if(isset($b) && $b>0)$b--;
					if($c!="0")$c--;
				} else {
					$tdstyle = '';
					$sort_class =  'sortable';
					$wd_str = "WD ".$wd;
					$wd++;
				}
				if($day == "Mon"){$capacity = $Mon_capacity;} else if($day == "Tue"){$capacity = $Tue_capacity;} else if($day == "Wed"){$capacity = $Wed_capacity;} else if($day == "Thu"){$capacity=$Thu_capacity;} else if($day=='Fri'){$capacity=$Fri_capacity;} else if($day=="Sat"){$capacity=$Sat_capacity;}else {$capacity=$Sun_capacity;} 
				if($capacity == '0'){
					$tdstyle = 'style="background-color:#CED2D8;"';
				}
				
//				if(isset($calender_tasks[$start_date]) && !(empty($calender_tasks[$start_date])))
//				{
                                 if(isset($calender_tasks[$start_date] )&& !empty($calender_tasks[$start_date])){
					for($j=0;$j<count($calender_tasks[$start_date]);$j++)
					{
						$is_master_deleted = $calender_tasks[$start_date][$j]['tm'];
						
						
						
						$project_name = '';
						$move_class = '';
						$full_title = $calender_tasks[$start_date][$j]['task_title'];
						$title = $calender_tasks[$start_date][$j]['task_title'];
						if($calender_tasks[$start_date][$j]['task_project_id']){
							$project_name = $calender_tasks[$start_date][$j]['project_title'];
							$title = $project_name.' - '.$full_title;
							$full_title = $project_name.' - '.$full_title;
						}
						if(strpos($calender_tasks[$start_date][$j]['task_id'],'child') !== false) {
						    $chk = "0";
						} else {
							$chk = "1";
						}
						
						if($chk == "1"){
							$dependencies = $calender_tasks[$start_date][$j]['tpp'];
							if($calender_tasks[$start_date][$j]['tpp']!='0' && $calender_tasks[$start_date][$j]['completed_depencencies']=="0"){
								$completed_depencencies = "green";
							} else if($calender_tasks[$start_date][$j]['tpp']=='0' && $calender_tasks[$start_date][$j]['completed_depencencies']=="0"){
								$completed_depencencies = "green";
							} else {
								$completed_depencencies = "red";
							}
						} else {
							$dependencies = '';
							$completed_depencencies = "";
						}
						
						$task_type = "0";
						if($calender_tasks[$start_date][$j]['task_status_id']==$completed_id){
							$completed += 1; // total completed task
							$task_type = "1";
							$schedulled += 1;
							if($task_type == "1"){
								$task_type = "1,3";
							} else {
								$task_type = "3";
							}
							if(strtotime($calender_tasks[$start_date][$j]['task_due_date']) == strtotime($date)){
								$due += 1;
								if($task_type == "1,3"){
									$task_type = "1,3,4";
								} else {
									$task_type = "3,4";
								}
							}
						} else if($calender_tasks[$start_date][$j]['task_due_date'] < date('Y-m-d')){
							$overdue += 1;
							$task_type = "2";
							$schedulled += 1;
							if($task_type == "2"){
								$task_type = "2,3";
							} else {
								$task_type = "3";
							}
						} else {
							$schedulled += 1;
							$task_type = "3";
							
							if(strtotime($calender_tasks[$start_date][$j]['task_due_date']) == strtotime($date)){
								$due += 1;
								if($task_type == "3"){
									$task_type = "3,4";
								} else {
									$task_type = "4";
								}
							}
						}
						
						$task_type_class = '';
						if($task_type){
							$task_type_val = explode(",", $task_type);
							$task_type_class = '';
							for($x=0;$x<count($task_type_val);$x++){
								$task_type_class .= "task_type_".$task_type_val[$x]." ";
							}
						}
						
						if($calender_tasks[$start_date][$j]['color_code']){
							$color_code = $calender_tasks[$start_date][$j]['color_code'];
						} else {
							$color_code = '#fff';
						}
						
						//$color = $calender_tasks[$start_date][$j]['color_id'];
						if($calender_tasks[$start_date][$j]['outside_color_code']){
							$outside_code = $calender_tasks[$start_date][$j]['outside_color_code'];
						} else {
							$outside_code = '#e5e9ec';
						}
						
                                                if($calender_tasks['color_menu']=='false'){
                                                    $color_code = '#fff';
                                                    $outside_code = '#e5e9ec';
                                                }
						if($completed_depencencies === 'red'){
							$move_class = 'unsorttd';
						}
						$cl = '';
						if($calender_tasks[$start_date][$j]['task_time_estimate'] == '0'){
							$cl = 'display:none;';
						}
						
						$cl3 = "";
						if($calender_tasks[$start_date][$j]['locked_due_date'] == "0"){
							$cl3 = 'display:none;';
						}
						
						if($cl == "" && $cl3 == ""){
							if(strlen($title) > 18) {
							    $title = substr($title, 0, 16).'..'; 
							}
						} else if($cl!="" && $cl3 == ""){
							if(strlen($title) > 24) {
							    $title = substr($title, 0, 22).'..'; 
							}
						} else if($cl=="" && $cl3!=""){
							if(strlen($title) > 18) {
							    $title = substr($title, 0, 16).'..'; 
							}
						} else {
							if(strlen($title) > 26) {
							    $title = substr($title, 0, 24).'..'; 
							}
						}
                                                                                                                 $report_user_list_id='';
                                                                                                                  if(isset($all_reorting_user) && !empty($all_reorting_user)){
                                                                                                                            foreach($all_reorting_user as $val ){
                                                                                                                                if($val['user_id']==$calender_tasks[$start_date][$j]['task_owner_id']){
                                                                                                                                   $report_user_list_id='1';  
                                                                                                                                }
                                                                                                                            }
                                                                                                                          }else{
                                                                                                                             $report_user_list_id='0';
                                                                                                                          }
                                                                                                                          
						 $jsonarray=array(
                                                                "task_status" =>$task_status,
                                                                "user_colors" =>$color_codes,
                                                                "user_swimlanes" =>$user_swimlanes,
                                                                "task_id" =>$calender_tasks[$start_date][$j]['task_id'],
                                                                "locked_due_date" => $calender_tasks[$start_date][$j]['locked_due_date'],
                                                                "task_due_date" =>date("m-d-Y",strtotime($calender_tasks[$start_date][$j]['task_due_date'])),
                                                                "task_scheduled_date" =>date("m-d-Y",strtotime($date)),
                                                                "date" =>strtotime($date), 
                                                                "active_menu" =>'from_calendar',
                                                                "start_date" =>strtotime($month_start_date),
                                                                "end_date" =>strtotime($month_end_date),
                                                                "master_task_id" =>$calender_tasks[$start_date][$j]['master_task_id'],
                                                                "is_master_deleted" =>$is_master_deleted,
                                                                "chk_watch_list" =>$calender_tasks[$start_date][$j]['watch'],
                                                                "task_owner_id" =>$calender_tasks[$start_date][$j]['task_owner_id'],
                                                                "completed_depencencies" =>$completed_depencencies,
                                                                "color_menu" =>$calender_tasks['color_menu'],
                                                                "swimlane_id" =>$calender_tasks[$start_date][$j]['swimlane_id'],
                                                                "task_status_id" => $calender_tasks[$start_date][$j]['task_status_id'],
                                                                "before_status_id" => '',
                                                                  "report_user_list_id" => $report_user_list_id 
                                                            );
						if($calender_tasks[$start_date][$j]['is_personal'] == '1' && $calender_tasks[$start_date][$j]['task_owner_id'] != get_authenticateUserID()){
							$task_list_str .= '<div class="taskbox calicon'.$calender_tasks[$start_date][$j]['task_priority'].' unsorttd '.$task_type_class.'" style="background-color:'.$color_code.'; border:1px solid '.$outside_code.';">
							                  <div oncontextmenu="context_menu(\''.$calender_tasks[$start_date][$j]['task_id'].'\',\''.$calender_tasks[$start_date][$j]['locked_due_date'].'\',\''.date("Y-m-d",strtotime($calender_tasks[$start_date][$j]['task_due_date'])).'\',\''.strtotime($date).'\',\''.strtotime($month_start_date).'\',\''.strtotime($month_end_date).'\',\''.$calender_tasks[$start_date][$j]['master_task_id'].'\',\''.$is_master_deleted.'\',\''.$calender_tasks[$start_date][$j]['watch'].'\',\''.$calender_tasks[$start_date][$j]['task_owner_id'].'\',\''.$completed_depencencies.'\',\''.$calender_tasks['color_menu'].'\');">
										<span class="task-desc">  Busy </span><p class="task-hrs"></p>
										<div class="clearfix"> </div></div></div>';
						} else {
							$task_list_str .= '<div onclick="save_task_for_timer(this,\''.$calender_tasks[$start_date][$j]['task_id'].'\',\''.addslashes($calender_tasks[$start_date][$j]['task_title']).'\',\''.$calender_tasks[$start_date][$j]['task_time_spent'].'\',\''.$chk.'\',\''.$completed_depencencies.'\');" class="taskbox calicon'.$calender_tasks[$start_date][$j]['task_priority'].' '.$task_type_class.' month_master_'.$calender_tasks[$start_date][$j]['master_task_id'].' '.$move_class.'" style="background-color:'.$color_code.'; border:1px solid '.$outside_code.';" id="task_'.$calender_tasks[$start_date][$j]['task_id'].'">';
							$task_list_str .= '<div oncontextmenu="context_menu(\''.htmlspecialchars(json_encode($jsonarray)).'\');">';
							if($calender_tasks[$start_date][$j]['master_task_id']=='0' || $is_master_deleted == '1'){
								$task_list_str .= '<a class="tooltips " data-toggle="tooltip" data-original-title="'.$full_title.'" href="javascript:void(0)" onclick="edit_task(this,\''.$calender_tasks[$start_date][$j]['task_id'].'\',\''.$chk.'\');">';
							} else {
								$task_list_str .= '<a class="tooltips " data-toggle="tooltip" data-original-title="'.$full_title.'" onclick="open_seris(this,\''.$calender_tasks[$start_date][$j]['task_id'].'\',\''.$calender_tasks[$start_date][$j]['master_task_id'].'\',\''.$chk.'\');" href="javascript:void(0)">';
							}
							
							$task_list_str .= '<span class="task-desc settaskdes"> '.$title.'</span>
									<p class="task-hrs"><i style="'.$cl3.'" class="stripicon lockicon"></i>
									<span class="task-hrs" id="task_est_'.$calender_tasks[$start_date][$j]['task_id'].'" style="'.$cl.'"> '.minutesToTime($calender_tasks[$start_date][$j]['task_time_estimate']).' </span>
									</p>
                                                                        <input type="hidden" id="monthly_color_menu" value="'.$calender_tasks['color_menu'].'"/>
									<input type="hidden" id="task_data_'.$calender_tasks[$start_date][$j]['task_id'].'" value="'.htmlspecialchars(json_encode($calender_tasks[$start_date][$j])).'" />
									<input type="hidden" id="hdn_due_date_'.$calender_tasks[$start_date][$j]['task_id'].'" value="'.strtotime($calender_tasks[$start_date][$j]['task_due_date']).'" />
									<input type="hidden" id="hdn_locked_due_date_'.$calender_tasks[$start_date][$j]['task_id'].'" value="'.$calender_tasks[$start_date][$j]['locked_due_date'].'" />
									<input type="hidden" id="or_color_'.$calender_tasks[$start_date][$j]['task_id'].'" name="or_color_id" value="'.$outside_code.'" />
									<input type="hidden" id="task_type_'.$calender_tasks[$start_date][$j]['task_id'].'" name="task_type" value="'.$task_type.'" />

									<input type="hidden" id="task_spent_'.$calender_tasks[$start_date][$j]['task_id'].'" name="task_spent_time" value="'.$calender_tasks[$start_date][$j]['task_time_spent'].'" />
									<input type="hidden" id="task_status_'.$calender_tasks[$start_date][$j]['task_id'].'" name="task_status_name" value="'.$calender_tasks[$start_date][$j]['task_status_name'].'" />

									<div class="clearfix"> </div></a>
									</div>
								</div>
								';
							}
						$allocated += $calender_tasks[$start_date][$j]['task_time_estimate'];
						
						if($allocated > ($capacity)){
							$labalclass = 'redlabel';
						}
					}
                                                                        }
					$overdueClass = "";
					if($overdue){
						$overdueClass = "txtred";
					}
					$event_str .= '<div class="td-date unsorttd"> <span class="weekday-txt"> '.$wd_str.'   </span> '.$actday.' '.$href.' </div>';
					$event_str .= '<div class="task-list unsorttd" id="task_list_'.strtotime($date).'">
									<ul>
										<li> 
											<ul>
												<li> <div class="commonlabel">Capacity&nbsp;</div> </li>
												<li>  <div class="commonlabel" id="capacity_time_'.strtotime($date).'"> '.minutesToTime($capacity).' </div></li>
											</ul>
										
										<li> 
											<ul>
												<li> <div class="commonlabel">Allocated</div> </li>
												<li>  <div class="commonlabel '.$labalclass.'" id="estimate_time_'.strtotime($date).'"> '.minutesToTime($allocated).' </div></li>
											</ul>
										</li>
									</ul>
								</div>
								<div class="task-info unsorttd" id="task_info_'.strtotime($date).'">
									<ul><li> 
											<span class="tasklab-info">Overdue : </span>
											<a class="task-num '.$overdueClass.' overduehover" href="javascript:void(0)" id="overdued_'.strtotime($date).'"> '.$overdue.' </a>
										</li>
										<li> 
											<span class="tasklab-info">Due : </span>
											<span class="task-num duehover" id="due_'.strtotime($date).'"> '.$due.' </span>
										</li>
										<li> 
											<span class="tasklab-info">Completed : </span>
											<span class="task-num completedhover" id="completed_'.strtotime($date).'"> '.$completed.' </span>
										</li>
										<li> 
											<span class="tasklab-info">Scheduled :</span>
											<span class="task-num scheduledhover" id="scheduled_'.strtotime($date).'"> '.$schedulled.' </span>
										</li>
									</ul>
								</div>
								<div class="task-lable '.$sort_class.' full_task " id="'.strtotime($date).'" style="padding-bottom:10px;min-height:50px;">'.$task_list_str;
                                                               
                                                    $event_str .= '</div>';
//				} else {
//                                        $event_str .= '<div class="td-date  '.$sort_class.' full_task scroll_calender" id="'.strtotime($date).'"  style="padding-bottom:20px;"> <span class="weekday-txt"> '.$wd_str.'   </span> '.$actday.' '.$href.'';
//                                        $event_str .= '<div class="task-list unsorttd" id="task_list_'.strtotime($date).'">
//									<ul>
//										<li> 
//											<ul>
//												<li> <div class="commonlabel">Capacity&nbsp;</div> </li>
//												<li>  <div class="commonlabel" id="capacity_time_'.strtotime($date).'"> '.minutesToTime($capacity).' </div></li>
//											</ul>
//										
//										<li> 
//											<ul>
//												<li> <div class="commonlabel">Allocated</div> </li>
//												<li>  <div class="commonlabel '.$labalclass.'" id="estimate_time_'.strtotime($date).'"> '.minutesToTime($allocated).' </div></li>
//											</ul>
//										</li>
//									</ul>
//								</div></div>';
//				}
				//echo $event_str."<br/>==========================================<br/>";
				echo "<td $class $tdstyle id='td_".strtotime($date)."'>".$event_str."</td>".$this->crLt;
				
				$start_date = date("Y-m-d",strtotime("+1 days",strtotime($start_date)));
			}
			echo "</tr>".$this->crLt;
			
		}
		echo "</table></div>".$this->crLt;
		
		//---------------------------end
		//Get how many complete weeks are in the actual month
		
		//Now display the rest of the month
		//if ($actday < $lastDay['mday']){}		
		//echo "</table>".$this->crLt;
		
	}


	/**
	 *	Draw small monthly calendar
	 *
	*/	
	private function DrawMonthSmall($year = "", $month = "")
	{
		$actday = 0;
		
		if($month == "") $month = $this->arrParameters['month'];
		if($year == "") $year = $this->arrParameters['year'];
		$week_rows = 0;
		
		// today, first day and last day in month
		$firstDay = @getdate(mktime(0,0,0,$month,1,$year));
		$lastDay  = @getdate(mktime(0,0,0,$month+1,0,$year));
		
		///print_r($firstDay);
		
		// create a table with the necessary header informations
		echo "<table class='month_small'>".$this->crLt;
		echo "<tr class='tr_small_days'>";
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				echo "<td class='th_small'>".$this->arrWeekDays[($i % 7)]["short"]."</td>";		
			}
		echo "</tr>".$this->crLt;
		
		// display the first calendar row with correct positioning
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);		
		if($max_empty_days < 7){
			echo "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for($i = 1; $i <= $max_empty_days; $i++){
				echo "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
			}			
			$actday = 0;
			for($i = $max_empty_days+1; $i <= 7; $i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
					$class = " class='td_small_actday'";			
				} else if ($actday == $this->arrParameters['day']){				
					$class = " class='td_small_selday'";				
				} else {
					$class = " class='td_small'";
				} 
				echo "<td$class>$actday</td>".$this->crLt;
			}
			echo "</tr>".$this->crLt;
			$week_rows++;
		}
		
		// get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
		
		for ($i=0;$i<$fullWeeks;$i++){
			echo "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;
			for ($j=0;$j<7;$j++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month) && ($this->arrToday['year'] == $year)) {
					$class = " class='td_small_actday'";
				} else if ($actday == $this->arrParameters['day'] && ($this->arrToday['mon'] == $month)){				
					$class = " class='td_small_selday'";				
				} else {
					$class = " class='td_small'";
				}
				echo "<td$class>$actday</td>".$this->crLt;
			}
			echo "</tr>".$this->crLt;
			$week_rows++;			
		}
		
		// now display the rest of the month
		if ($actday < $lastDay['mday']){
			echo "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for ($i=0; $i<7;$i++){
				$actday++;
				if (($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month) && ($this->arrToday['year'] == $year)) {
					$class = " class='td_small_actday'";
				} else {
					$class = " class='td_small'";
				}				
				if ($actday <= $lastDay['mday']){
					echo "<td$class>$actday</td>".$this->crLt;
				} else {
					echo "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
				}
			}					
			echo "</tr>".$this->crLt;
			$week_rows++;
		}
		
		// complete last line
		if($week_rows < 5){
			echo "<tr class='tr_small' style='height:".$this->celHeight.";'>".$this->crLt;			
			for ($i=0; $i<7;$i++){
				echo "<td class='td_small_empty'>&nbsp;</td>".$this->crLt;
			}					
			echo "</tr>".$this->crLt;
			$week_rows++;			
		}
		
		echo "</table>".$this->crLt;
		
	}
	

	/**
	 *	Draw weekly calendar
	 *
	*/	
	private function DrawWeek()
	{
		// today, first day and last day in month
		$firstDay = @getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$lastDay  = @getdate(mktime(0,0,0,$this->arrParameters['month']+1,0,$this->arrParameters['year']));		
		
		// Create a table with the necessary header informations
		echo "<table class='month' border=0>".$this->crLt;
		echo "<tr>".$this->crLt;
		echo "<th colspan='7'>".$this->crLt;
			echo "<table border=0 width='100%'>".$this->crLt;
			echo "<tr>";
			echo "<th class='tr_navbar_left'>".$this->DrawDateJumper(false)."</th>";				  
			echo "<th class='tr_navbar'>".(($this->prevWeek['month'] != $this->nextWeek['month']) ? $this->prevWeek['month']."-".$this->nextWeek['month']." " : $this->prevWeek['month']." - ").$this->arrParameters['year']."</th>".$this->crLt;
			echo "<th class='tr_navbar_right'>				
					<a href=\"javascript:__doPostBack('view', 'weekly', '".$this->prevYear['year']."')\">".$this->prevYear['year']."</a> |
					<a href=\"javascript:__doPostBack('view', 'weekly', '".$this->nextYear['year']."')\">".$this->nextYear['year']."</a>
				  </th>".$this->crLt;
			echo "</tr>".$this->crLt;
			echo "</table>".$this->crLt;			  
		echo "</th>".$this->crLt;
		echo "</tr>".$this->crLt;
		echo "<tr class='tr_days'>".$this->crLt;
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				echo "<td class='th'>".$this->arrWeekDays[($i % 7)][$this->weekDayNameLength]."</td>";		
			}
		echo "</tr>".$this->crLt;
		
		// Display the first calendar row with correct positioning
		echo "<tr>".$this->crLt;
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$actday = 0;
		for($i = 0; $i <= 6; $i++){
			$actday = @date("d", mktime(0,0,0,$this->arrParameters["month"],$this->arrParameters["day"]+$i,$this->arrParameters["year"]));
			$actmon = @date("M", mktime(0,0,0,$this->arrParameters["month"],$this->arrParameters["day"]+$i,$this->arrParameters["year"]));
			if(($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $this->arrParameters["month"])) {
				$class = " class='td_actday_w'";
			}else{
				$class = " class='td'";
			}
			echo "<td$class>".$this->crLt;
			
			echo $actday.'<br><br><br><br>';
		
			echo "</td>".$this->crLt;
		}
		echo "</tr>".$this->crLt;
		echo "</table>".$this->crLt;		
	}


	/**
	 *	Draw daily calendar
	 *
	*/	
	private function DrawDay()
	{
		//echo "<br /><font color='#a60000'>This type of calendar view is not available in free version</font>";
		
		// Create a table with the necessary header informations
		echo "<table class='day_navigation' width='100%' border='0' cellpadding='0' celspacing='0'>".$this->crLt;
		echo "<tr>";
		echo "<th class='tr_navbar_left'>
			  ".$this->DrawDateJumper(false)."	
			  </th>".$this->crLt;
		echo "<th class='tr_navbar'>";
		echo " <a href=\"javascript:__doPostBack('view', 'daily', '".$this->prevDay['year']."', '".$this->ConvertToDecimal($this->prevDay['mon'])."', '".$this->ConvertToDecimal($this->prevDay['mday'])."')\">&laquo;&laquo;</a> ";
		echo $this->arrParameters['month_full_name']." ".$this->arrParameters['day'].", ".$this->arrParameters['year'];
		echo " <a href=\"javascript:__doPostBack('view', 'daily', '".$this->nextDay['year']."', '".$this->ConvertToDecimal($this->nextDay['mon'])."', '".$this->ConvertToDecimal($this->nextDay['mday'])."')\">&raquo;&raquo;</a> ";
		echo "</th>".$this->crLt;
		echo "<th class='tr_navbar_right' colspan='2'>				
			  <a href=\"javascript:__doPostBack('view', 'daily', '".$this->prevWeek['year']."', '".$this->prevWeek['mon']."', '".$this->ConvertToDecimal($this->prevWeek['mday'])."')\">".$this->ConvertToDecimal($this->prevWeek['mday'])."th ".$this->prevWeek['month']."</a> |
			  <a href=\"javascript:__doPostBack('view', 'daily', '".$this->nextWeek['year']."', '".$this->nextWeek['mon']."', '".$this->ConvertToDecimal($this->nextWeek['mday'])."')\">".$this->ConvertToDecimal($this->nextWeek['mday'])."th ".$this->nextWeek['month']."</a>
			  </th>".$this->crLt;
		echo "</tr>".$this->crLt;
		echo "</table>".$this->crLt;
		
		echo "<table class='day' width='100%' border='0' cellpadding='0' celspacing='0'>".$this->crLt;
		for($i_hour=0; $i_hour<24; $i_hour++){
			if($this->ConvertToDecimal($i_hour) == $this->arrToday['hours']) {
				$td_acthour_d = " class='td_acthour_d_h'";
				$td_d = " class='td_acthour_d'";
			} else {
				$td_acthour_d = " class='td_d_h'";
				$td_d = " class='td_d'";
			}
			echo "<tr>".$this->crLt;
			echo "<td".$td_acthour_d." width='50px'><b>".$this->ConvertToHour($i_hour)."</b></td>".$this->crLt;
			echo "<td".$td_d.">";
			//echo "..."; // add here event description
			echo "</td>".$this->crLt;
			echo "</tr>".$this->crLt;
		}
		echo "</table>".$this->crLt;
	}

	/**
	 *	Draw calendar types changer
	 *  	@param $draw - draw or return
	*/	
	private function DrawTypesChanger($draw = true)
	{
		$result = "<select class='form_select' name='view_type' id='view_type' onchange=\"window.location.href='".$this->arrParameters["current_file"]."?action=view&view_type='+this.value\">";
		foreach($this->arrViewTypes as $key => $val){
			$result .= "<option value='".$key."' ".(($this->arrParameters['view_type'] == $key) ? "selected='selected'" : "").">".$val."</option>";
		}
		$result .= "</select>";
		
		if($draw){
			echo $result;
		}else{
			return $result;
		}
	}

	/**
	 *	Draw today jumper
	 *  	@param $draw - draw or return
	*/	
	private function DrawTodayJumper($draw = true)
	{
		$result = "<input class='form_button' type='button' value='Today' onclick=\"javascript:__doPostBack('".$this->defaultAction."', '".$this->defaultView."', '".$this->arrToday["year"]."', '".$this->ConvertToDecimal($this->arrToday["mon"])."', '".$this->arrToday["mday"]."')\" />";
	
		if($draw){
			echo $result;
		}else{
			return $result;
		}
	}
	
	/**
	 *	Draw date jumper
	 *  	@param $draw - draw or return
	*/	
	private function DrawDateJumper($draw = true, $draw_day = true, $draw_month = true, $draw_year = true)
	{
		$result = "<form name='frmCalendarJumper' class='class_form'>";

		// draw days ddl
		if($draw_day){
			$result = "<select class='form_select' name='jump_day' id='jump_day'>";
			for($i=1; $i <= 31; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$result .= "<option value='".$this->ConvertToDecimal($i)."' ".(($this->arrParameters["day"] == $i_converted) ? "selected='selected'" : "").">".$i_converted."</option>";
			}
			$result .= "</select> ";			
		}else{
			$result .= "<input type='hidden' name='jump_day' id='jump_day' value='".$this->arrToday["mday"]."' />";			
		}

		// draw months ddl
		if($draw_month){			
			$result .= "<select class='form_select' name='jump_month' id='jump_month'>";
			for($i=1; $i <= 12; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$result .= "<option value='".$this->ConvertToDecimal($i)."' ".(($this->arrParameters["month"] == $i_converted) ? "selected='selected'" : "").">".$this->arrMonths[$i]."</option>";
			}
			$result .= "</select> ";			
		}else{
			$result .= "<input type='hidden' name='jump_month' id='jump_month' value='".$this->ConvertToDecimal($this->arrToday["mon"])."' />";			
		}

		// draw years ddl
		if($draw_year){			
			$result .= "<select class='form_select' name='jump_year' id='jump_year'>";
			for($i=$this->arrParameters["year"]-10; $i <= $this->arrParameters["year"]+10; $i++){
				$result .= "<option value='".$i."' ".(($this->arrParameters["year"] == $i) ? "selected='selected'" : "").">".$i."</option>";
			}
			$result .= "</select> ";
		}else{
			$result .= "<input type='hidden' name='jump_year' id='jump_year' value='".$this->arrToday["year"]."' />";			
		}
		
		$result .= "<input class='form_button' type='button' value='Go' onclick='JumpToDate()' />";
		$result .= "</form>";
		
		if($draw){
			echo $result;
		}else{
			return $result;
		}
	}

	////////////////////////////////////////////////////////////////////////////
	// Auxilary
	////////////////////////////////////////////////////////////////////////////
	/**
	 *	Check if parameters is 4-digit year
	 *  	@param $year - string to be checked if it's 4-digit year
	*/	
	private function isYear($year = "")
	{
		if(!strlen($year) == 4 || !is_numeric($year)) return false;
		for($i = 0; $i < 4; $i++){
			if(!(isset($year[$i]) && $year[$i] >= 0 && $year[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Check if parameters is month
	 *  	@param $month - string to be checked if it's 2-digit month
	*/	
	private function isMonth($month = "")
	{
		if(!strlen($month) == 2 || !is_numeric($month)) return false;
		for($i = 0; $i < 2; $i++){
			if(!(isset($month[$i]) && $month[$i] >= 0 && $month[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Check if parameters is day
	 *  	@param $day - string to be checked if it's 2-digit day
	*/	
	private function isDay($day = "")
	{
		if(!strlen($day) == 2 || !is_numeric($day)) return false;
		for($i = 0; $i < 2; $i++){
			if(!(isset($day[$i]) && $day[$i] >= 0 && $day[$i] <= 9)){
				return false;	
			}
		}
		return true;
	}

	/**
	 *	Convert to decimal number with leading zero
	 *  	@param $number
	*/	
	private function ConvertToDecimal($number)
	{
		return (($number < 10) ? "0" : "").$number;
	}

   	/**
	 *	Remove bad chars from input
	 *	  	@param $str_words - input
	 **/
	private function remove_bad_chars($str_words)
	{
		$found = false;
		$bad_string = array("select", "drop", ";", "--", "insert","delete", "xp_", "%20union%20", "/*", "*/union/*", "+union+", "load_file", "outfile", "document.cookie", "onmouse", "<script", "<iframe", "<applet", "<meta", "<style", "<form", "<img", "<body", "<link", "_GLOBALS", "_REQUEST", "_GET", "_POST", "include_path", "prefix", "http://", "https://", "ftp://", "smb://", "onmouseover=", "onmouseout=");
		for ($i = 0; $i < count($bad_string); $i++){
			$str_words = str_replace($bad_string[$i], "", $str_words);
		}
		return $str_words;            
	}

	/**
	 *	Get max day for month
	 *	  	@param $month - month
	 *  	@param $day - day
	*/
	private function GetDayForMonth($month = '', $day = '')
	{		
		if($day < 29){
			return $day;
		}else if($day == 29){			
			if((int)$month == 2){
				return 28;
			}else{
				return 29;
			}			
		}else if($day == 30){
			if((int)$month != 2){
				return 30;
			}else{
				return 28;
			}
		}else if($day == 31){
			if((int)$month == 2){
				return 28;
			}else if((int)$month == 4 || (int)$month == 6 || (int)$month == 9 || (int)$month == 11){
				return 30;
			}else{
				return 31;
			}			
		}else{
			return 30;	
		}		
	}

	/**
	 *	Convert to hour formar with leading zero
	 *  	@param $number
	*/	
	private function ConvertToHour($number)
	{
		return (($number < 10) ? "0" : "").$number.":00";
	}
	
}
?>
