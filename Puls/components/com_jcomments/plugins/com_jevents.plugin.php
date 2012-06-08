<?php

class jc_com_jevents extends JCommentsPlugin 
{
	function getObjectInfo($id, $language = null)
	{
		$info = new JCommentsObjectInfo();

		$db = JFactory::getDBO();

		$query = 'SELECT det.summary, rpt.rp_id, ev.created_by, ev.access'
			. ' FROM #__jevents_repetition AS rpt '
			. ' LEFT JOIN #__jevents_vevdetail AS det ON det.evdet_id = rpt.eventdetail_id '
			. ' LEFT JOIN #__jevents_vevent AS ev ON ev.ev_id = rpt.eventid '
			. ' WHERE rp_id = ' . $id;

		$db->setQuery($query);
		$row = $db->loadObject();
			
		if (!empty($row)) {
			$info->title = $row->summary;
			$info->access = $row->access;
			$info->userid = $row->created_by;
			$info->link = JRoute::_( 'index.php?option=com_jevents&task=icalrepeat.detail&evid=' . $id );
		}

		return $info;
	}
}
