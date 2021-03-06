<?php
    $new_products_query = tep_db_query("
      select
        p.products_id,
        pd.products_viewed,
        p.products_image_thumbnail,
        p.products_image,
        p.bed_rooms,
        p.bath_rooms,
        p.products_promote,
        p.number_of_floors,
        p.products_tax_class_id,
        pd.products_name,
        if(s.status, s.specials_new_products_price, p.products_price) as products_price
      from
        " . TABLE_PRODUCTS . " p
            left join
        " . TABLE_SPECIALS . " s
            on
        p.products_id = s.products_id,
        " . TABLE_PRODUCTS_DESCRIPTION . " pd
    where
        p.products_status = 1
            and
        p.products_id = pd.products_id
            and
        pd.language_id = '" . (int)$languages_id . "'
            order by
        p.products_promote desc, rand(), p.products_date_added desc
        limit 6"
    );
  $num_new_products = tep_db_num_rows($new_products_query);

  if ($num_new_products > 0) {

    $new_prods_content = NULL;

    while ($new_products = tep_db_fetch_array($new_products_query)) {
        switch ($new_products['products_promote']) {
            case 3:
                $classBig = 'col-md-6';
                $classSmall = 'col-sm-6';
                $width = '250px';
                break;
            case 2:
                $classBig = 'col-md-4';
                $classSmall = 'col-sm-4';
                $width = '195px';
                break;
            default:
                $classBig = 'col-md-3';
                $classSmall = 'col-sm-3';
                $width = '150px';
        }
        $p_name = $new_products['products_name'];
          $new_prods_content .= '

            <div class="item">
              <!-- col-md-12 -->
              <div class="'.$classBig.' '.$classSmall.' col-home rent-block">
                <!-- Property Main Box -->
                <div class="property-main-box">
                  <div class="property-images-box">
                    <a
                        title="Property Image"
                        href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '"
                    >
                    '
                        . tep_image(DIR_WS_IMAGES . $new_products['products_image_thumbnail'],
                        $new_products['products_name'], SMALL_IMAGE_WIDTH,
                        SMALL_IMAGE_HEIGHT, 'style="width:100%; height: '.$width.';"') .
                    '
                        </a>
                    <h4>
                        ' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '
                    </h4>
                    <div class="' . $class . '" style="display: none;">'. $text .'</div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="property-details">
                    <a title="Property Title"
                        href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '"
                    >' . $p_name . '</a>
                    <ul>
                        <li>
                            <i
                                class="fa fa-heart-o heart-icon"
                                data-product="'. $new_products['products_id']. '"
                                data-type="insert"
                            ></i>
                        </l>
                      <li>
                          <i class="fa fa fa-institution"></i>
                          ' . $new_products['number_of_floors'] . '
                      </li>
                      <li>
                        <i><img src="images/icon/bed-icon.png" alt="bed-icon" /></i>
                        '. $new_products['bed_rooms'] .'
                      </li>
                      <li>
                        <i><img src="images/icon/bath-icon.png" alt="bath-icon" /></i>
                        '. $new_products['bath_rooms'] . '
                      </li>
                    </ul>
                  </div>
                </div><!-- Property Main Box /- -->
              </div><!-- col-md-12 /- -->
           </div>
          ';
    }
?>
    <!-- Rent Property -->
    <div class="">
        <div id="rent-property-block1" class="rent-property-block">
          <?php echo $new_prods_content; ?>
        </div>
    </div>
      <!-- Rent Property /- -->
<?php
  }