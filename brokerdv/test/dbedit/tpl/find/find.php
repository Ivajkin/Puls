		<?php
			$br_room= gettbl($link, 'br_room');
			$br_ptype= gettbl($link, 'br_ptype');
			$br_district= gettbl($link, 'br_district');
			$br_hometype= gettbl($link, 'br_hometype');
			$br_planning= gettbl($link, 'br_planning');
			$br_state= gettbl($link, 'br_state');
			$br_balcony= gettbl($link, 'br_balcony');
			$br_lavatory= gettbl($link, 'br_lavatory');
			$br_acreusage= gettbl($link, 'br_acreusage');
            $br_realtor= gettbl($link, 'br_realtor');
			
		?>
        <!--<a  href="javascript: void(0);" class="ft_findbtn">Найти</a>-->
        <!--<a  href="javascript: void(0);" class="ahref">Найти</a>-->
        <div class="prod_filter">
<div class="selects ft_block" style="width: 45%">
    <div class="ft_visible">
        <span class='text_field'>Объект недвижимости</span>
        <select class="ft_ptype ft_tcol">
            <?php
            $i= 0;
            while ($br_ptype[$i]) {
                echo "<option value='".$br_ptype[$i]['id']." ".$br_ptype[$i]['ptype']."'>".$br_ptype[$i++]['ptype']."</option>";
            }
            ?>
        </select>
    </div>
    <div class="ft_visible">
        <span class='text_field'>Район</span>
        <div class="ft_tcol" style="display: inline;">
        <?php
        $ft_class= 'ft_visible';
        $i= 0;
        while ($br_district[$i]) {
            echo "<select class='ft_district ".$ft_class."'>";
            while ($br_district[$i]['fid_ptype'] == $br_district[$i+1]['fid_ptype'])
                echo "<option  value='".$br_district[$i]['fid_ptype'].' '.$br_district[$i]['id'].' '.$br_district[$i]['district']."' >".$br_district[$i++]['district']."</option>";
            echo "<option  value='".$br_district[$i]['fid_ptype'].' '.$br_district[$i]['id'].' '.$br_district[$i]['district']."' >".$br_district[$i++]['district']."</option>";
            if ($br_district[$i]['fid_ptype'] > 1 ) { $ft_class= 'ft_none';}
            echo '</select>';
        }
        ?>
        </div>
    </div>
	<div class="ft_visible">
		<span class='text_field'>Комнаты</span>
		<select class="ft_room ft_tcol">
		<?php
			$i= 0;
			while ($br_room[$i]) {
				echo "<option value='".$br_room[$i]['id'].' '.$br_room[$i]['room']."'>".$br_room[$i++]['room']."</option>";
			}
		?>
		</select>
	</div>
    <div class="ft_visible">
		<span class='text_field'>Тип дома</span>
		<select class="ft_hometype ft_tcol">
		<?php
			$i= 0;
			while ($br_hometype[$i]) {
				echo "<option value='".$br_hometype[$i]['id'].' '.$br_hometype[$i]['hometype']."'>".$br_hometype[$i++]['hometype']."</option>";
			}
		?>
		</select>
	</div>
	<div class="ft_visible">
		<span class='text_field'>Планировка</span>
		<select class="ft_planning ft_tcol">
		<?php
			$i= 0;
			while ($br_planning[$i]) {
				echo "<option value='".$br_planning[$i]['id'].' '.$br_planning[$i]['planning']."'>".$br_planning[$i++]['planning']."</option>";
			}
		?>
		</select>
	</div>
	<div class="ft_visible">
		<span class='text_field'>Состояние</span>
		<select class="ft_state ft_tcol">
		<?php
			$i= 0;
			while ($br_state[$i]) {
				echo "<option value='".$br_state[$i]['id'].' '.$br_state[$i]['state']."'>".$br_state[$i++]['state']."</option>";
			}
		?>
		</select>
	</div>
	<div class="ft_visible">
		<span class='text_field'>Балкон</span>
		<select class="ft_balcony ft_tcol">
		<?php
			$i= 0;
			while ($br_balcony[$i]) {
				echo "<option value='".$br_balcony[$i]['id'].' '.$br_balcony[$i]['balcony']."'>".$br_balcony[$i++]['balcony']."</option>";
			}
		?>
		</select>
	</div>
	<div class="ft_visible">
		<span class='text_field'>Сан.узел</span>
		<select class="ft_lavatory ft_tcol">
		<?php
			$i= 0;
			while ($br_lavatory[$i]) {
				echo "<option value='".$br_lavatory[$i]['id'].' '.$br_lavatory[$i]['lavatory']."'>".$br_lavatory[$i++]['lavatory']."</option>";
			}
		?>
		</select>
	</div>
	<div class="ft_none">
		<span class='text_field'>Назначение земли</span>
		<select class="ft_acreusage ft_tcol">
		<?php
			$i= 0;
			while ($br_acreusage[$i]) {
				echo "<option value='".$br_acreusage[$i]['id'].' '.$br_acreusage[$i]['acreusage']."'>".$br_acreusage[$i++]['acreusage']."</option>";
			}
		?>
		</select>
	</div>
