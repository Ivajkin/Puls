if ( !( typeof changeMultilist == 'function' ) ) {
    function changeMultilist(sourceID, targetID)
    {
        list = $(sourceID);
        str = '';

        for ( i = 0; i < list.length; i++ ) {
            if ( list.options[i].selected == true ) {
                if ( str != '' ) {
                    str += ',' + list.options[i].value;
                } else {
                    str += list.options[i].value;
                }
            }
        }

        $(targetID).value = str;
    }
}