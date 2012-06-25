<?php

/**
* Gavick Class - date class
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class GKDate for create customizable datas based on Joomla! 1.5 DB
*/

class GK_Date{

	var $Months;
	var $Days;
	var $MonthsShort;
	var $DaysShort;

	function init(){
		
		$this->Months = array(
			JText::_('JANUARY'),
			JText::_('FEBRUARY'),
			JText::_('MARCH'),
			JText::_('APRIL'),
			JText::_('MAY'),
			JText::_('JUNE'),
			JText::_('JULY'),
			JText::_('AUGUST'),
			JText::_('SEPTEMBER'),
			JText::_('OCTOBER'),
			JText::_('NOVEMBER'),
			JText::_('DECEMBER')
		);
	
		$this->MonthsShort = array(
			JText::_('JAN'),
			JText::_('FEB'),
			JText::_('MAR'),
			JText::_('APR'),
			JText::_('MAY'),
			JText::_('JUN'),
			JText::_('JUL'),
			JText::_('AUG'),
			JText::_('SEP'),
			JText::_('OCT'),
			JText::_('NOV'),
			JText::_('DEC')
		);
		
		$this->Days = array(
			JText::_('MONDAY'),
			JText::_('TUESDAY'),
			JText::_('WEDNESDAY'),
			JText::_('THURSDAY'),
			JText::_('FRIDAY'),
			JText::_('SATURDAY'),
			JText::_('SUNDAY')
		);

		$this->DaysShort = array(
			JText::_('MON'),
			JText::_('TUE'),
			JText::_('WED'),
			JText::_('THU'),
			JText::_('FRI'),
			JText::_('SAT'),
			JText::_('SUN')
		);
	}
	
	function news_date($date_string, $date_format){		
		$year = (int) substr($date_string, 0, 4);
		$month = (int) substr($date_string, 5, 2);
		$day = (int) substr($date_string, 8, 2);
		$hour = (int) substr($date_string, 11,2);
		$minutes = substr($date_string, 14,2);
		
		$day_name = date("l", strtotime($date_string));
		$month_name = '';
		$date_format = ' '.$date_format;
		$output = '';
		
		/**
		 D - full day name, 
		 s - short day name, 
		 s - day (number without 0 prefix), 
		 z - day (number with 0 prefix), 
		 M - full month name, 
		 S - short month name, 
		 m - month (number without 0 prefix), 
		 Z - month (number with 0 prefix), 
		 Y - year
		 H - hour in 24h format
		 h - hour in 12h format
		**/
		
		for($i = 0; $i < strlen($date_format); $i++)
		{
			$letter = '';
			$letter = substr($date_format, $i, 1);
			
			switch($letter)
			{
				case 'd' : 
					$output .= $day;
				break;
				
				case 'z' :
					if($day < 10) $day = '0'.$day;
					$output .= $day;
				break;
		
				case 'm' : 
					$output .= $month;
				break;
				
				case 'Z' :
					if($month < 10) $month = '0'.$month;
					$output .= $month;
				break;	
				
				case 'Y' : 
					$output .= $year;
				break;	
		
				case 'D' :
					switch($day_name){
						case 'Monday'    : $day_name = $this->Days[0];break;
						case 'Tuesday'   : $day_name = $this->Days[1];break;
						case 'Wednesday' : $day_name = $this->Days[2];break;
						case 'Thursday'  : $day_name = $this->Days[3];break;
						case 'Friday'    : $day_name = $this->Days[4];break;
						case 'Saturday'  : $day_name = $this->Days[5];break;
						case 'Sunday'    : $day_name = $this->Days[6];break;
					}
			
					$output .= $day_name;
				break;
		
				case 's' :
					switch($day_name){
						case 'Monday'    : $day_name = $this->DaysShort[0];break;
						case 'Tuesday'   : $day_name = $this->DaysShort[1];break;
						case 'Wednesday' : $day_name = $this->DaysShort[2];break;
						case 'Thursday'  : $day_name = $this->DaysShort[3];break;
						case 'Friday'    : $day_name = $this->DaysShort[4];break;
						case 'Saturday'  : $day_name = $this->DaysShort[5];break;
						case 'Sunday'    : $day_name = $this->DaysShort[6];break;
					}
				
					$output .= $day_name;
				break;
		
				case 'M' :
					$month_name =  $this->Months[$month-1];
					$output .= $month_name;
				break;
		
				case 'S' :
					$month_name =  $this->MonthsShort[$month-1];
					$output .= $month_name;
				break;	
				
				case 'H' :
				    $output .= $hour.':'.$minutes; 
				break;
				
				case 'h' :
					$suf = ($hour > 12) ? JText::_('PM') : JText::_('AM');
				    $output .= $hour.':'.$minutes.$suf; 
				break;
				
				default :
					$output .= $letter;
				break;
			}
		}
		
		return $output;
	}

}

/**/
/**/
/**/