</div>
<div class="inputs ft_block">
    <div class="ft_visible ft_tcol2_fix">
        <span class='text_field ft_tcol2_fixtxt'>Цена </span>
        <div class="ft_tcol2">
            <span class='text_field'>от</span>
            <input type="text" class="ft_cost">
            <span class='text_field'> до </span>
            <input type="text" class="ft_cost">
        </div>
    </div>
    <div class="ft_visible ft_tcol2_fix">
        <span class='text_field ft_tcol2_fixtxt'>Общая площадь </span>
        <div class="ft_tcol2">
            <span class='text_field'>от</span>
            <input type="text" class="ft_totalarea">
            <span class='text_field'> до </span>
            <input type="text" class="ft_totalarea">
        </div>
    </div>
    <div class="ft_visible ft_tcol2_fix">
        <span class='text_field ft_tcol2_fixtxt'>Жилая площадь </span>
        <div class="ft_tcol2">
            <span class='text_field'>от</span>
            <input type="text" class="ft_livearea">
            <span class='text_field'> до </span>
            <input type="text" class="ft_livearea">
        </div>
    </div>
    <div class="ft_visible ft_tcol2_fix">
        <span class='text_field ft_tcol2_fixtxt'>Площадь кухни </span>
        <div class="ft_tcol2">
            <span class='text_field'>от</span>
            <input type="text" class="ft_cookarea">
            <span class='text_field'> до </span>
            <input type="text" class="ft_cookarea">
        </div>
    </div>
    <div class="ft_visible ft_tcol2_fix">
        <span class='text_field ft_tcol2_fixtxt'>Этаж </span>
        <div class="ft_tcol2">
            <span class='text_field'>от</span>
            <input type="text" class="ft_storey">
            <span class='text_field'> до </span>
            <input type="text" class="ft_storey">
        </div>
    </div>
</div>
<div class="clearfix"></div>
        </div>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            Добавление данных
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="prod_addform" style="margin-top:5%">
                            <div>
                                <span class='text_field'>Ссылка на полное описание</span>
                                <input type="text" class="add_name" value="0"/>
                            </div>

                            <div>
                                <input type="submit" class="add_save" />
                            </div>
                            <!--Realtor-->
                            <!--<div>
                                <span class='text_field'>ФИО агента</span>
                                <input type="text" class="rl_fio" />
                            </div>
                            <div>
                                <span class='text_field'>телефон</span>
                                <input type="text" class="rl_tel" />
                            </div>
                            <div>
                                <input type="submit" class="realtor_save" />
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Изменение/Удаление
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="prod_out"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Выберите действие</h4>
                    </div>
                    <div class="modal-body">
                        Для удаления элемента нажмите на кнопку "Удалить". Для изменения -- "Изменить".
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-primary">Удалить</button>
                        <button type="button" class="btn btn-secondary btn-success">Изменить</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
