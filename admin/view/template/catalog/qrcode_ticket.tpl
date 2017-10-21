<!DOCTYPE html>
<html>
<head>
	<title>二维码</title>
</head>

<style type="text/css">
	.ticket{
		/*align-content: center;*/
		margin-left: 5px;
	}
	.content{
		font-size: 0.1px;
		line-height: 0.5px;
	}
	td{
		font-size: 0.5em;
	}
	.qrcode_img{
		padding-left: 33px;
	}
	.qrcode_th{
		font-weight: bold;
		font-size: 12px;
		padding-left: 40px;
	}
	.qrcode_price{
		font-weight: bold;
		font-size: 16px;
		padding-left: 15px;
	}
	.short_input{
		border:none;
		width: 60px;
		font-size: 1em;
	}
	.long_input{
		border:none;
		width: 120px;
		font-size: 1em;
	}

	/*设置不被打印出来地方*/
	@media Print { .no_print { DISPLAY: none }}
</style>
<script type="text/javascript">
	function edit(){
		var ainput=document.getElementsByTagName('input');
		for (var i = 0; i <ainput.length; i++) {
			ainput[i].readOnly=false;
			ainput[i].style="border: 1px solid";
			console.log(ainput[i]);
		}
	}
	function qrcode_print(){
		var ainput=document.getElementsByTagName('input');
		for (var i = 0; i <ainput.length; i++) {
			ainput[i].readOnly=true;
			ainput[i].style="readOnly:true";
			ainput[i].style="border:none";

		}
		window.print();
	}
</script>

<body>
	<div class="ticket">
		<form method="post">
			<p></p>
			<table>
				<tr><td class="qrcode_th">合格证</td></tr>
				<tr><td><img class="qrcode_img" src="view/image/qrcode/<?php echo $model; ?>.png" alt="<?php echo $model; ?>" width="50px"/></td></tr>
				<tr></tr>
			</table>
			<table cellspacing="0">
				<tr>
					<td><input class="short_input" readOnly='true' type="text" name="styleCode" value="<?php echo "款号: ".$styleCode ?>"></td>
					<td><input class="short_input" readOnly='true' type="text" name="partName" value="<?php echo "品名: ".$partName ?>"></td>
				</tr>
				<tr>
					<td><input class="short_input" readOnly='true' type="text" name="size" value="<?php echo "尺码: ".$size ?>"></td>
					<td><input class="short_input" readOnly='true' type="text" name="color" value="<?php echo "颜色: ".$color ?>"></td>
				</tr>
				<tr><td><input class="short_input" readOnly='true' type="text" name="rank" value="<?php echo "等级: ".$rank ?>"></td></tr>
			</table>
			<table cellspacing="0">
				<tr>
					<td><input class="long_input" readOnly='true' type="text" name="fabric" value="<?php echo "面料: ".$fabric ?>"></td>
				</tr>
				<tr>
					<td><input class="long_input" readOnly='true' type="text" name="address" value="<?php echo "地址: ".$address ?>"></td>
				</tr>
			</table>
			<p></p>
			<table>
				<tr><td class="qrcode_price"><?php echo $price ?></td></tr>
			</table>
		</form>
	</div>

	<div class="no_print">
		<button onclick="qrcode_print();">打印标签</button>
		<button onclick="edit();">编辑标签</button>
	</div>

</body>
</html>