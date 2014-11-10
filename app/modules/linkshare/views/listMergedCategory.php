<?=$this->load->view(branded_view('cp/header'));?>
<h1>List Merged Categories</h1>
<div>
	<?=$this->dataset->table_head();?>
	<?
		
	if (!empty($this->dataset->data)) {
		foreach ($this->dataset->data as $row) {
		?>
			<tr>			
				<td><input type="checkbox" name="check_<?=$row['category_merged_ID'];?>" value="1" class="action_items" /></td>
				<td><?=$row['category_merged_name'];?></td>
                                <td><?php foreach($row['categories_merged'] as $id){ echo "<div>".$id."<div>"; } ?></td>			
				<td class="options" align="center">
				<a href="<?=site_url('admincp2/linkshare/editMergedCategory/'.$row['category_merged_ID']);?>">editeaza nume</a>
				</td>
			</tr>
		<?
		}
	}
	else {
	?>
	<tr>
		<td colspan="7">Nu sunt categorii.</td>
	</tr>
	<? } ?>
	<?=$this->dataset->table_close();?>
</div>
<?=$this->load->view(branded_view('cp/footer'));?>