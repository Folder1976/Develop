<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>Мультивалютные товары v. 2.3</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-body">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-cron" data-toggle="tab">Задачи\Планировщик</a></li>
            <li><a href="#tab-options" data-toggle="tab">Настройки</a></li>
            <li><a href="#tab-log" data-toggle="tab">Лог операций</a></li>
            <li><a href="#tab-price" data-toggle="tab">Формирование цен</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-cron">
              <div class="table-responsive">
                <form action="<?php echo $action_cron; ?>" method="post" enctype="multipart/form-data" id="action_cron">
                <table id="cron_tab" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">Валюта</td>
                      <td class="text-left">Производитель</td>
                      <td class="text-left">Поставщик</td>
                      <td class="text-left">Округление курса</td>
                      <td class="text-left">Прибавить к валютной цене</td>
                      <td class="text-left">Умножить результат</td>
                      <td class="text-left">Прибавить к результату</td>
                      <td class="text-left">Округлить результат</td>
                      <td class="text-left" style="width:1%">
                        <button type="button" onclick="$('.cron_tab').html('');" data-toggle="tooltip" title="Удалить все" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                      </td>
                      <td class="text-left">
                        <button type="button" onclick="addTask();" data-toggle="tooltip" title="Добавить задачу" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                      </td>
                      <td class="text-left">
                        <button type="button" onclick="$('#action_cron').submit();" data-toggle="tooltip" title="Сохранить список задач" class="btn btn-primary"><i class="fa fa-save"></i></button>
                      </td>
                    </tr>
                  </thead>
                  <tbody class="cron_tab">
                  <?php foreach ($mccron as $task_id => $task) { ?>
                    <tr id="task-row<?php echo $task_id; ?>">
                      <td class="text-left">
                        <select name="mccron[<?php echo $task_id; ?>][currency_id]" class="form-control">
                            <?php foreach ($currencies as $currency) { ?>
                              <?php if ($currency['value']==1) continue; ?>   
                              <option value="<?php echo $currency['currency_id']; ?>" <?php echo (($currency['currency_id']==$task['currency_id'])?'selected="selected"':''); ?> ><?php echo $currency['title']; ?></option>
                            <?php } ?>
                        </select>          
                      </td>
                      <td class="text-left">
                          <select name="mccron[<?php echo $task_id; ?>][vendor_id]" class="form-control">
                              <option value="0" selected="selected">-- все производители --</option>
                              <?php foreach ($manufacturer as $vendor) { ?>
                                <option value="<?php echo $vendor['manufacturer_id']; ?>" <?php echo ($vendor['manufacturer_id']==$task['vendor_id']?'selected="selected"':''); ?> ><?php echo $vendor['name']; ?></option>
                              <?php } ?>
                          </select>          
                      </td>
                      <td class="text-left">
                          <select name="mccron[<?php echo $task_id; ?>][supplier]" class="form-control">
                              <option value="0" selected="selected">-- все поставщики --</option>
                              <?php $task_supplier = isset($task['supplier'])?$task['supplier']:''; ?>
                              <?php foreach ($suppliers as $supplier) { ?>
                                <option value="<?php echo $supplier['name']; ?>" <?php echo ($supplier['name']==$task_supplier?'selected="selected"':''); ?> ><?php echo $supplier['name']; ?></option>
                              <?php } ?>
                          </select>          
                      </td>
                      <td class="text-left">
                        <select name="mccron[<?php echo $task_id; ?>][curr_round_mode]" class="form-control">
                          <option <?php if ($task['curr_round_mode']==-2) { ?> selected="selected"<?php } ?> value="-2">До сотен</option>
                          <option <?php if ($task['curr_round_mode']==-1) { ?> selected="selected"<?php } ?> value="-1">До десятков</option>
                          <option <?php if ($task['curr_round_mode']==0)  { ?> selected="selected"<?php } ?> value="0">До ближайшего целого</option>
                          <option <?php if ($task['curr_round_mode']==1)  { ?> selected="selected"<?php } ?> value="1">До одного знака после запятой</option>
                          <option <?php if ($task['curr_round_mode']==2)  { ?> selected="selected"<?php } ?> value="2">До двух знаков после запятой</option>
                          <option <?php if ($task['curr_round_mode']==3)  { ?> selected="selected"<?php } ?> value="3">До трех знаков после запятой</option>
                        </select>
                      </td>
                      <td class="text-left">
                          <input type="text"  name="mccron[<?php echo $task_id; ?>][add_before]" value="<?php echo $task['add_before']; ?>" class="form-control">
                      </td>
                      <td class="text-left">
                          <input type="text"  name="mccron[<?php echo $task_id; ?>][mul_after]" value="<?php echo $task['mul_after']; ?>" class="form-control">
                      </td>
                      <td class="text-left">
                          <input type="text"  name="mccron[<?php echo $task_id; ?>][add_after]" value="<?php echo $task['add_after']; ?>" class="form-control">
                      </td>
                      <td class="text-left">
                        <select name="mccron[<?php echo $task_id; ?>][round_mode]" class="form-control">
                          <option <?php if ($task['round_mode']==-2) { ?> selected="selected"<?php } ?> value="-2">До сотен</option>
                          <option <?php if ($task['round_mode']==-1) { ?> selected="selected"<?php } ?> value="-1">До десятков</option>
                          <option <?php if ($task['round_mode']==0)  { ?> selected="selected"<?php } ?> value="0">До ближайшего целого</option>
                          <option <?php if ($task['round_mode']==1)  { ?> selected="selected"<?php } ?> value="1">До одного знака после запятой</option>
                          <option <?php if ($task['round_mode']==2)  { ?> selected="selected"<?php } ?> value="2">До двух знаков после запятой</option>
                          <option <?php if ($task['round_mode']==3)  { ?> selected="selected"<?php } ?> value="3">До трех знаков после запятой</option>
                        </select>
                      </td>
                      <td class="text-right">
                        <button type="button" onclick="$('#task-row<?php echo $task_id; ?>').remove();" data-toggle="tooltip" title="Удалить задачу" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                      </td>
                      <td class="text-right">
                        <button type="button" onclick="StartTask(<?php echo $task_id; ?>);" data-toggle="tooltip" title="Немедленный старт с курсом:" class="btn btn-primary" style="background-color:yellow;color: black;font-size: 16px;"><i id="fa<?php echo $task_id; ?>" class="fa fa-fast-forward"></i></button>
                      </td>
                      <td class="text-left">
                        <?php 
                          $task_placeholder = "Курс";
                          $task_value       = "";
                          if ($task_save_mode!=0) {   
                            if (isset($task['last_value'])) {
                              if ($task_save_mode==1) { $task_placeholder = $task['last_value'];}
                              if ($task_save_mode==2) { $task_value       = $task['last_value'];}
                              }
                            }
                        ?>
                        <input type="hidden"  name="mccron[<?php echo $task_id; ?>][last_value]" value="<?php echo isset($task['last_value'])?$task['last_value']:''; ?>">
                        <input type="text" placeholder="<?php echo $task_placeholder; ?>" size="8" name="start[<?php echo $task_id; ?>]" value="<?php echo $task_value; ?>" class="form-control">
                      </td>
                    </tr>
                  <?php $task_id++; } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="11" style="text-align:center;padding-top:20px;">
                        При запуске через планировщик(cron) задачи выполняются в том порядке, в котором они идут в списке
                        <br />
                        <?php if (!$cron_mc_access_key) { ?>
                        Создайте свой пароль для запуска задач через CRON в закладке "Настройки" в графе "CRON - ключ доступа:"
                        <br />
                        Пароль должен быть сложным и не менее 8 символов. Пример: eDUYsXqgunmGPHZ8C4XLl0HxBiSdtVMj
                        <br />
                        В противном случае вы будете получать ошибку cron-post-key-short
                        <?php } else { ?>
                        Формат команды для CRON: 
                        <br />
          <b>wget -o /dev/null -O/dev/null <?php echo HTTP_SERVER; ?>index.php?route=product/mccron --post-data 'mccron=<?php echo $cron_mc_access_key; ?>'</b>
                        <br />
                        Скопируйте строку выше, вставьте в "Планировщик задач" на хостинге и настройте интервал выполнения задачи.
                        <?php } ?>
                      </td>
                    </tr>
                  </tfoot>
                </table>
                </form>
              </div>
            </div>
     
            <div class="tab-pane" id="tab-options">
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-round_mode">Округление цен при пересчете:</label>
                <div class="col-sm-7">
                  <select name="round_mode" class="form-control">
                    <option <?php if ($round_mode==-2) { ?> selected="selected"<?php } ?> value="-2">До сотен</option>
                    <option <?php if ($round_mode==-1) { ?> selected="selected"<?php } ?> value="-1">До десятков</option>
                    <option <?php if ($round_mode==0)  { ?> selected="selected"<?php } ?> value="0">До ближайшего целого</option>
                    <option <?php if ($round_mode==1)  { ?> selected="selected"<?php } ?> value="1">До одного знака после запятой</option>
                    <option <?php if ($round_mode==2)  { ?> selected="selected"<?php } ?> value="2">До двух знаков после запятой</option>
                    <option <?php if ($round_mode==3)  { ?> selected="selected"<?php } ?> value="3">До трех знаков после запятой</option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); setRoundMode($('select[name=\'round_mode\']').val());" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i id="fa-save-round" class="fa fa-save"></i></button>
                </div>
              </div>
              <br /><br />
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-save_mode">Сохранять новый курс в <b>"Сиcтема>Локализация->Валюты"</b>:</label>
                <div class="col-sm-7">
                  <select name="save_mode" class="form-control">
                    <option <?php if ($save_mode==0)  { ?> selected="selected"<?php } ?> value="0">Не сохранять</option>
                    <option <?php if ($save_mode==1)  { ?> selected="selected"<?php } ?> value="1">Сохранять</option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); setSaveMode($('select[name=\'save_mode\']').val());" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i id="fa-save-save" class="fa fa-save"></i></button>
                </div>
              </div>
              <br /><br />
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-save_mode">Сохранять курс установленный для задачи:</label>
                <div class="col-sm-7">
                  <select name="task_save_mode" class="form-control">
                    <option <?php if ($task_save_mode==0)  { ?> selected="selected"<?php } ?> value="0">Не сохранять</option>
                    <option <?php if ($task_save_mode==1)  { ?> selected="selected"<?php } ?> value="1">Сохранять как подсказку</option>
                    <option <?php if ($task_save_mode==2)  { ?> selected="selected"<?php } ?> value="2">Сохранять в поле редактирования</option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); setTaskSaveMode($('select[name=\'task_save_mode\']').val());" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i id="fa-save-save" class="fa fa-save"></i></button>
                </div>
              </div>
              <br /><br />
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-ro_support">Связанные опции:</label>
                <div class="col-sm-7">
                  <select disabled name="ro_support" class="form-control">
                    <option <?php if ($ro_support==0)      { ?> selected="selected"<?php } ?> value="0"  >Не поддерживать</option>
                    <option <?php if ($ro_support=='2_2')  { ?> selected="selected"<?php } ?> value="2_2">Поддерка для 2.2</option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); setRoSupport($('select[name=\'ro_support\']').val());" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i id="fa-save-save" class="fa fa-save"></i></button>
                </div>
              </div>
              <br /><br />
              <div class="form-group">
                <label class="col-sm-4 control-label" for="input-cron_mc_access_key">CRON - ключ доступа:</label>
                <div class="col-sm-7">
                  <input type="text"  name="cron_mc_access_key" value="<?php echo $cron_mc_access_key; ?>" class="form-control">
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="$(this).children('i').addClass('fa-spinner').addClass('fa-spin'); setCronKey($('input[name=\'cron_mc_access_key\']').val());" data-toggle="tooltip" title="Сохранить" class="btn btn-primary"><i id="fa-save-save" class="fa fa-save"></i></button>
                </div>
              </div>
            </div>
     
            <div class="tab-pane" id="tab-log">
              <?php if (count($log)==0) { ?>
                Лог пустой 
              <?php } else { ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">Дата и время</td>
                      <td class="text-left">Пользователь</td>
                      <td class="text-left">Код валюты</td>
                      <td class="text-left">Установленный курс</td>
                      <td class="text-left">Пересчитано товаров</td>
                      <td class="text-left" style="width:1%">
                        <button type="button" onclick="del_history();" data-toggle="tooltip" title="Удалить все" class="btn btn-danger"><i id="fa_del_history" class="fa fa-minus-circle"></i></button>
                      </td>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $i=0; foreach ($log as $his) { ?>
                    <tr id="log-row_<?php echo $i; ?>">
                      <td class="text-left"><?php echo $his['date']; ?></td>
                      <td class="text-left"><?php echo $his['user']; ?></td>
                      <td class="text-left"><?php echo $currencies[$his['code']]['code']; ?> (ID: <?php echo $his['code']; ?>)</td>
                      <td class="text-left"><?php echo $his['kurs']; ?></td>
                      <td class="text-left"><?php echo $his['total']; ?></td>
                      <td class="text-left" style="width:1%">
                        <button type="button" onclick="del_history_row(<?php echo $his['id']; ?>)" data-toggle="tooltip" title="Удалить" class="btn btn-danger"><i id="fa_del_history_<?php echo $his['id']; ?>" class="fa fa-minus-circle"></i></button>
                      </td>
                    </tr>
                  <?php $i++; } ?>
                  </tbody>
                </table>
              </div>
              <?php } ?>
            </div>

            <div class="tab-pane" id="tab-price">
                <div class="table-responsive">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <form id="extra_charge" enctype="multipart/form-data" method="post" action="<?php echo $action_add_prices; ?>">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td>
                                        Общая сумма заказа
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">До</span>
                                            <input class="form-control" name="price_to" value="<?php echo $extra_charge['price_to'] ?>" type="text">
                                            <span class="input-group-addon"><?php echo $currency_info['value'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group" style="width: 50%; float: left;">
                                            <span class="input-group-addon">От</span>
                                            <input class="form-control" name="price_min" value="<?php echo $extra_charge['price_min'] ?>" type="text">
                                            <span class="input-group-addon"><?php echo $currency_info['value'] ?></span>
                                        </div>
                                        <div class="input-group" style="width: 50%;">
                                            <span class="input-group-addon">До</span>
                                            <input class="form-control" name="price_max" value="<?php echo $extra_charge['price_max'] ?>" type="text">
                                            <span class="input-group-addon"><?php echo $currency_info['value'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">От</span>
                                            <input class="form-control" name="price_from" value="<?php echo $extra_charge['price_from'] ?>" type="text">
                                            <span class="input-group-addon"><?php echo $currency_info['value'] ?></span>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Наценка к закупочной цене при покупке от одной единицы товара или минимального количества</td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="sp_to" value="<?php echo $extra_charge['sp_to'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="sp_min_max" value="<?php echo $extra_charge['sp_min_max'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="sp_from" value="<?php echo $extra_charge['sp_from'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td>Наценка к закупочной цене при покупке от упаковки</td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="pop_to" value="<?php echo $extra_charge['pop_to'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="pop_min_max" value="<?php echo $extra_charge['pop_min_max'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">+</span>
                                            <input class="form-control" name="pop_from" value="<?php echo $extra_charge['pop_from'] ?>" placeholder="Цена" type="text">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>

                                </tr>
                            </table>
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script>   
var task_id = <?php echo isset($task_id)?$task_id:0; ?>;

function addTask() {	
	html  = '<tr id="task-row' + task_id + '" style="height: 50px;">';
  html  += '  <td class="text-left">';
  html  += '      <select name="mccron[' + task_id + '][currency_id]" class="form-control">';
            <?php foreach ($currencies as $currency) { ?>
              <?php if ($currency['value']==1) continue; ?>
  html  += '            <option value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['title']; ?></option>';
            <?php } ?>
  html  += '      </select>';          
  html  += '    </td>';
  html  += '  <td class="text-left">';
  html  += '      <select name="mccron[' + task_id + '][vendor_id]" class="form-control">';
  html  += '          <option value="0" selected="selected">-- все производители --</option>';
            <?php foreach ($manufacturer as $vendor) { ?>
  html  += '            <option value="<?php echo $vendor['manufacturer_id']; ?>"><?php echo addslashes($vendor['name']); ?></option>';
            <?php } ?>
  html  += '      </select>';          
  html  += '  </td>';
   html  += '<td class="text-left">';
   html  += '   <select name="mccron[' + task_id + '][supplier]" class="form-control">';
   html  += '       <option value="0" selected="selected">-- все поставщики --</option>';
          <?php foreach ($suppliers as $supplier) { ?>
   html  += '         <option value="<?php echo $supplier['name']; ?>"><?php echo addslashes($supplier['name']); ?></option>';
          <?php } ?>
   html  += '   </select>';          
   html  += '</td>';
  html  += '  <td class="text-left">';
  html  += '    <select name="mccron[' + task_id + '][curr_round_mode]" class="form-control">';
  html  += '      <option value="-2">До сотен</option>';
  html  += '      <option value="-1">До десятков</option>';
  html  += '      <option value="0">До ближайшего целого</option>';
  html  += '      <option value="1">До одного знака после запятой</option>';
  html  += '      <option value="2">До двух знаков после запятой</option>';
  html  += '      <option selected="selected" value="3">До трех знаков после запятой</option>';
  html  += '    </select>';
  html  += '  </td>';
  html  += '  <td class="text-left">';
  html  += '      <input type="text"  name="mccron[' + task_id + '][add_before]" value="0" class="form-control">';
  html  += '  </td>';
  html  += '  <td class="text-left">';
  html  += '      <input type="text"  name="mccron[' + task_id + '][mul_after]" value="1" class="form-control">';
  html  += '  </td>';
  html  += '  <td class="text-left">';
  html  += '      <input type="text"  name="mccron[' + task_id + '][add_after]" value="0" class="form-control">';
  html  += '  </td>';
  html  += '  <td class="text-left">';
  html  += '    <select name="mccron[' + task_id + '][round_mode]" class="form-control">';
  html  += '      <option value="-2">До сотен</option>';
  html  += '      <option value="-1">До десятков</option>';
  html  += '      <option selected="selected" value="0">До ближайшего целого</option>';
  html  += '      <option value="1">До одного знака после запятой</option>';
  html  += '      <option value="2">До двух знаков после запятой</option>';
  html  += '      <option value="3">До трех знаков после запятой</option>';
  html  += '    </select>';
  html  += '  </td>';
  html  += '  <td class="text-right">';
  html  += '  <button type="button" onclick="$(\'#task-row' + task_id + '\').remove();" data-toggle="tooltip" title="Удалить задачу" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>';
  html  += '  </td>';
  html  += '  <td class="text-right">';
  html  += '  <button type="button" onclick="StartTask(' + task_id + ');" data-toggle="tooltip" title="Немедленный старт с курсом:" class="btn btn-primary" style="background-color:yellow;color: black;font-size: 16px;"><i id="fa' + task_id + '" class="fa fa-fast-forward"></i></button>';
  html  += '  </td>';
  html  += '  <td class="text-left">';
  html  += '  <input type="hidden"  name="mccron[' + task_id + '][last_value]" value="">';
  html  += '  <input type="text" placeholder="Курс" size="8" name="start[' + task_id + ']" value="" class="form-control">';
  html  += '  </td>';
  html  += '</tr>';
	
	$('#cron_tab tbody').append(html);
	task_id++;
}
//------------------------------------------------------------------------------
function StartTask(task_id) {
if ($('input[name=\'start[' + task_id + ']\']').val()<=0) {alert ("ОШИБКА: не введен курс!"); return;}
var url = 'index.php?route=extension/module/multycurrgoods/start&token=<?php echo $token; ?>';
var data = { 
    task_id:          task_id,
    currency_id:      $('select[name=\'mccron[' + task_id + '][currency_id]\']').val(),
    manufacturer_id:  $('select[name=\'mccron[' + task_id + '][vendor_id]\']').val(),
    supplier:         $('select[name=\'mccron[' + task_id + '][supplier]\']').val(),
    curr_round_mode:  $('select[name=\'mccron[' + task_id + '][curr_round_mode]\']').val(),
    add_before:       $('input[name=\'mccron[' + task_id + '][add_before]\']').val(),
    mul_after:        $('input[name=\'mccron[' + task_id + '][mul_after]\']').val(),
    add_after:        $('input[name=\'mccron[' + task_id + '][add_after]\']').val(),
    last_value:       $('input[name=\'mccron[' + task_id + '][last_value]\']').val(),
    round_mode:       $('select[name=\'mccron[' + task_id + '][round_mode]\']').val(),
    kurs:             $('input[name=\'start[' + task_id + ']\']').val()
    };
$.ajax({
type:'post',
url: url,
data: data,
beforeSend: function() { 
    $('#fa'+task_id).removeClass('fa-fast-forward').addClass('fa-spinner').addClass('fa-spin');      
    },
success: function() {
  alert ("Завершено");
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
});
}
//------------------------------------------------------------------------------     
function del_history() {
var url = 'index.php?route=extension/module/multycurrgoods/del_history&token=<?php echo $token; ?>';
$.ajax({
type:'post',
url: url,
beforeSend: function() { 
    $('#fa_del_history').removeClass('fa-minus-circle').addClass('fa-spinner').addClass('fa-spin'); 
    },
success: function() {
  alert ("Завершено");
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  },
error: function(xhr, ajaxOptions, thrownError) {
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
});
}
//------------------------------------------------------------------------------
function del_history_row(id) {
var url = 'index.php?route=extension/module/multycurrgoods/del_history_row&token=<?php echo $token; ?>';
var data = { id:      id };
$.ajax({
type:'post',
url: url,
data: data,
beforeSend: function() { 
    $('#fa_del_history_' + id).removeClass('fa-minus-circle').addClass('fa-spinner').css('font-size','16px').addClass('fa-spin');
    },
success: function() {
  alert ("Завершено");
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  },
error: function(xhr, ajaxOptions, thrownError) {
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
//------------------------------------------------------------------------------
function setRoundMode (round_mode) {
var url = 'index.php?route=extension/module/multycurrgoods/settings&token=<?php echo $token; ?>';
var data = { 
    round_mode:      round_mode
    };
$.ajax({
type:'post',
url: url,
data: data,
success: function() { location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>'; },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
//------------------------------------------------------------------------------
function setSaveMode (save_mode) {
var url = 'index.php?route=extension/module/multycurrgoods/settings&token=<?php echo $token; ?>';
var data = { 
    save_mode:      save_mode
    };
$.ajax({
type:'post',
url: url,
data: data,
success: function() { location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>'; },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
//------------------------------------------------------------------------------     
function setCronKey (value) {
var url = 'index.php?route=extension/module/multycurrgoods/settings&token=<?php echo $token; ?>';
var data = { 
    cron_mc_access_key:      value
    };
$.ajax({
type:'post',
url: url,
data: data,
success: function() { location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>'; },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
//------------------------------------------------------------------------------     
function setRoSupport (value) {
var url = 'index.php?route=extension/module/multycurrgoods/settings&token=<?php echo $token; ?>';
var data = { 
    ro_support:      value
    };
$.ajax({
type:'post',
url: url,
data: data,
success: function() { location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>'; },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
//------------------------------------------------------------------------------
function setTaskSaveMode (task_save_mode) {
var url = 'index.php?route=extension/module/multycurrgoods/settings&token=<?php echo $token; ?>';
var data = { 
    task_save_mode:      task_save_mode
    };
$.ajax({
type:'post',
url: url,
data: data,
success: function() { location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>'; },
error: function(xhr, ajaxOptions, thrownError) { 
  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); 
  location = 'index.php?route=extension/module/multycurrgoods&token=<?php echo $token; ?>';
  }
  });
}
</script>   



<?php echo $footer; ?>