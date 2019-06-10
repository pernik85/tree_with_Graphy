<?php foreach ($items as $item): ?>
        <ul>
            <li><a class="node" href="#node<?=$item['val']?>">Узел - <?=$item['val']?></a></li>
            <?php if(count($item['children']) > 0): ?>
                <li><?= $this->render('_tree', array('items' => $item['children']))?></li>
            <?php endif; ?>
        </ul>
<?php endforeach;?>