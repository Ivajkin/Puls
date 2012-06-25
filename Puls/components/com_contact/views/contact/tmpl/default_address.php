  <?php
/** $Id: default_address.php 12387 2009-06-30 01:17:44Z ian $ */
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php if ( ( $this->contact->params->get( 'address_check' ) > 0 ) &&  ( $this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode ) ) : ?>
<table border="0">
  <tr>
    <td>
      <div id="ymaps-map-id_133855381901717710237" style="width: 450px; height: 350px;"></div>
      <div style="width: 450px; text-align: right;">
        <a href="http://api.yandex.ru/maps/tools/constructor/?lang=ru-RU" target="_blank" style="color: #1A3DC1; font: 13px Arial,Helvetica,sans-serif;"></a>
      </div>
<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_133855381901717710237"></script>
<script type="text/javascript">
     function fid_133855381901717710237(ymaps) {
        var map = new ymaps.Map("ymaps-map-id_133855381901717710237",
           {center: [135.10086478012192, 48.49599184745651], zoom: 16, 
           type: "yandex#map"}
        );
        map.controls
                 .add("zoomControl")
                 .add("mapTools")
                 .add(new ymaps.control.TypeSelector(
             ["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"])
        );
        // создает метку в заданной геоточке
        var placemark  = new ymaps.Placemark(new ymaps.GeoPoint(135.10086478012192, 48.49599184745651));
        placemark.setIconContent("Название  точки");
        // устанавливает содержимое балуна
        //placemark.name =  "Заголовок балуна";
        //placemark.description  = "Описание балуна";
        // добавляет метку на карту
        map.addOverlay(placemark);
};
</script>
    </td>
    <td width="50">
    </td>
    <td style="text-align: center;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <?php if ( $this->contact->params->get( 'address_check' ) > 0 ) : ?>
        <tr>
          <td rowspan="6" valign="top" width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
            Адрес:
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->address && $this->contact->params->get( 'show_street_address' ) ) : ?>
        <tr>
          <td valign="top">
            <?php echo nl2br($this->escape($this->contact->address)); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->suburb && $this->contact->params->get( 'show_suburb' ) ) : ?>
        <tr>
          <td valign="top">
            <?php echo $this->escape($this->contact->suburb); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->state && $this->contact->params->get( 'show_state' ) ) : ?>
        <tr>
          <td valign="top">
            <?php echo $this->escape($this->contact->state); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->postcode && $this->contact->params->get( 'show_postcode' ) ) : ?>
        <tr>
          <td valign="top">
            <?php echo $this->escape($this->contact->postcode); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->country && $this->contact->params->get( 'show_country' ) ) : ?>
        <tr>
          <td valign="top">
            <?php echo $this->escape($this->contact->country); ?>
          </td>
        </tr>
        <?php endif; ?>
      </table>
      <br />
      <?php endif; ?>
      <?php if ( ($this->contact->email_to && $this->contact->params->get( 'show_email' )) || 
			($this->contact->telephone && $this->contact->params->get( 'show_telephone' )) || 
			($this->contact->fax && $this->contact->params->get( 'show_fax' )) || 
			($this->contact->mobile && $this->contact->params->get( 'show_mobile' )) || 
			($this->contact->webpage && $this->contact->params->get( 'show_webpage' )) ) : ?>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <?php if ( $this->contact->email_to && $this->contact->params->get( 'show_email' ) ) : ?>
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
            <?php echo $this->contact->params->get( 'marker_email' ); ?>
          </td>
          <td>
            <?php echo $this->contact->email_to; ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->telephone && $this->contact->params->get( 'show_telephone' ) ) : ?>
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
            Телефон:
          </td>
          <td>
            <?php echo nl2br($this->escape($this->contact->telephone)); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->fax && $this->contact->params->get( 'show_fax' ) ) : ?>
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
            <?php echo $this->contact->params->get( 'marker_fax' ); ?>
          </td>
          <td>
            <?php echo nl2br($this->escape($this->contact->fax)); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->mobile && $this->contact->params->get( 'show_mobile' ) ) :?>
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
            <?php echo $this->contact->params->get( 'marker_mobile' ); ?>
          </td>
          <td>
            <?php echo nl2br($this->escape($this->contact->mobile)); ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ( $this->contact->webpage && $this->contact->params->get( 'show_webpage' )) : ?>
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" >
          </td>
          <td>
            <a href=""
              <?php echo $this->escape($this->contact->webpage); ?>" target="_blank">
              <?php echo $this->escape($this->contact->webpage); ?>
            </a>
          </td>
        </tr>
        <?php endif; ?>
      </table>

      <?php endif; ?>
      <br />
      <?php if ( $this->contact->misc && $this->contact->params->get( 'show_misc' ) ) : ?>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width=""
            <?php echo $this->contact->params->get( 'column_width' ); ?>" valign="top" >
            <?php echo $this->contact->params->get( 'marker_misc' ); ?>
          </td>
          <td>
            <?php echo nl2br($this->contact->misc); ?>
          </td>
        </tr>
      </table>
      <br />
    </td>
  </tr>
</table>
<?php endif; ?>