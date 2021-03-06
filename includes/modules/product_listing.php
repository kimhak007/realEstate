<?php
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
?>

<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>
  <div class="container">
<?php include('advanced_search_box_right.php');?>
      <div class="col-md-8 col-sm-6">
<?php
  if ($listing_split->number_of_rows > 0) {
  $listing_query = tep_db_query($listing_split->sql_query);
  $prod_list_contents = NULL;

  while ($listing = tep_db_fetch_array($listing_query)) {
    if (strlen(strip_tags($listing['products_description'], '<br>')) > 370){
       $str_description = substr(strip_tags($listing['products_description'], '<br>'), 0, 370) . '...';
   }else{
        $str_description = strip_tags($listing['products_description'], '<br>');
   }
   switch ($listing['products_promote']) {
        case 3:
                $classBig = 'col-md-6';
                $classSmall = 'col-sm-6';
                $height = '268px';
                $heightLogo = ' style="max-width: 120px;max-height: 60px;margin-bottom: 10px;"';
                break;
            case 2:
                $classBig = 'col-md-5';
                $classSmall = 'col-sm-7';
                $height = '228px';
                $heightLogo = ' style="max-width: 100px;max-height: 37px;margin-bottom: 10px;"';
                break;
            default:
                $classBig = 'col-md-4';
                $classSmall = 'col-sm-8';
                $height = '200px';
                $heightLogo = '';
    }
    $prod_list_contents .= '
        <div class="property-listing-box sale-block">
            <!-- Property Main Box -->
            <div class="property-main-box">
              <div class="'.$classBig.' p_z">
                <a title="' . $listing['products_name'] .'" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '">
                     ' . tep_image(DIR_WS_IMAGES . $listing['products_image_thumbnail'], '', '', '', 'style="width: 100%;height: '.$height.';"') . '
                </a>
                <div class="' . $class . '">'. $text .'</div>
              </div>
              <div class="'.$classSmall.' p_z">
                <div class="property-details">
                  <a
                    title="Property Title"
                    href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']) . '"
                  >
                    ' . $listing['products_name'] .'
                  </a>
                  <h4>
                    ' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) .'
                  </h4>
                  <p>
                    ' . $str_description .'
                  </p>
                  '. ($listing['products_promote'] > 1 ? '<img src="images/' . $listing['company_logo'] . '" '. $heightLogo .'/>'  : '' ).'
                  <ul>
                    <li>
                        <i
                            class="fa fa-heart-o heart-icon"
                            data-product="'. $listing['products_id']. '"
                            data-type="insert"
                        ></i>
                    </li>
                    <li><i class="fa fa-eye"></i> '. $listing['products_viewed'] .' </li>
                        <li><img src="images/aa-listing/bedroom-icon.png" alt="bedroom-icon"> '.$listing['bed_rooms'].' </li>
                    <li><img src="images/aa-listing/bathroom-icon.png" alt="bathroom-icon"> '.$listing['bath_rooms'].' </li>
                    <li><i class="fa fa fa-institution"></i> ' . $listing['number_of_floors'] . ' </li>
                  </ul>
                </div>
              </div>
            </div><!-- Property Main Box /- -->
          </div>';

  }

    echo ' <!-- Property Listing Section -->
        <div id="property-listing" class="property-listing">
            <div class="">
              <div class="property-left col-md-12 p_l_z content-area">
    ';
    echo $prod_list_contents;
    ?>

    <!-- Pagination -->
      <div class="row">
        <div class="col-md-12" style="text-align: center;">
          <div>
              <?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS) ?>
          </div>
          <div class="listing-pagination">
            <ul class="pagination">
              <?php
                echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y')));
              ?>
            </ul>
          </div>
        </div>
    </div>
    <!-- Pagination /- -->
    </div>
  </div>
</div>
    <!-- Property Listing Section /- -->
<?php
} else {
?>
    <div class="property-left p_l_z content-area">
      <div class="alert alert-info"><?php echo TEXT_NO_PRODUCTS; ?></div>
    </div>
<?php
    }
?>
</div>
<div class="col-md-2 col-sm-3">
<?php
include('advertisement_right.php');
?>
</div>
