		<?php
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'conf.php');

            $out= array('listbox' => array());

            $out ['listbox'] []= $link->gettbl('br_ptype', 'num');
            $out ['listbox'] []= $link->gettbl('br_district', 'num');
            $out ['listbox'] []= $link->gettbl('br_room', 'num');
            $out ['listbox'] []= $link->gettbl('br_hometype', 'num');
            $out ['listbox'] []= $link->gettbl('br_planning', 'num');
            $out ['listbox'] []= $link->gettbl('br_state', 'num');
            $out ['listbox'] []= $link->gettbl('br_balcony', 'num');
            $out ['listbox'] []= $link->gettbl('br_lavatory', 'num');
            $out ['listbox'] []= $link->gettbl('br_acreusage', 'num');

            echo json_encode($out);
            exit;
        }
		?>

        <!--<a  href="javascript: void(0);" class="ft_findbtn">Найти</a>-->
        <!--<a  href="javascript: void(0);" class="ahref">Найти</a>-->
        <div class="prod_filter">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    .col-md-3 .col-md-pull-9
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
