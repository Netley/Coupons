<?=$this->load->view(branded_view('cp/header'));?>
<h1>Parse Categories according to : <a href="http://helpcenter.linkshare.com/publisher/questions.php?questionid=709" target="_blank">Linkshare Article</a></h1>

<div style="width:600px;text-align:center;" align="center">
    <?=$this->dataset->table_head();?>
    <?php
    if (!empty($this->dataset->data)) {
        foreach ($this->dataset->data as $row) {
            ?>
            <tr>										
               <td align="center"><?=$row['id_category'];?></td>
               <td align="center"><?=$row['name'];?></td>							
            </tr>
            <?php
        }
    } else {
        ?>
            <tr>
                <td colspan="7">No categories available.</td>
            </tr>
    <?php 
    } 
    ?>
    <?=$this->dataset->table_close();?>
</div>
<?= $this->load->view(branded_view('cp/footer')); ?>