<?php 
if($_->vars['failed']){
	echo '<font color="red">',($_->vars['failed']),'</font>/',($_->vars['total']),' tests failed.';
}else{
	echo '<font color="green">All</font> tests passed.';
}
?>
<br />
<ul>
<?php 
	foreach($_->vars['tests'] as $test){
		echo '<li>';
		echo $test['name'],': ';
		if($test['result']){
			echo '<font color="green">Passed</font>';
		}else{
			echo '<font color="red">Fail</font>';
		}
		echo '</li>';
	}
?>
</ul>