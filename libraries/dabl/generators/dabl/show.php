<h1>View <?php echo BaseGenerator::spaceTitleCase($table_name) ?></h1>
<p>
<?php foreach($actions as $action_label => $action_url): ?>
	<a href="<?php echo $action_url ?>" class="ui-state-default ui-corner-all ui-button-link" title="<?php echo $action_label . ' ' . ucfirst($single) ?>"<?php if(strtolower($action_label)=='delete') echo " onclick=\"return confirm('Are you sure?');\"";?>>
		<span class="ui-icon <?php if(array_key_exists($action_label, self::$action_icons)) echo 'ui-icon-'.self::$action_icons[$action_label]; ?>"></span><?php echo $action_label ?>

	</a>
<?php endforeach ?>
</p>
<div class="ui-widget-content ui-corner-all">
<?php
foreach($this->getColumns($table_name) as $column){
	$column_name = $column->getName();
	if($column_name==$pk)continue;
	$method = "get$column_name";
	switch($column->getType()){
		case PropelTypes::TIMESTAMP:
			$format = 'VIEW_TIMESTAMP_FORMAT';
			break;
		case PropelTypes::DATE:
			$format = 'VIEW_DATE_FORMAT';
			break;
		default:
			$format = null;
			break;
	}
?>

<p>
	<strong><?php echo $column_name ?>:</strong>
	<?php echo '<?php echo htmlentities($'.$single.'->'.$method.'('.$format.')) ?>' ?>

</p>

<?php
}
?>
</div>