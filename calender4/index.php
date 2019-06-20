<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP Calendar">
    <title>ApPHP Calendar</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<center>
<?php
    
    ## +---------------------------------------------------------------------------+
    ## | 1. Creating & Calling:                                                    | 
    ## +---------------------------------------------------------------------------+
    // invlude calendar class
    require_once("calendar.class.php");
    
    // create calendar object
    $objCalendar = new Calendar();
    
    ## +---------------------------------------------------------------------------+
    ## | 2. General Settings:                                                      | 
    ## +---------------------------------------------------------------------------+

    ## *** set calendar width and height
    $objCalendar->SetCalendarDimensions("800px", "500px");
    ## *** set week day name length - "short" or "long"
    $objCalendar->SetWeekDayNameLength("long");
    ## *** set start day of week: from 1 (Sanday) to 7 (Saturday)
    $objCalendar->SetWeekStartedDay("1");
    ## *** set calendar caption
    $objCalendar->SetCaption("ApPHP Calendar v".Calendar::Version());

    ## +---------------------------------------------------------------------------+
    ## | 3. Draw Calendar:                                                         | 
    ## +---------------------------------------------------------------------------+
    
    $objCalendar->Show();
    
?>
</center>
</body>
</html>
