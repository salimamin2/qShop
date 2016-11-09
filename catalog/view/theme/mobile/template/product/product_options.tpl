<?php if ($product_colors): ?>
    <dt>
        <label><?php echo __('text_color'); ?></label>
    </dt>
    <dd>
        <div class="list-colors input-box">
            <?php foreach ($product_colors as $product_color): ?>
                <a class="color-sample <?php echo $product_color['product_id'] . ',' . $product_color['product_type_option_value_id']; ?>" onclick="getRateMatrix(<?php echo $product_color['product_id'] . ',' . $product_color['product_type_option_value_id']; ?>)" href="javascript:void(0);" >
                    <?php echo $product_color['name']; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </dd>
<?php endif; ?>
<?php if ($options): ?>
    <?php if(isset($options[6]) && $options[6]): ?>
        <?php $i = 0; foreach ($aOption as $option) : ?>
            <dd id="rbox">
                
                <?php 
                echo CHtml::radioButtonList('option[' . $option['option_id'] .']','',CHtml::listData($option['option_value'],'option_value_id','name'));
                ?>
                <div class="clearfix"></div>
            </dd>
        <?php $i++; endforeach; ?>
    <?php endif; ?>

    <?php if (isset($options[4]) && $options[4]): ?>
        <?php foreach ($options[4] as $i => $group): ?>
            <div class="option-panel">
                <?php foreach($group['option_value'] as $value): ?>
                    <div class="skin-minimal">
                        <?php echo CHtml::radioButton('option_group',($i == 0 ? true : false),array('value' => $group['option_id'],'id' => 'option_group_' . $i));
                        echo CHtml::label($group['name'],'option_group_' . $i); ?>
                    </div>
                    <small class="<?php echo strtolower($value['name']) ?> right price-tag"><?php echo $value['name']; ?>
                        <span class="bold-font color-red"><?php echo $value['price']; ?></span>
                    </small>
                <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
            <div id="<?php echo $group['option_id'] ?>" class="group-child hide">
                <?php if (isset($group['child'])): ?>
                    <div>
                        <?php foreach ($group['child'] as $type_id => $option_types) : ?>
                            <?php foreach ($option_types as $option):
                                    switch ($type_id) {
                                    case 1:
                                    default: ?>
                                        <?php /*<div class="child-label">*/ ?>
                                            <label><?php echo $option['name'] ?>:</label>
                                        <?php /*</div>*/ ?>
                                        <div>
                                            <div class="input-box">
                                                <?php echo CHtml::dropDownList('option[' . $group['option_id']. '][' . $option['option_id']. ']','',CHtml::listData($option['option_value'],function($value) {
                                                        return 'sel_' . $value['option_value_id'];
                                                    },'option_text'),array('prompt' => 'Choose '.$option['name'])); ?>
                                            </div>
                                        </div>
                                    <?php break;
                                    case 2: ?>
                                        <div class="child-label">
                                            <label><?php echo $option['name'] ?></label>
                                        </div>
                                        <div class="option-panel">
                                            <div class="row">
                                                <?php foreach ($option['option_value'] as $value) : ?>
                                                    <div class="option-text-input input col-sm-6">
                                                        <label><?php echo $value['name'] ?>:</label>
                                                        <input type="text" class="opt_text_box" id="option_<?php echo $value['option_value_id'] ?>" name="option[<?php echo $group['option_id'] ?>][<?php echo $value['option_value_id'] ?>]" maxlength="<?php echo $value['max_size'] ?>" placeholder="<?php echo $value['help'] ?>" />
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php break;
                                    case 6: ?>
                                        <div class="child-label">
                                            <label><?php echo $option['name'] ?></label>
                                        </div>
                                        <div>
                                            <?php $params['template'] = '<div class="skin-minimal">{input}{label}</div>';
                                                $params['options'] = CHtml::listData($option['option_value'],function($value) {
                                                    return 'sel_' . $value['option_value_id'];
                                                },function($value) {
                                                    return array(
                                                        'data-group' => $group['option_id'],
                                                        'data-id' => $option['option_id'],
                                                        'maxlength' => $value['max_size'],
                                                        'placeholder' => $value['help']
                                                    );
                                                });
                                                echo CHtml::radioButtonList('option_radio','',CHtml::listData($option['option_value'],function($value) {
                                                        return 'sel_' . $value['option_value_id'];
                                                    },'name'),$params); ?>
                                        </div>
                                    <?php break;
                                    case 3: ?>
                                        <div class="child-label">
                                            <label><?php echo $option['name'] ?></label>
                                        </div>
                                        <div>
                                            <?php foreach ($option['option_value'] as $value) : ?>
                                                <div class="option-text-input input-box">
                                                    <label><?php echo $value['name'] ?>:</label>
                                                    <?php if ($value['price']): ?><?php echo $value['prefix'] . ' ' . $value['price'] ?><?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php break;
                                    case 5:
                                    case 7:
                                        echo HelperCatalog::generateButtonHtml(array($option),($type_id == 7 ? 2 : 1));
                                    break;
                                } ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <button type="reset" class="button right"><span>Clear fields</span></button>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($options[2]) && !empty($options[2])): ?>
        <?php foreach ($options[2] as $option): ?>
            <dt>
            </dt>
            <dd>
                <?php foreach ($option['option_value'] as $value) : ?>
                    <div>
                        <label><?php echo $value['name'] ?>:</label>
                        <input type="text" class="opt_text_box" id="option_<?php echo $value['option_value_id'] ?>" name="option[<?php echo $option['option_id'] ?>][<?php echo $value['option_value_id'] ?>]" maxlength="<?php echo $value['max_size'] ?>" placeholder="<?php echo $value['help'] ?>" />
                    </div>
                <?php endforeach; ?>
            </dd>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($options[3]) && !empty($options[3])): ?>
        <?php $extra_option = $options[3]; ?>
    <?php endif; ?>

    <?php if (isset($options[1]) && !empty($options[1])): ?>
        <?php foreach ($options[1] as $option): ?>
            <div>
                <?php 
                $params = array();
                $params['class'] = 'size-select option_value_group_id';
                $params['prompt'] = 'Choose '.$option['name'];
                if(isset($option['child']))
                    $params['prompt'] = 'Select';
                $params['options'] = CHtml::listData($option['option_value'],'option_value_id',function($value) { return array('data-price' => $value['price_org']); });
                echo CHtml::dropDownList('option[' . $option['option_id'] . ']','',CHtml::listData($option['option_value'],'option_value_id','option_text'),$params); 
                ?>
            <div class="clearfix"></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($options[7]) && !empty($options[7])): ?>
        <?php echo HelperCatalog::generateButtonHtml($options[7],2); ?>
    <?php endif; ?>
    <?php if (isset($options[5]) && !empty($options[5])): ?>
        <?php echo HelperCatalog::generateButtonHtml($options[5]); ?>
    <?php endif; ?>
    <?php if(isset($options[8]) && !empty($options[8])): ?>
        <?php foreach ($options[8] as $option): ?>
            <div>
                <?php foreach ($option['option_value'] as $value) : ?>
                    <div>
                        <label><?php echo $value['name'] ?>:</label>
                        <textarea class="opt_text_box" id="option_<?php echo $value['option_value_id'] ?>" name="option[<?php echo $option['option_id'] ?>][<?php echo $value['option_value_id'] ?>]" placeholder="<?php echo $value['help'] ?>" ></textarea>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>