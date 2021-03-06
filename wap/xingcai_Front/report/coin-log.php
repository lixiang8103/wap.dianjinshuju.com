<?php
	$this->getTypes();
	$this->getPlayeds();
	
	// 日期限制
	if($_REQUEST['fromTime'] && $_REQUEST['toTime']){
		$timeWhere=' and l.actionTime between '. strtotime($_REQUEST['fromTime']).' and '.strtotime($_REQUEST['toTime']);
	}elseif($_REQUEST['fromTime']){
		$timeWhere=' and l.actionTime >='. strtotime($_REQUEST['fromTime']);
	}elseif($_REQUEST['toTime']){
		$timeWhere=' and l.actionTime <'. strtotime($_REQUEST['toTime']);
	}else{
		
		if($GLOBALS['fromTime'] && $GLOBALS['toTime']) $timeWhere=' and l.actionTime between '.$GLOBALS['fromTime'].' and '.$GLOBALS['toTime'].' ';
	}
	
	// 帐变类型限制
	if($_REQUEST['liqType']){
		$liqTypeWhere=' and liqType='.intval($_REQUEST['liqType']);
		if($_REQUEST['liqType']==2) $liqTypeWhere=' and liqType between 2 and 3';
	}
	

	//用户限制
	$userWhere=" and u.uid={$this->user['uid']}";
	
	// 冻结查询
	if($this->action=='fcoinModal'){
		$fcoinModalWhere='and l.fcoin!=0';
	}
	
	$sql="select b.type, b.playedId, b.actionNo, b.mode, l.liqType, l.coin, l.fcoin, l.userCoin, l.actionTime, l.extfield0, l.extfield1, l.info, u.username from {$this->prename}members u, {$this->prename}coin_log l left join {$this->prename}bets b on b.id=extfield0 where l.uid=u.uid $liqTypeWhere $timeWhere $userWhere $typeWhere $fcoinModalWhere and l.liqType not in(4,11,104) order by l.id desc";
	//echo $sql;
	
	$list=$this->getPage($sql, $this->page, $this->pageSize);
	$params=http_build_query($_REQUEST, '', '&');
	$modeName=array('1.000'=>'元', '0.100'=>'角', '0.010'=>'分', '0.001'=>'厘','1.000'=>'1元');
	$liqTypeName=array(
		1=>'充值',
		111=>'卡密充值',
		2=>'返点',
		//3=>'返点',//分红
		//4=>'抽水金额',
		5=>'停止追号',
		6=>'中奖金额',
		7=>'撤单',
		8=>'提现失败返回冻结金额',
		9=>'管理员充值',
		10=>'解除抢庄冻结金额',
		//11=>'收单金额',
		12=>'上级充值',
		13=>'上级充值成功扣款',
		50=>'签到赠送',
		51=>'首次绑定工行卡赠送',
		52=>'充值佣金',
		53=>'消费佣金',
		54=>'充值活动奖金',
		55=>'注册佣金',
		56=>'至尊佣金奖励',
		57=>'积分兑换',
		
		100=>'抢庄冻结金额',
		101=>'投注冻结金额',
		102=>'追号投注',
		103=>'抢庄返点金额',
		//104=>'抢庄抽水金额',
		105=>'抢庄赔付金额',
		106=>'提现冻结',
		107=>'提现成功扣除冻结金额',
		108=>'开奖扣除冻结金额',
		120=>'幸运大转盘赠送',
		130=>'幸运砸蛋赠送',
		140=>'存入投资理财',
		150=>'投资理财提款',
		160=>'幸运大抽奖中奖金额'
	);
	
?>


<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
                 
            <div class="ibox-content">

                        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>帐变类型</th>
                                    <th>单号</th>
                                    <th>金额</th>
                                    <th>余额</th>
                                </tr>
                            </thead>
                            <tbody class="table_b_tr">
						<?php if($list['data']) {foreach($list['data'] as $var){ ?>
                                <tr class="gradeX">
                                    <td><?=$liqTypeName[$var['liqType']]?></td>
									
								<?php if($var['extfield0'] && in_array($var['liqType'], array(2,3,4,5,6,7,10,11,100,101,102,103,104,105,108))){ ?>
                <td><a href="/index.php/record/betInfo/<?=$var['extfield0']?>" width="95%" title="投注信息" button="关闭:defaultModalCloase" target="modal"><?=$this->getValue("select wjorderId from {$this->prename}bets where id=?", $var['extfield0'])?></a>
                </td>
              
			<?php }elseif(in_array($var['liqType'], array(1,9,52))){?>
                <td><a href="/index.php/cash/rechargeModal/<?=$var['extfield0']?>" width="95%" title="充值信息" button="关闭:defaultModalCloase" target="modal"><?=$var['extfield1']?></a></td>
                
			<?php }elseif(in_array($var['liqType'], array(8,106,107))){?>
                <td><a href="/index.php/cash/cashModal/<?=$var['extfield0']?>" width="95%" title="提现信息" button="关闭:defaultModalCloase" target="modal"><?=$var['extfield0']?></a></td>
                
                
            <?php }else{ ?>
                <td>--</td>
            <?php } ?>
									
                                    <td><?=number_format($var['coin'],3)?></td>
									<td><?=$var['userCoin']?></td>
		</tr>
	
   	<?php } }else{ ?>
   
    <?php } ?>
                                
                            </tbody>
                        
                        </table>
			</div>
		</div>
	</div>
</div>



<script>
    $(document).ready(function() {
              $('.dataTables-example').dataTable( {
              //跟数组下标一样，第一列从0开始，这里表格初始化时，第四列默认降序
                "order": [[ 3, "desc" ]]
              } );
            } );
        function fnClickAddRow() {
            $('#editable').dataTable().fnAddData([
                "Custom row",
                "New row",
                "New row",
                "New row",
                "New row"]);
        }
</script>

</body>
</html>