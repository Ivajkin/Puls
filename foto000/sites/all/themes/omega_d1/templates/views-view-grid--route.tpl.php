<?php
/**
 * Created by PhpStorm.
 * User: storm
 * Date: 4/8/14
 * Time: 1:53 AM
 */

/**
 * @file
 * Default simple view template to display a rows in a grid.
 *
 * - $rows contains a nested array of rows. Each row contains an array of
 *   columns.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)) : ?>
    <h2><?php print $title; ?></h2>
<?php endif; ?>
<?php switch ($view->current_display):
        case 'route_page':
        case 'route_category_pane': ?>
        <table class="<?php print $class; ?>"<?php print $attributes; ?>>
        <?php if (!empty($caption)) : ?>
            <caption><?php print $caption; ?></caption>
        <?php endif; ?>

        <tbody>
        <?php foreach ($rows as $row_number => $columns):
            /**
             * Output table with 5 col in row
             * @func
             */
            ?>
            <tr <?php if ($row_classes[$row_number]) { print 'class="' . $row_classes[$row_number] .'"';  } ?>>
                <?php
                    $cnt= count($columns);
                    foreach ($columns as $column_number => $item): ?>
                    <td <?php if ($column_classes[$row_number][$column_number]) { print 'class="' . $column_classes[$row_number][$column_number] .'"';  } ?>>
                        <?php print $item; ?>
                    </td>
                    <?php if (($column_number+1) != $cnt && ($column_number+1) % 5 == 0): ?>
            </tr>
            <tr <?php if ($row_classes[$row_number]) { print 'class="custom ' . $row_classes[$row_number] .'"';  } ?>>
                   <?php endif; ?>
                <?php endforeach; ?>
                <?php
                /**
                 * Append to 5-column row for css compatibility
                 * @func
                 */
                $empty_col= 5 - count($columns)%5;
                if ($empty_col < 5)
                    for ($i=0; $i<$empty_col; $i++)
                        echo '<td></td>';
                ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
<?php   break;
        default: ?>
          <div class="<?php print $class; ?>"<?php print $attributes; ?>>
              <?php if (!empty($caption)) : ?>
                  <div class="caption"><?php print $caption; ?></div>
              <?php endif; ?>

              <?php foreach ($rows as $row_number => $columns): ?>
                  <?php  $tmp= ($row_classes[$row_number])? $row_classes[$row_number] : ''; ?>
                      <?php foreach ($columns as $column_number => $item): ?>
                          <div <?php if ($column_classes[$row_number][$column_number]) { print 'class="row-col ' . $tmp . ' '. $column_classes[$row_number][$column_number] .'"';  } ?>>
                              <?php print $item; ?>
                          </div>
                      <?php endforeach; ?>
              <?php endforeach; ?>
          </div>
<?php endswitch; ?>