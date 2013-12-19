<?php
/*
Plugin Name: Cart Conversion Rate Calculator
Plugin URI: http://www.usingwordpressforbusiness.com/cartconversions
Description: Creates A Backend Report For Viewing Cart -> Sale Coversion Rates
Author: Matthew Brown
Author URI: http://www.usingwordpressforbusiness.com
Version: 1.0
License: GPLv2 or later
*/

function cartconversions_page () {
?>

<div class="wrap">
	<h2>Cart Sale Conversion Rates</h2>
    <div id="dvData" class="cartconversionstablebox">
    
    	<div style="padding:10px;border:#ffcc66 1px solid;background:#ffff99;margin:0px;margin-bottom:10px;">
			The report below shows you the conversion rate of the visitors to your website that have visited their shopping cart and completed an order.
    	</div>
        
        <h3>Set The Starting Order For Your Calculations</h3>
    
    	<div style="width:45%;padding:10px;border:#cccccc 1px solid;background:#f2f2f2;float:left;margin:0px;margin-bottom:20px;">
        	<p>Please enter the post id of the last order placed before you installed this plugin.  All counting of orders, and adding of order values will commence from this order forward.</p>
        	<p style="font-style:italic;">(so that your calculations are not thrown off by orders that were placed before visits to the "Cart" page started being counted).</p>
        </div>
    
    	<div style="width:45%;padding:10px;border:#cccccc 1px solid;background:#f2f2f2;float:right;margin:0px;margin-bottom:20px;">
    		<form method="post" action="options.php">
				<?php wp_nonce_field('update-options'); ?>
            	<h3>Last Order Number</h3>
                <p>(only set this field once after activation)</p>
            	<p><input name="cartconversions_starter" type="text" id="cartconversions_starter" value="<?php echo get_option('cartconversions_starter'); ?>" />
            	<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="cartconversions_starter" />
				<input type="submit" value="Save Settings" /></p>
        	</form>
        </div>
    
    	<table class="conversiontable" id="ReportTable" style="width:100%;text-align:left;margin-bottom:20px;" cellpadding="0" cellspacing="0">
    		<tr>
        		<th style="text-align:center;">Cart Views</th>
            	<th style="text-align:center;">Orders</th>
            	<th style="text-align:center;">Conversion Rate</th>
            	<th style="text-align:center;">Abandoned Cart Rate</th>
        	</tr>
            
            <?php 
				// Start With The Value Of The Start Number Order ID To Get Timing Correct
				$lastorder = get_option('cartconversions_starter');
			
				// Begin The Product Table Calls
				$cartpagequery = new wp_Query('pagename=cart');
				while($cartpagequery->have_posts()) : $cartpagequery->the_post() ;
				
				// Number of times that the Cart page has been viewed
				$cartviewcount = getPostViews(get_the_ID());
				
				// Retrieve the total number of orders from the database
				global $wpdb;
				$shoporders = $wpdb->query("SELECT * FROM wp_posts WHERE ID > '".$lastorder."' AND post_type LIKE '%shop_order%'");
				$shopordercount = $wpdb->num_rows;
				
				// Calculate the cart -> sale conversion rate for the store
				if($shopordercount != '0') {
					$cartconversions = $shopordercount / $cartviewcount;
					$cartconversions = $cartconversions * 100;
				} else {
					$cartconversions = '0';	
				}
				
				// Calculate The Abandoned Cart Rate (simple substraction)
				$abandonedcarts = 100 - $cartconversions;
				
				/** For Later Maybe
				// Pull the total dollar value of the orders in the database
				$shopordervalue = $wpdb->get_var("SELECT sum(meta_value) FROM wp_postmeta WHERE meta_key = '_order_total'");
				
				// Determine the value of each visitor to the cart page
				$cartvisitorvalue = $shopordervalue / $cartviewcount;
				**/
				
			?>
            
            	<tr>
                	<td style="text-align:center;border-left:#cccccc 1px solid;"><?php echo getPostViews(get_the_ID()); ?></td>
                    <td style="text-align:center;"><?php echo $shopordercount; ?></td>
                    <td style="text-align:center;"><?php echo round($cartconversions,2); ?> %</td>
                    <td style="text-align:center;border-right:#cccccc 1px solid;"><?php echo $abandonedcarts ; ?> %</td>
                </tr>
            
            <?php endwhile; wp_reset_query(); ?>
            
    	</table>
        
        <div style="padding:10px;border:#cccccc 1px solid;background:#f2f2f2;">
        	<h3>How To Use This Report</h3>
            <p>This report is intended to be a simple view of two of the most important metrics of your ecommerce store. First, your conversion rate (the % of visitors who make it to the shopping cart and then complete a purchase) and conversely, your abandoned cart percentage.</p>
            <p>Obviously, the higher your conversion rate and lower your abandoned cart rate, the more money you make.</p>
            <p>By increasing your cart conversion rate, you can dramatically increase your sales (and profits) without the added expense of developing large increases in traffic.</p>
            <p>Here are a few things you can do to increase your conversion rate:</p>
            <ol>
            	<li>Clean up and simplify your Cart and Checkout pages - clean and clutter-free checkouts generally inspire more confidence in shoppers.<br /></li>
                <li>Ensure that you are using SSL (have a certification) on your checkout pages - today's shopper will simply not shop with you if you don't.<br /></li>
                <li>Offer "Free Shipping" on all orders, or with minimum purchases - this is a real popular (and almost expected) feature with today's online shoppers.<br /></li>
                <li>Check that your pricing is inline with your online competitors - savvy shoppers with "price shop" you and if they can find the same thing for a far better price elsewhere, they will.  Google Shopping &amp; Amazon are great places to check that your prices are inline.<br /></li>
				<li>Use a trusted checkout partner (Paypal, Google Checkout, Authorize.net) that visitors will recognize and that will inspire confidence in them.<br /></li>
            </ol>
            <p>Lastly, no matter what you do, you will obviously always have abandoned carts.  A solid system for automatically following up (via email) with shoppers who abandon their carts is a great way to turn a percentage of those lost orders into more sales and profits for you.</p>
        </div>
        
    </div>
    
    <div style="width:25%;padding:10px;border:#cccccc 1px solid;float:right;margin:0px;">
    	<h3 style="margin-top:0px;">Want More Marketing Power?</h3>
        <p>Check out our WooMetrix Pro Kit, which includes our enhanced Conversion Report.  In this professional upgrade, you will get the conversion rates for every single product in your WooCommerce Store.</p>
        <p>Compare which products convert the best (and the worst) and use your best converting products as templates to improve your other products.</p>
    	<a href="http://www.usingwordpressforbusiness.com/woometrix-marketing-conversion-pro-pack" target="blank" style="text-align:center;display:block;width:100%;height:40px;line-height:40px;background:#006699;color:#ffffff;text-decoration:none;">
        	<h3 style="margin:0px;">Check Out This Power Pack</h3>
        </a>
    </div>

</div>

<?php
}
function cartconversions_menu () {
add_dashboard_page('WooMetrix Shopping Cart Conversions','Cart Conversions','read','cartconversions_view', 'cartconversions_page');
}

add_action('admin_menu','cartconversions_menu');
?>

<?php
// Create Functions For Generating & Displaying Product View Counts
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Create And Insert The Code For WP Into The Header Of The Website On The Cart Page
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	add_action( 'template_redirect', 'cart_views' );

function cart_views()
{
    if (is_page('Cart'))
        setPostViews( get_the_ID() );
}

//Create and insert the function to include the conversioncart.css stylesheet in the admin pages header

function cartconversionstyles() {
	echo '<link rel="stylesheet" href="'.plugins_url( 'cartconversions.css', __FILE__ ) .'" type="text/css" media="all" />';
}

// Add hook for admin <head></head>
add_action('admin_head', 'cartconversionstyles');

// Activate & Deactive Hooks
register_activation_hook(__FILE__,'cartconversions_install');
register_deactivation_hook( __FILE__, 'cartconversions_remove' );

// On activation, setup the database options field
function cartconversions_install() {
	/* Create a new database field */
	update_option("cartconversions_starter", '0');
}

// On deactivation, remove the database options field
function cartconversions_remove() {
	/* Delete the database field */
	delete_option('cartconversions_starter');
	remove_action( 'template_redirect', 'cart_views' );
}

?>