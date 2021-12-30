<?php
    # $page to aktualna strona
    $nb = ModelBanner::get_to_display($page);
    if ($nb == NULL) {
        ?>
            <div style="width: 1026px; height: 188px; cursor: pointer; margin-left: auto; margin-right: auto; background-image: url('gfx/goblin.jpg');" onclick="window.location='http://www.goblin.org.pl/'"></div>
        <?php
    } else {
        ?>
        <div style="width: 1026px; height: 188px; cursor: pointer; margin-left: auto; margin-right: auto; background-image: url('uploads/native/<?php echo $nb->id_gfx; ?>.jpg');" onclick="window.location='c.php?c=<?php echo $nb->id; ?>&page=<?php echo $nb->page; ?>'"></div>
        <?php
    }
?>

