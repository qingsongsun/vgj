<div class="<?php echo $is_logged_in ? 'checkout-content' : ''; ?> checkout-<?php echo "shipping"; ?>-form">
    <?php if ($is_logged_in): ?>
    <h2 class="secondary-title"><?php echo "自提地址"?> </h2>
    <?php endif; ?>
    <form class="form-horizontal form-<?php echo $type; ?>">
            <div id="<?php echo $type; ?>-existing">

                <select name="<?php echo 'pickup_repository'; ?>" class="form-control">
                <?php if (isset($repositories_able)&&$repositories_able): ?>
                    
                
                    <?php foreach ($repositories_able as $repository) { ?>
    
                        <option value="<?php echo $repository['repository_id'];?>"><?php echo $repository['repository_name'] ;?></option>                        
                        <?php }?>
                    <?php else :?>
                        <option value="">无库存</option>
                    <?php endif ?>
                </select>
            </div>
    </form> 
</div>