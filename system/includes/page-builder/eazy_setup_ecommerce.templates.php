<?php

/**
 * @author Jasman
 * @copyright 2017
 */


$_lock_the_page = false;

if(isset($_SESSION['FILE_NAME']))
{
    $file_name = $_SESSION['FILE_NAME'];
} else
{
    header('Location: ./?page=dashboard&err=project');
    die();
}


if(isset($_POST['page-builder']))
{
    if(file_exists('projects/'.$_SESSION['FILE_NAME'].'/page.menu_1.json'))
    {
        @unlink('projects/'.$_SESSION['FILE_NAME'].'/page.menu_1.json');
    }

    if(file_exists('projects/'.$_SESSION['FILE_NAME'].'/page.menu_2.json'))
    {
        @unlink('projects/'.$_SESSION['FILE_NAME'].'/page.menu_2.json');
    }
    $lbl_categories = $_POST['ecommerce']['label_categories'];
    $lbl_products = $_POST['ecommerce']['label_products'];
    $lbl_carts = $_POST['ecommerce']['label_carts'];
    $lbl_how_to_order = $_POST['ecommerce']['label_how_to_order'];
    $lbl_retrieval_error_title = $_POST['ecommerce']['label_retrieval_error_title'];
    $lbl_retrieval_error_content = $_POST['ecommerce']['label_retrieval_error_content'];
    $lbl_no_result_found = $_POST['ecommerce']['label_no_result_found'];
    $lbl_pull_for_refresh = $_POST['ecommerce']['label_pull_for_refresh'];
    $lbl_search = $_POST['ecommerce']['label_search'];
    $lbl_currency_symbol = $_POST['ecommerce']['label_currency_symbol'];


    $lbl_order_ok = $_POST['ecommerce']['label_order_ok'];
    $lbl_order_err = $_POST['ecommerce']['label_order_err'];
    $lbl_no_items = $_POST['ecommerce']['label_no_items'];
    $lbl_add_to_cart = $_POST['ecommerce']['label_add_to_cart'];


    $lbl_order = $_POST['ecommerce']['label_order'];

    $background = $_POST['ecommerce']['background'];
    $backend = $_POST['ecommerce']['backend_used'];
    $site_url = $_POST['ecommerce']['site_url'];
    $order_via = $_POST['ecommerce']['order_via'];
    $how_to_order = $_POST['ecommerce']['how_to_order'];
    $contact = $mailer_url = $_POST['ecommerce']['contact'];

    $json_save = null;
    $json_save['page_builder']['ecommerce']['label_categories'] = $lbl_categories;
    $json_save['page_builder']['ecommerce']['label_products'] = $lbl_products;
    $json_save['page_builder']['ecommerce']['label_carts'] = $lbl_carts;
    $json_save['page_builder']['ecommerce']['label_how_to_order'] = $lbl_how_to_order;
    $json_save['page_builder']['ecommerce']['label_retrieval_error_title'] = $lbl_retrieval_error_title;
    $json_save['page_builder']['ecommerce']['label_retrieval_error_content'] = $lbl_retrieval_error_content;
    $json_save['page_builder']['ecommerce']['label_no_result_found'] = $lbl_no_result_found;
    $json_save['page_builder']['ecommerce']['label_pull_for_refresh'] = $lbl_pull_for_refresh;
    $json_save['page_builder']['ecommerce']['label_search'] = $lbl_search;
    $json_save['page_builder']['ecommerce']['label_currency_symbol'] = $lbl_currency_symbol;
    $json_save['page_builder']['ecommerce']['label_order'] = $lbl_order;
    $json_save['page_builder']['ecommerce']['label_add_to_cart'] = $lbl_add_to_cart;


    $json_save['page_builder']['ecommerce']['background'] = $background;
    $json_save['page_builder']['ecommerce']['backend_used'] = $backend;
    $json_save['page_builder']['ecommerce']['site_url'] = $site_url;
    $json_save['page_builder']['ecommerce']['order_via'] = $order_via;
    $json_save['page_builder']['ecommerce']['how_to_order'] = $how_to_order;
    $json_save['page_builder']['ecommerce']['contact'] = $contact;

    $json_save['page_builder']['ecommerce']['label_order_ok'] = $lbl_order_ok;
    $json_save['page_builder']['ecommerce']['label_order_err'] = $lbl_order_err;
    $json_save['page_builder']['ecommerce']['label_no_items'] = $lbl_no_items;


    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.ecommerce.json',json_encode($json_save));

    $app_json = file_get_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/app.json');
    $app_config = json_decode($app_json,true);
    $app_config['app']['index'] = 'dashboard';
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/app.json',json_encode($app_config));


    // TODO: MENU
    $menu_index = -1;
    $menu['menu']['type'] = 'tabs';
    $menu['menu']['menu_style'] = 'tabs-striped';
    $menu['menu']['menu_position'] = 'bottom';
    $menu['menu']['header_background'] = 'energized-900';
    $menu['menu']['menu_background'] = 'energized-900';
    $menu['menu']['menu_color'] = 'stable';
    $menu['menu']['header_image_background'] = '';
    $menu['menu']['expanded_header'] = 'data/images/header/header0.jpg';
    $menu['menu']['logo'] = 'data/images/header/logo.png';
    $menu['menu']['title'] = $_SESSION['PROJECT']['app']['name'];

    $menu_index++;
    $menu['menu']['items'][$menu_index]['label'] = $lbl_categories;
    $menu['menu']['items'][$menu_index]['var'] = 'categories';
    $menu['menu']['items'][$menu_index]['icon'] = 'ion-android-restaurant';
    $menu['menu']['items'][$menu_index]['icon-alt'] = 'ion-android-restaurant';
    $menu['menu']['items'][$menu_index]['type'] = 'link';
    $menu['menu']['items'][$menu_index]['option'] = '';
    $menu['menu']['items'][$menu_index]['desc'] = '';

    $menu_index++;
    $menu['menu']['items'][$menu_index]['label'] = $lbl_products;
    $menu['menu']['items'][$menu_index]['var'] = 'products';
    $menu['menu']['items'][$menu_index]['icon'] = 'ion-beer';
    $menu['menu']['items'][$menu_index]['icon-alt'] = 'ion-beer';
    $menu['menu']['items'][$menu_index]['type'] = 'link';
    $menu['menu']['items'][$menu_index]['option'] = '';
    $menu['menu']['items'][$menu_index]['desc'] = '';

    $menu_index++;
    $menu['menu']['items'][$menu_index]['label'] = $lbl_carts;
    $menu['menu']['items'][$menu_index]['var'] = 'product_cart';
    $menu['menu']['items'][$menu_index]['icon'] = 'ion-ios-cart';
    $menu['menu']['items'][$menu_index]['icon-alt'] = 'ion-ios-cart';
    $menu['menu']['items'][$menu_index]['type'] = 'link';
    $menu['menu']['items'][$menu_index]['option'] = '';
    $menu['menu']['items'][$menu_index]['desc'] = '';

    $menu_index++;
    $menu['menu']['items'][$menu_index]['label'] = $lbl_how_to_order;
    $menu['menu']['items'][$menu_index]['var'] = 'how_to_order';
    $menu['menu']['items'][$menu_index]['icon'] = 'ion-help-circled';
    $menu['menu']['items'][$menu_index]['icon-alt'] = 'ion-help-circled';
    $menu['menu']['items'][$menu_index]['type'] = 'link';
    $menu['menu']['items'][$menu_index]['option'] = '';
    $menu['menu']['items'][$menu_index]['desc'] = '';


    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/menu.json',json_encode($menu));

    // TODO: POPOVER
    $popover['popover']['icon'] = 'ion-android-more-vertical';
    $popover['popover']['title'] = '';
    $z = 0;


    $popover['popover']['menu'][$z]['title'] = 'Update Data';
    $popover['popover']['menu'][$z]['type'] = 'link';
    $popover['popover']['menu'][$z]['link'] = '#/'.$_SESSION['FILE_NAME'].'/form_user';

    $z++;
    $popover['popover']['menu'][$z]['title'] = 'About Us';
    $popover['popover']['menu'][$z]['type'] = 'link';
    $popover['popover']['menu'][$z]['link'] = '#/'.$_SESSION['FILE_NAME'].'/about_us';

    $z++;
    $popover['popover']['menu'][$z]['title'] = 'Language';
    $popover['popover']['menu'][$z]['type'] = 'show-language-dialog';
    $popover['popover']['menu'][$z]['link'] = '';

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/popover.json',json_encode($popover));


    // TODO: TABLE - CATEGORIES
    $tables = null;
    $table_index = -1;
    $table_name = 'categorie';

    $tables['tables'][$table_name]["parent"] = 'categories';
    $tables['tables'][$table_name]["title"] = $table_name;
    $tables['tables'][$table_name]["prefix"] = $table_name;
    $tables['tables'][$table_name]["template"] = "thumbnail-2";
    $tables['tables'][$table_name]["template_single"] = "none";
    $tables['tables'][$table_name]["itemtype"] = "item";
    $tables['tables'][$table_name]["itemcolor"] = "colorful";
    $tables['tables'][$table_name]["db_type"] = "offline";
    $tables['tables'][$table_name]["db_var"] = "";
    $tables['tables'][$table_name]["db_var_single"] = "";

    $tables['tables'][$table_name]["db_url"] = "";
    $tables['tables'][$table_name]["db_url_single"] = "";
    switch($backend)
    {
        case 'offline':
            $tables['tables'][$table_name]["db_type"] = "offline";
            $tables['tables'][$table_name]["db_url"] = "";
            $tables['tables'][$table_name]["db_url_single"] = "";
            break;
        case 'php-sql-generator':
            $tables['tables'][$table_name]["db_type"] = "online";
            $tables['tables'][$table_name]["db_url"] = $site_url.'?json=categorie';
            $tables['tables'][$table_name]["db_url_single"] = "";
            break;

    }
    $tables['tables'][$table_name]["items_focus"] = "scroll";
    $tables['tables'][$table_name]["max_items"] = "50";
    $tables['tables'][$table_name]["fetch_per_scroll"] = "1";
    $tables['tables'][$table_name]["icon"] = "ion-social-buffer";
    $tables['tables'][$table_name]["relation_to"] = "products";
    $tables['tables'][$table_name]["localstorage"] = "localforage";
    $tables['tables'][$table_name]["motions"] = "none";
    $tables['tables'][$table_name]["bookmarks"] = "none";
    $tables['tables'][$table_name]["column-for-price"] = "none";
    $tables['tables'][$table_name]["currency-symbol"] = $lbl_currency_symbol;
    $tables['tables'][$table_name]["languages"]['retrieval_error_title'] = $lbl_retrieval_error_title;
    $tables['tables'][$table_name]["languages"]['retrieval_error_content'] = $lbl_retrieval_error_content;
    $tables['tables'][$table_name]["languages"]['no_result_found'] = $lbl_no_result_found;
    $tables['tables'][$table_name]["languages"]['pull_for_refresh'] = $lbl_pull_for_refresh;
    $tables['tables'][$table_name]["languages"]['search'] = $lbl_search;


    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "catID";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "cat_id";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "id";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    switch($backend)
    {
        case 'offline':
            $tables['tables'][$table_name]["db_type"] = "offline";
            $tables['tables'][$table_name]["db_url"] = "?product_cat=-1";
            $tables['tables'][$table_name]["db_url_single"] = "";
            $base_link = 'cat_id';
            break;
        case 'php-sql-generator':
            $table_index++;
            $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Just a Link";
            $tables['tables'][$table_name]["cols"][$table_index]["title"] = "cat_name";
            $tables['tables'][$table_name]["cols"][$table_index]["type"] = "id";
            $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";
            $base_link = 'cat_name';
            break;

    }


    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Thumbnail";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "cat_image";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "images";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Name";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "cat_name";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "heading-1";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "[txt]";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "cat_desc";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "text";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $tables['tables'][$table_name]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/tables.categorie.json',json_encode($tables));

    // TODO: TABLE - PRODUCTS
    $tables = null;
    $table_index = -1;
    $table_name = 'product';

    $tables['tables'][$table_name]["parent"] = 'products';
    $tables['tables'][$table_name]["title"] = $table_name;
    $tables['tables'][$table_name]["prefix"] = $table_name;
    $tables['tables'][$table_name]["template"] = "thumbnail-2";
    $tables['tables'][$table_name]["template_single"] = "tabs|card";
    $tables['tables'][$table_name]["itemtype"] = "item";
    $tables['tables'][$table_name]["itemcolor"] = "colorful";
    $tables['tables'][$table_name]["db_type"] = "offline";
    $tables['tables'][$table_name]["db_var"] = "";
    $tables['tables'][$table_name]["db_var_single"] = "";
    $tables['tables'][$table_name]["db_url"] = "?product_cat=-1";
    $tables['tables'][$table_name]["db_url_single"] = "";
    $tables['tables'][$table_name]['builder_link'] = @$_SERVER["HTTP_REFERER"];

    switch($backend)
    {
        case 'offline':
            $tables['tables'][$table_name]["db_type"] = "offline";
            $tables['tables'][$table_name]["db_url"] = "?product_cat=-1";
            $tables['tables'][$table_name]["db_url_single"] = "";
            $base_link = 'cat_id';
            break;
        case 'php-sql-generator':
            $tables['tables'][$table_name]["db_type"] = "online";
            $tables['tables'][$table_name]["db_url"] = $site_url.'?product_cat=-1&json=product';
            $tables['tables'][$table_name]["db_url_single"] = $site_url.'?json=product&product_id=';
            $base_link = 'cat_name';
            break;

    }


    $tables['tables'][$table_name]["db_url_dinamic"] = "on";
    $tables['tables'][$table_name]["items_focus"] = "scroll";
    $tables['tables'][$table_name]["max_items"] = "50";
    $tables['tables'][$table_name]["fetch_per_scroll"] = "1";
    $tables['tables'][$table_name]["icon"] = "ion-social-buffer";
    $tables['tables'][$table_name]["relation_to"] = "";
    $tables['tables'][$table_name]["localstorage"] = "localforage";
    $tables['tables'][$table_name]["motions"] = "none";
    $tables['tables'][$table_name]["bookmarks"] = "cart";
    $tables['tables'][$table_name]["column-for-price"] = "product_price";
    $tables['tables'][$table_name]["currency-symbol"] = $lbl_currency_symbol;
    $tables['tables'][$table_name]["languages"]['retrieval_error_title'] = $lbl_retrieval_error_title;
    $tables['tables'][$table_name]["languages"]['retrieval_error_content'] = $lbl_retrieval_error_content;
    $tables['tables'][$table_name]["languages"]['no_result_found'] = $lbl_no_result_found;
    $tables['tables'][$table_name]["languages"]['pull_for_refresh'] = $lbl_pull_for_refresh;
    $tables['tables'][$table_name]["languages"]['search'] = $lbl_search;

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "productID";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_id";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "id";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Thumbnail";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_thumbnail";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "images";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Thumbnail";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_thumbnail";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "images";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Name";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_name";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "heading-1";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Code";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_code";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "heading-2";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "false";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Description";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_desc";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "to_trusted";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";


    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "Slider";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_images";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "slidebox";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = $lbl_currency_symbol." [txt]";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_price";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "text";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "[txt]";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "product_cat";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "text";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    //$tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/tables.product.json',json_encode($tables));

    $z = 0;
    $data_tables[$z]['cat_id'] = $z;
    $data_tables[$z]['cat_name'] = 'Kue Kering';
    $data_tables[$z]['cat_image'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-keju-wijen-1.jpg';
    $data_tables[$z]['cat_desc'] = 'Aneka kue kering';

    $z++;
    $data_tables[$z]['cat_id'] = $z;
    $data_tables[$z]['cat_name'] = 'Kue Basah';
    $data_tables[$z]['cat_image'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-talam-labu-1.jpg';
    $data_tables[$z]['cat_desc'] = 'Aneka kue-kue basah';

    $z++;
    $data_tables[$z]['cat_id'] = $z;
    $data_tables[$z]['cat_name'] = 'Kue Ultah';
    $data_tables[$z]['cat_image'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-ultah-1.jpg';
    $data_tables[$z]['cat_desc'] = 'Kue-kue untuk perayaan ulang tahun';

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/tables/categorie.json',json_encode($data_tables));
    file_put_contents('output/'.$_SESSION['FILE_NAME'].'/www/data/tables/categorie.json',json_encode($data_tables));

    $data_tables = null;
    $z = -1;


    $z++;
    $data_tables[$z]['product_id'] = $z;
    $data_tables[$z]['product_name'] = 'Kue Keju Wijen';
    $data_tables[$z]['product_code'] = 'keju-wijen';
    $data_tables[$z]['product_thumbnail'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-keju-wijen-1.jpg';
    $data_tables[$z]['product_desc'] = 'Kue Kering untuk hari Raya Idul Fitri yang rasanya lembut dan enak. Bentuknya yang seperti buah, bernetuk bulat bulat kecil sehingga memudahkan untuk di nikmati. Bahan utama kue kering ini adalah tepung terigu dengan kualitas baik untuk menghasilkan kue yang enak.';
    $data_tables[$z]['product_images'] = '<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-keju-wijen-2.jpg" />|<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-keju-wijen-1.jpg" />';
    $data_tables[$z]['product_price'] = '25000';
    $data_tables[$z]['product_cat'] = '0';

    $z++;
    $data_tables[$z]['product_id'] = $z;
    $data_tables[$z]['product_name'] = 'Kue Talam Labu';
    $data_tables[$z]['product_code'] = 'talam-labu';
    $data_tables[$z]['product_thumbnail'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-talam-labu-1.jpg';
    $data_tables[$z]['product_desc'] = 'Variasi lain dari kue talam ubi adalah kue talam labu yang juga sama-sama menyajikan tekstur yang lezat di lidah dan menyesuaikan selera anda, pastinya';
    $data_tables[$z]['product_images'] = '<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-talam-labu-2.jpg" />|<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-talam-labu-1.jpg" />';
    $data_tables[$z]['product_price'] = '25000';
    $data_tables[$z]['product_cat'] = '1';

    $z++;
    $data_tables[$z]['product_id'] = $z;
    $data_tables[$z]['product_name'] = 'Kue Sarang Semut';
    $data_tables[$z]['product_code'] = 'sarang-semut';
    $data_tables[$z]['product_thumbnail'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-sarang-semut-1.jpg';
    $data_tables[$z]['product_desc'] = 'Kue sarang semut adalah kue panggang yang bentuknya mirip dengan kue bolu. Uniknya lagi kue ini juga mirip sekali dengan sarang semut';
    $data_tables[$z]['product_images'] = '<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-sarang-semut-2.jpg" />|<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-sarang-semut-1.jpg" />';
    $data_tables[$z]['product_price'] = '35000';
    $data_tables[$z]['product_cat'] = '1';

    $z++;
    $data_tables[$z]['product_id'] = $z;
    $data_tables[$z]['product_name'] = 'Kue Dadar Gulung';
    $data_tables[$z]['product_code'] = 'dadar-gulung';
    $data_tables[$z]['product_thumbnail'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-dadar-gulung-1.jpg';
    $data_tables[$z]['product_desc'] = 'merupakan penganan khas Indonesia dan Malaysia yang dapat digolongkan sebagai pancake yang diisi dengan parutan kelapa yang dicampur dengan gula jawa cair. Isi ini disebut inti.';
    $data_tables[$z]['product_images'] = '<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-dadar-gulung-2.jpg" />|<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-dadar-gulung-1.jpg" />';
    $data_tables[$z]['product_price'] = '45000';
    $data_tables[$z]['product_cat'] = '1';

    $z++;
    $data_tables[$z]['product_id'] = $z;
    $data_tables[$z]['product_name'] = 'Kue Ulang Tahun';
    $data_tables[$z]['product_code'] = 'ultah';
    $data_tables[$z]['product_thumbnail'] = '../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-ultah-1.jpg';
    $data_tables[$z]['product_desc'] = 'Kue untuk perayaan ulang tahun';
    $data_tables[$z]['product_images'] = '<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-ultah-2.jpg" />|<img src="../../../system/includes/page-builder/eazy_setup_ecommerce/products/kue-ultah-1.jpg" />';
    $data_tables[$z]['product_price'] = '45000';
    $data_tables[$z]['product_cat'] = '2';

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/tables/product.json',json_encode($data_tables));
    file_put_contents('output/'.$_SESSION['FILE_NAME'].'/www/data/tables/product.json',json_encode($data_tables));


    // TODO: PAGE - DASHBOARD
    $page_dashboard['page'][0]['menutype'] = 'tabs-custom';
    $page_dashboard['page'][0]['prefix'] = "dashboard";
    $page_dashboard['page'][0]['title'] = "Dashboard";
    $page_dashboard['page'][0]['menu'] = $_SESSION['PROJECT']['app']['prefix'];
    $page_dashboard['page'][0]['for'] = '-';
    $page_dashboard['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_dashboard['page'][0]['lock'] = $_lock_the_page;
    $page_dashboard['page'][0]['css'] = '';
    $page_dashboard['page'][0]['img_bg'] = $background;
    $page_dashboard['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_dashboard['page'][0]['content'] = '

   
<!-- code slide hero -->
<div class="assertive-900-bg slide-box-hero" ng-controller="productsCtrl">
	<ion-slides class="slide-box-hero-content" options="{slidesPerView:1,autoplay:10000,loop:1}" slider="data.slider">
		<ion-slide-page class="slide-box-hero-item" ng-repeat="item in data_products | limitTo : 10:0" >
		<div class="slide-box-hero-container" style="background: url(\'{{item.product_thumbnail}}\') no-repeat center center;">
			<div class="padding caption">
				<h2 ng-bind-html="item.product_name | strHTML"></h2>
				<a ng-href="#/'.$file_name.'/product_singles/{{item.product_id}}">>> more</a>
			</div>
		</div>
		</ion-slide-page>
	</ion-slides>
</div>
<!-- ./code slide hero -->

<!-- listing categories -->
<a ng-href="#/'.$file_name.'/categories" class="tags-heroes-title light-bg dark">'.$lbl_categories.' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="light-bg" ng-controller="categoriesCtrl">
	<div class="tags-heroes-content list">
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:0" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="#/'.$file_name.'/products/{{item.cat_id}}" class="button button-small button-full ink" ng-class="{\'button-assertive\' : $index}">{{item.cat_name}}</a></div>
		</div>
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:2" class="col" ng-class="$index ? \'col-66\':\'col-33\'" ><a href="#/'.$file_name.'/products/{{item.cat_id}}" class="button button-small button-full ink" ng-class="$index ? \'button-stable\' : \'button-energized\'" >{{item.cat_name}}</a></div>
		</div>
		<div class="row">
			<div ng-repeat="item in data_categories | limitTo:2:4" class="col" ng-class="$index ? \'col-33\':\'col-67\'" ><a href="#/'.$file_name.'/products/{{item.cat_id}}" class="button button-small button-full ink" ng-class="{\'button-royal\' : $index}">{{item.cat_name}}</a></div>
		</div>
	</div>
</div>
<!-- ./listing categories -->


<!-- listing products -->
<a ng-href="#/'.$file_name.'/products/-1" class="tags-heroes-title light-bg dark">'.$lbl_products.' <i class="pull-right icon ion-chevron-right"></i></a>
<div class="light-bg" ng-init="var_products={}">
    <div ng-controller="productsCtrl">
        <span ng-repeat="item_product in data_products" ng-init="var_products[$index]=item_product"></span>
    </div>

    <div ng-repeat="item in var_products | limitTo : 16:0">
        <a class="item item-thumbnail-left" href="#/'.$file_name.'/product_singles/{{ item.product_id }}">
            <img ng-src="{{ item.product_thumbnail }}">
            <h2 ng-bind-html="item.product_name | strHTML"></h2>
            <span class="calm">{{ item.product_price | currency:"'.$woo_currency.'":2 }}</span> 
        </a>
    </div>
 
</div>
<!-- ./listing products -->

<div class="dark-bg stable">
       <div class="padding text-center">&copy '.($_SESSION['PROJECT']['app']['company']).', '.date("Y").'</div> 
</div>

<br/>
<br/> 
<br/>
<br/> 
<br/>

       
    ';
    file_put_contents(JSM_PATH.'/projects/'.$file_name.'/page.dashboard.json',json_encode($page_dashboard));


    // TODO: PAGE - CATEGORIES
    $page_posts = null;
    $var = 'categories';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    $page_posts['page'][0]['table-code']['url_detail'] = '#/'.$_SESSION['FILE_NAME'].'/products/{{item.cat_id}}';
    $page_posts['page'][0]['table-code']['url_list'] = '#/'.$_SESSION['FILE_NAME'].'/products';
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
    $page_posts['page'][0]['title'] = ($lbl_categories);

    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['js'] = '';
    $page_posts['page'][0]['for'] = 'table-list';
    $page_posts['page'][0]['content'] = '
    
<!-- code refresh -->
<ion-refresher pulling-text="'.$lbl_pull_for_refresh.'"  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_categories" placeholder="'.$lbl_search.'" aria-label="filter categories" />
	</div>
</ion-list>
<!-- ./code search -->


<!-- code listing -->
<div class="card list animate-none">
	<a class="item item-thumbnail-left item-text-wrap" ng-repeat="item in categories | filter:filter_categories as results" ng-init="$last ? fireEvent() : null" href="#/'.$_SESSION['FILE_NAME'].'/products/{{item.'.$base_link.'}}">
		<img alt="" class="full-image" ng-src="{{item.cat_image}}" />
		<h3 class=""  ng-bind-html="item.cat_name | to_trusted"></h3>
		<p>{{item.cat_desc}}</p>
	</a>
    
</div>
<!-- ./code listing -->


<!-- code infinite scroll -->
<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
</ion-list>
<!-- ./code infinite scroll -->


<!-- code search result not found -->
<ion-list class="card list" ng-if="results.length == 0">
	<div class="item" ng-if="results.length == 0" >
		<p>'.$lbl_no_result_found.'</p>
	</div>
</ion-list>
<!-- code search result not found -->
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

';

    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    // TODO: PAGE - PRODUCTS
    $page_posts = null;
    $var = 'products';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'];
    $page_posts['page'][0]['title'] = ($lbl_products);

    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['js'] = '';
    $page_posts['page'][0]['for'] = 'table-list';
    $page_posts['page'][0]['query'][0] = 'product_cat';
    $page_posts['page'][0]['query_value'] = '-1';
    $page_posts['page'][0]['db_url_dinamic'] = 'on';
    $page_posts['page'][0]['table-code']['url_detail'] = '#/'.$_SESSION['FILE_NAME'].'/product_singles/{{item.product_id}}';
    $page_posts['page'][0]['table-code']['url_list'] = '#/'.$_SESSION['FILE_NAME'].'/products';
    $page_posts['page'][0]['content'] = '
     
<!-- code refresh -->
<ion-refresher pulling-text="'.$lbl_pull_for_refresh.'"  on-refresh="doRefresh()"></ion-refresher>
<!-- ./code refresh -->


<!-- code search -->
<ion-list class="card list" >
	<div class="item item-input">
		<i class="icon ion-search placeholder-icon"></i>
		<input type="search" ng-model="filter_products" placeholder="'.$lbl_search.'" aria-label="filter products" />
	</div>
</ion-list>
<!-- ./code search -->


<!-- code listing -->
<div class="card list animate-none">
	<a class="item item-thumbnail-left item-text-wrap" ng-repeat="item in products | filter: first_param | filter:filter_products as results" ng-init="$last ? fireEvent() : null" ng-href="#/'.$_SESSION['FILE_NAME'].'/product_singles/{{item.product_id}}">
		<img alt="" class="full-image" ng-src="{{item.product_thumbnail}}" />
		<h3 ng-bind-html="item.product_name | to_trusted"></h3>
		<p>{{ item.product_price | currency:"'.$lbl_currency_symbol.'":2 }}</p>
	</a>
</div>
<!-- ./code listing -->


<!-- code infinite scroll -->
<ion-list class="list">
	<ion-infinite-scroll ng-if="!noMoreItemsAvailable" on-infinite="onInfinite()" distance="5px" ng-if="hasMoreData"></ion-infinite-scroll>
</ion-list>
<!-- ./code infinite scroll -->


<!-- code search result not found -->
<ion-list class="card list" ng-if="results.length == 0" >
	<div class="item" ng-if="results.length == 0" >
		<p>'.$lbl_no_result_found.'</p>
	</div>
</ion-list>
<!-- code search result not found -->



<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
    
';

    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    // TODO: PAGE - PRODUCT_SINGLES
    $page_posts = null;
    $var = 'product_singles';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = 'products';
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = 'sub-tabs';
    $page_posts['page'][0]['title'] = '{{ product.product_name }}';
    $page_posts['page'][0]['table-code']['url_detail'] = '';
    $page_posts['page'][0]['table-code']['url_list'] = '';
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = 'false';
    $page_posts['page'][0]['cache'] = 'false';
    $page_posts['page'][0]['js'] = '$ionicConfig.backButton.text("");';
    $page_posts['page'][0]['for'] = 'table-item';
    $page_posts['page'][0]['query'][0] = 'product_id';

    $page_posts['page'][0]['content'] = '
<ion-refresher pulling-text="Pull to refresh..." on-refresh="doRefresh()"></ion-refresher>
<!--
<div class="tabs tabs-energized-900 stable tabs-icon-top static">
    <a ng-click="$ionicGoBack()" class="tab-item"><i class="icon ion-ios-albums-outline"></i> {{ \'List\' | translate }}</a>
    <a class="tab-item tab-item-active active"><i class="icon ion-ios-list-outline"></i> {{ \'Detail\' | translate }}</a>
</div>
--!>

<div class="list card">

	<!-- images -->
	<div class="item noborder"><img class="full-image" ng-src="{{product.product_thumbnail}}"   zoom-view="true" zoom-src="{{product.product_thumbnail}}" /></div>
	<!-- ./images -->

	<!-- heading-1 -->
	<div class="item item-divider"  ng-bind-html="product.product_name | to_trusted">{{product.product_name}}</div>
	<!-- ./heading-1 -->

	<!-- to_trusted -->
	<div class="item item-text-wrap noborder to_trusted" ng-bind-html="product.product_desc | strHTML"></div>
	<!-- ./to_trusted -->

	<!-- slidebox -->
	<div class="item item-text-wrap noborder to_trusted">
	<div class="slideshow_container to_trusted" ng-if="product.product_images" >
		<ion-slides options="{slidesPerView:1}" slider="data.slider" class="slideshow">
			<ion-slide-page class="slideshow-item" ng-repeat="slide_item in product.product_images | strExplode:\'|\' track by $index" >
				<div class="item-text-wrap" ng-bind-html="slide_item | to_trusted"></div>
			</ion-slide-page>
		</ion-slides>
	</div>
	</div>
	<!-- ./slidebox -->

	<!-- text -->
	<div class="item"><span class="subdued" >{{ product.product_price | currency:"'.$lbl_currency_symbol.'":2 }}</span></div>
	<!-- ./text -->
</div>

<div class="card"> 
    <div class="button-bar"> 
    	<a class="button button-small button-energized-900 ion-android-cart" ng-href="#/'.$_SESSION['FILE_NAME'].'/product_cart">
         {{ \''.$lbl_carts.'\' | translate }} <span ng-show="item_in_virtual_table_product">( {{ item_in_virtual_table_product  }} )</span>
        </a>
        
        <a class="button button-small button-energized-900 ion-android-add-circle" ng-click="addToDbVirtual(product);">
         {{ \''.$lbl_add_to_cart.'\' | translate }}
        </a>
    </div>
</div>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

  ';


    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    // TODO: PAGE - PRODUCT_CART
    $page_posts = null;
    $var = 'product_cart';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = '';
    $page_posts['page'][0]['parent'] = 'products';
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = 'tabs-custom';
    $page_posts['page'][0]['title'] = ($lbl_carts);
    $page_posts['page'][0]['table-code']['url_detail'] = '';
    $page_posts['page'][0]['table-code']['url_list'] = '';
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['cache'] = 'false';
    $page_posts['page'][0]['last_edit_by'] = 'table (product)';
    $page_posts['page'][0]['css'] = null;
    $page_posts['page'][0]['css'] .= '.product_cart{margin-top: 50%;}'."\r\n";
    $page_posts['page'][0]['css'] .= '.product_cart .icon:before{font-size: 72px;font-weight: 600;}'."\r\n";
    $page_posts['page'][0]['for'] = 'table-bookmarks';
    $page_posts['page'][0]['content'] = '
<!-- shopping cart -->
	<div ng-if="product_cart.length != 0">
		<!-- items -->
		<div class="list" ng-init="product_order={}" >
			<div class="card" ng-repeat="item in product_cart" ng-init="product_order[$index]=item">
				<div class="item item-thumbnail-left item-button-right noborder">
					<img ng-src="{{ item.product_thumbnail }}" />
					<h2 class="" ng-bind-html="item.product_name | to_trusted"></h2>
					<span>{{ item._sum | currency:"'.$lbl_currency_symbol.'":2 }}</span>
					<input type="number" min="1" ng-change="updateDbVirtual()" ng-model="product_order[$index][\'_qty\']" />
					<button class="button button-small button-assertive button-outline" ng-click="removeDbVirtualProduct(item.product_id)"><i class="icon ion-trash-a"></i></button>
				</div>
			</div>
		</div>
		<!-- ./items -->

		<!-- totals -->
		<div class="list">
			<div class="item text-right">
				<h2>{{ product_cost | currency:"'.$lbl_currency_symbol.'":2 }}</h2>
			</div>
		</div>
		<!-- ./totals -->

		<!-- buttons -->
		<div class="list card">
			<div class="item tabs tabs-secondary tabs-icon-top tabs-stable">
				<a class="tab-item" ng-click="clearDbVirtualProduct();"><i class="icon ion-trash-a"></i> {{ \'Clear\' | translate }}</a>
				<a class="tab-item" ng-click="gotoCheckout(product_cart)"><i class="icon ion-cash"></i> {{ \'Go To Checkout\' | translate }}</a>
			</div>
		</div>
		<!-- ./buttons -->
	</div>
<!-- ./shopping cart -->

<!-- no items -->
	<div class="product_cart padding text-center" ng-if="product_cart.length == 0">
		<i class="icon ion-ios-cart-outline"></i>
		<p>{{ \''.$lbl_no_items.'\' | translate }}</p>
	</div>
<!-- ./no items -->

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
 
';
    $page_posts['page'][0]['js'] = '
$rootScope.order_now = "";
$scope.gotoCheckout = function(items){
   var list_item = "";
   angular.forEach(items, function(item, key) {
       list_item += "* " + item.product_code + " ( " + item._qty + " x " + item.product_price + " = " + item._sum + ")" + "\\r\\n" ; 
   });
  $rootScope.order_now = list_item;
  $state.go("'.$_SESSION['FILE_NAME'].'.form_order");
}

';
    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    // TODO: PAGE - HOW_TO_ORDER
    $page_posts = null;
    $var = 'how_to_order';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = 'tabs';
    //$page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['menutype'] = 'tabs';
    $page_posts['page'][0]['title'] = ($lbl_how_to_order);
    $page_posts['page'][0]['table-code']['url_detail'] = '';
    $page_posts['page'][0]['table-code']['url_list'] = '';
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['js'] = '';
    $page_posts['page'][0]['cache'] = 'false';
    $page_posts['page'][0]['last_edit_by'] = 'menu';
    $page_posts['page'][0]['css'] = null;
    $page_posts['page'][0]['for'] = '-';
    $page_posts['page'][0]['content'] = '<div class="padding">'.$how_to_order.'</div>';

    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    // TODO: TABLE - ORDER
    $tables = null;
    $table_name = 'order';
    $table_index = -1;
    $tables['tables'][$table_name]["parent"] = 'order';
    $tables['tables'][$table_name]["title"] = $table_name;
    $tables['tables'][$table_name]["prefix"] = $table_name;
    $tables['tables'][$table_name]["template"] = "";
    $tables['tables'][$table_name]["template_single"] = "";
    $tables['tables'][$table_name]["itemtype"] = "item";
    $tables['tables'][$table_name]["itemcolor"] = "colorful";
    $tables['tables'][$table_name]["db_type"] = "offline";
    $tables['tables'][$table_name]["db_var"] = "";
    $tables['tables'][$table_name]["db_var_single"] = "";
    $tables['tables'][$table_name]["db_url"] = "";
    $tables['tables'][$table_name]["db_url_single"] = "";
    $tables['tables'][$table_name]["db_url_dinamic"] = "";
    $tables['tables'][$table_name]["items_focus"] = "scroll";
    $tables['tables'][$table_name]["max_items"] = "50";
    $tables['tables'][$table_name]["fetch_per_scroll"] = "1";
    $tables['tables'][$table_name]["icon"] = "ion-social-buffer";
    $tables['tables'][$table_name]["relation_to"] = "";
    $tables['tables'][$table_name]["localstorage"] = "localforage";
    $tables['tables'][$table_name]["motions"] = "none";
    $tables['tables'][$table_name]["bookmarks"] = "cart";
    $tables['tables'][$table_name]["column-for-price"] = "product_price";
    $tables['tables'][$table_name]["currency-symbol"] = $lbl_currency_symbol;
    $tables['tables'][$table_name]["languages"]['retrieval_error_title'] = $lbl_retrieval_error_title;
    $tables['tables'][$table_name]["languages"]['retrieval_error_content'] = $lbl_retrieval_error_content;
    $tables['tables'][$table_name]["languages"]['no_result_found'] = $lbl_no_result_found;
    $tables['tables'][$table_name]["languages"]['pull_for_refresh'] = $lbl_pull_for_refresh;
    $tables['tables'][$table_name]["languages"]['search'] = $lbl_search;

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "orderID";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "order_id";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "id";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "orderName";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "order_name";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "heading-1";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "orderPhone";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "order_phone";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "text";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "orderAddress";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "order_address";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "to_trusted";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    $table_index++;
    $tables['tables'][$table_name]["cols"][$table_index]["label"] = "orderContent";
    $tables['tables'][$table_name]["cols"][$table_index]["title"] = "order_content";
    $tables['tables'][$table_name]["cols"][$table_index]["type"] = "to_trusted";
    $tables['tables'][$table_name]["cols"][$table_index]["page_list"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["page_detail"] = "true";
    $tables['tables'][$table_name]["cols"][$table_index]["json"] = "true";

    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/tables.order.json',json_encode($tables));

    // TODO: --------------------------------------
    @unlink(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.form_order.json');
    if(file_exists('projects/'.$_SESSION['FILE_NAME'].'/php_sql.json'))
    {
        @unlink('projects/'.$_SESSION['FILE_NAME'].'/php_sql.json');
        @unlink('projects/'.$_SESSION['FILE_NAME'].'/php_sql_config.json');
        @unlink('projects/'.$_SESSION['FILE_NAME'].'/forms.order.json');
    }

    switch($order_via)
    {
        case 'whatsapp':

            // TODO: WA - PAGE - ORDER
            $page_posts = null;
            $var = 'form_order';
            $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $page_posts['page'][0]['prefix'] = $var;
            $page_posts['page'][0]['title'] = ($lbl_order);
            $page_posts['page'][0]['img_bg'] = '';
            $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
            $page_posts['page'][0]['lock'] = true;
            $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
            $page_posts['page'][0]['scroll'] = true;
            $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
            $page_posts['page'][0]['css'] = '#form_order .list .item {border: 0;}';
            $page_posts['page'][0]['for'] = '';
            $page_posts['page'][0]['cache'] = 'false';
            $page_posts['page'][0]['content'] = '    
            
                <div class="list list" >
 
                <form ng-submit="submitOrder(form_order)" >
                
                    <!-- input order_name -->
                    <label class="item item-input item-stacked-label" >
                        <span class="input-label">Name</span>
                        <input type="text" ng-model="form_order.order_name" placeholder="Regel" ng-required="true" ng-bind-html="form_order.order_name=data_user.fullname"/>
                    </label>
                    <!-- ./input order_name -->
                
                <!-- input order_phone -->
                <label class="item item-input item-stacked-label" >
                    <span class="input-label">Phone / Email</span>
                    <input type="text" ng-model="form_order.order_phone" placeholder="" ng-required="true" ng-bind-html="form_order.order_phone=data_user.phone" />
                </label>
                <!-- ./input order_phone -->
                
                <!-- input order_address -->
                <label class="item item-input item-stacked-label" >
                    <span class="input-label">Address</span>
                    <input type="text" ng-model="form_order.order_address" placeholder="" ng-required="true" ng-bind-html="form_order.order_address=data_user.address" />
                </label>
                <!-- ./input order_address -->
                
                <!-- input order_content -->
                <label ng-show="false" class="item item-input item-stacked-label" ng-init="form_order.order_content = order_now">
                    <span class="input-label">Content</span>
                    <textarea ng-model="form_order.order_content" placeholder="Content"></textarea>
                </label>
                <!-- ./input order_content -->
                
                <!-- input buy_now -->
                <div class="item item-text-wrap noborder">
                    <button class="button button-small button-assertive ink" ng-click="submitOrder(form_order)" >Buy Now</button>
                    <a class="button button-small button-calm ink" href="#/'.$_SESSION['FILE_NAME'].'/form_user" >Edit</a>
                </div>
                <!-- ./input buy_now -->
                
                </form>
                
                </div>
                ';
            $page_posts['page'][0]['js'] = '
            
  
            
            $scope.data_user = {};
            $scope.show_form = true ;
            
            $scope.$on("$ionicView.afterEnter", function (){  
                   $ionicLoading.show();
                   localforage.getItem("data_user_session", function(err, data_user_session){
                        if(data_user_session === null){
            			    $scope.show_form = true ;
            		    }else{
            		        $scope.show_form = false ; 
                            var data_user = JSON.parse(data_user_session);
                            $scope.data_user = data_user ;
                            $rootScope.data_user = data_user ;
                            
                        }
                   }).then(function(data_user_session){ 
                 		$timeout(function() {
                    			$ionicLoading.hide();
                    	},500);
                   }).catch(function(err){
            	       $scope.show_form = true ;
                  		$timeout(function() {
                    		$ionicLoading.hide();
                    	}, 500);
                        console.log(err);
            	   })
            });    
                           
                $scope.submitOrder = function(form_order){
                    var phoneNumber = "'.$contact.'";
                    var textMessage = "";
                    
                    textMessage += form_order.order_name + " (" + form_order.order_phone +  ")" + "\\r\\n" ;
                    textMessage += form_order.order_address + "\\r\\n" ;
                    textMessage += "orders:" + "\\r\\n" ;
                    textMessage += form_order.order_content + "\\r\\n" ;
                    
                    //alert(textMessage);
                    var urlSchema = "whatsapp://send?phone=" + phoneNumber + "&text=" + window.encodeURIComponent(textMessage);
                    
                    window.open(urlSchema,"_system","location=yes");
                }
                ';
            file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));

            break;
        case 'sms':

            // TODO: SMS - PAGE - ORDER
            $page_posts = null;
            $var = 'form_order';
            $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $page_posts['page'][0]['prefix'] = $var;
            $page_posts['page'][0]['title'] = ($lbl_order);
            $page_posts['page'][0]['img_bg'] = '';
            $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
            $page_posts['page'][0]['lock'] = true;
            $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
            $page_posts['page'][0]['scroll'] = true;
            $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
            $page_posts['page'][0]['css'] = '#form_order .list .item {border: 0;}';
            $page_posts['page'][0]['for'] = '';
            $page_posts['page'][0]['cache'] = 'false';
            $page_posts['page'][0]['content'] = '    
            
                <div class="list list" >
 
                <form ng-submit="submitOrder(form_order)" >
                
                    <!-- input order_name -->
                    <label class="item item-input item-stacked-label" >
                        <span class="input-label">Name</span>
                        <input type="text" ng-model="form_order.order_name" placeholder="Regel" ng-required="true" ng-bind-html="form_order.order_name=data_user.fullname"/>
                    </label>
                    <!-- ./input order_name -->
                
                <!-- input order_phone -->
                <label class="item item-input item-stacked-label" >
                    <span class="input-label">Phone / Email</span>
                    <input type="text" ng-model="form_order.order_phone" placeholder="" ng-required="true" ng-bind-html="form_order.order_phone=data_user.phone" />
                </label>
                <!-- ./input order_phone -->
                
                <!-- input order_address -->
                <label class="item item-input item-stacked-label" >
                    <span class="input-label">Address</span>
                    <input type="text" ng-model="form_order.order_address" placeholder="" ng-required="true" ng-bind-html="form_order.order_address=data_user.address" />
                </label>
                <!-- ./input order_address -->
                
                <!-- input order_content -->
                <label ng-show="false" class="item item-input item-stacked-label" ng-init="form_order.order_content = order_now">
                    <span class="input-label">Content</span>
                    <textarea ng-model="form_order.order_content" placeholder="Content"></textarea>
                </label>
                <!-- ./input order_content -->
                
                <!-- input buy_now -->
                <div class="item item-text-wrap noborder">
                    <button class="button button-small button-assertive ink" ng-click="submitOrder(form_order)" >Buy Now</button>
                    <a class="button button-small button-calm ink" href="#/'.$_SESSION['FILE_NAME'].'/form_user" >Edit</a>
                </div>
                <!-- ./input buy_now -->
                
                </form>
                
                </div>
                ';
            $page_posts['page'][0]['js'] = '
            
  
            
                $scope.data_user = {};
                $scope.show_form = true ;
                
                $scope.$on("$ionicView.afterEnter", function (){  
                       $ionicLoading.show();
                       localforage.getItem("data_user_session", function(err, data_user_session){
                            if(data_user_session === null){
                			    $scope.show_form = true ;
                		    }else{
                		        $scope.show_form = false ; 
                                var data_user = JSON.parse(data_user_session);
                                $scope.data_user = data_user ;
                                $rootScope.data_user = data_user ;
                                
                            }
                       }).then(function(data_user_session){ 
                     		$timeout(function() {
                        			$ionicLoading.hide();
                        	},500);
                       }).catch(function(err){
                	       $scope.show_form = true ;
                      		$timeout(function() {
                        		$ionicLoading.hide();
                        	}, 500);
                            console.log(err);
                	   })
                });    
               
                $scope.submitOrder = function(form_order){
                    var phoneNumber = "'.$contact.'";
                    var textMessage = "";
                    
                    textMessage += form_order.order_name + " (" + form_order.order_phone +  ")" + "\\r\\n" ;
                    textMessage += form_order.order_address + "\\r\\n" ;
                    textMessage += "orders:" + "\\r\\n" ;
                    textMessage += form_order.order_content + "\\r\\n" ;
                    
                    //alert(textMessage);
                    
                    if (ionic.Platform.isIOS()){
                        var urlSchema = "sms:" + phoneNumber + ";?&body=" + window.encodeURIComponent(textMessage);
                    }else{
                        var urlSchema = "sms:" + phoneNumber + "?body=" + window.encodeURIComponent(textMessage);
                    }
                    window.open(urlSchema,"_system","location=yes");
                }
                ';
            file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));

            break;

        case 'email':
            // TODO: EMAIL - PAGE - ORDER
            $page_posts = null;
            $var = 'form_order';
            $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
            $page_posts['page'][0]['prefix'] = $var;
            $page_posts['page'][0]['title'] = ($lbl_order);
            $page_posts['page'][0]['img_bg'] = '';
            $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
            $page_posts['page'][0]['lock'] = true;
            $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
            $page_posts['page'][0]['scroll'] = true;
            $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
            $page_posts['page'][0]['css'] = '#form_order .list .item {border: 0;}';
            $page_posts['page'][0]['for'] = '';
            $page_posts['page'][0]['cache'] = 'false';
            $page_posts['page'][0]['content'] = '    
            
                <div class="list list">
                
                <form ng-submit="submitOrder(form_order)">
                
                    <!-- input order_name -->
                    <label class="item item-input item-stacked-label">
                        <span class="input-label">Name</span>
                        <input type="text" ng-model="form_order.order_name" name="order_name" placeholder="" ng-required="true" ng-bind-html="form_order.order_name=data_user.fullname"/>
                    </label>
                    <!-- ./input order_name -->
                
                <!-- input order_phone -->
                <label class="item item-input item-stacked-label">
                    <span class="input-label">Phone / Email</span>
                    <input type="text" ng-model="form_order.order_phone" name="order_phone" placeholder="" ng-required="true" ng-bind-html="form_order.order_phone=data_user.phone" />
                </label>
                <!-- ./input order_phone -->
                
                <!-- input order_address -->
                <label class="item item-input item-stacked-label">
                    <span class="input-label">Address</span>
                    <textarea ng-model="form_order.order_address" name="order_address" placeholder="Address" ng-required="true" ng-bind-html="form_order.order_address=data_user.address"></textarea>
                </label>
                <!-- ./input order_address -->
                
                <!-- input order_content -->
                <label  ng-show="false"  class="item item-input item-stacked-label" ng-init="form_order.order_content = order_now">
                    <span class="input-label">Content</span>
                    <textarea ng-model="form_order.order_content" name="order_content" placeholder="Content" ng-required="true"></textarea>
                </label>
                <!-- ./input order_content -->
                
                <!-- input buy_now -->
                <div class="item item-text-wrap noborder">
                    <button class="button button-small button-assertive ink" >Buy Now</button>
                    <a class="button button-small button-calm ink" href="#/'.$_SESSION['FILE_NAME'].'/form_user" >Edit</a>
                </div>
                <!-- ./input buy_now -->
                
                </form>
                
                </div>
                ';
            $page_posts['page'][0]['js'] = '
              
                
            $scope.form_order= {};
            $scope.data_user = {};
            $scope.show_form = true ;

            $scope.$on("$ionicView.afterEnter", function (){  
                   $ionicLoading.show();
                   localforage.getItem("data_user_session", function(err, data_user_session){
                        if(data_user_session === null){
            			    $scope.show_form = true ;
            		    }else{
            		        $scope.show_form = false ; 
                            var data_user = JSON.parse(data_user_session);
                            $scope.data_user = data_user ;
                            $rootScope.data_user = data_user ;
                            
                        }
                   }).then(function(data_user_session){ 
                 		$timeout(function() {
                    			$ionicLoading.hide();
                    	},500);
                   }).catch(function(err){
            	       $scope.show_form = true ;
                  		$timeout(function() {
                    		$ionicLoading.hide();
                    	}, 500);
                        console.log(err);
            	   })
            });    
                      
                      
                      
            	// TO'.'DO: form_orderCtrl --|-- $scope.submitOrder
                
            	$scope.submitOrder = function(form_order){

                    var textMessage = "";
                    
                    textMessage += form_order.order_name + " (Contact: " + form_order.order_phone +  ")" + "\\r\\n" ;
                    textMessage += form_order.order_address + "\\r\\n" ;
                    textMessage += "orders:" + "\\r\\n" ;
                    textMessage += form_order.order_content + "\\r\\n" ;
                                      
                    var data_order = {
                        feedback_email: "app@'.parse_url($mailer_url,PHP_URL_HOST).'",
                        feedback_name: form_order.order_name ,
                        feedback_message: textMessage
                    };
                   
            		// animation loading 
            		$ionicLoading.show();
            	
            		var $messages, $title = null;
                    
            		$http({
            				method:"POST",
            				url: "'.$mailer_url.'",
            				data: $httpParamSerializer(data_order),  // pass in data as strings
            				headers: {"Content-Type":"application/x-www-form-urlencoded"}  // set the headers so angular passing info as form data (not request payload)
            			})
            			.then(function(response) {
            				$messages = response.data.message;
            				$title = response.data.title;
            			},function(response){
            				$messages = response.statusText;
            				$title = response.status;
            			}).finally(function(){
            				// event done, hidden animation loading
            				$timeout(function() {
            					$ionicLoading.hide();
            					if($messages !== null){
           						 // message
            					var alertPopup = $ionicPopup.alert({
            						title: $title,
            						template: $messages,
            					});

                               
            					}
            			}, 500);
            		});
            	};
    
                ';
            file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));
            break;
        case 'backend':
            // TODO: BACKEND - PAGE - ORDER

            switch($backend)
            {
                case 'offline':


                    break;
                case 'php-sql-generator':
                    // TODO: PHP-SQL GEN -> TABLE - ORDER
                    // TODO: FORM - ORDER
                    $form_name = 'order';
                    $form_index = -1;
                    $forms['forms'][$form_name]['select'] = $form_name;
                    $forms['forms'][$form_name]['method'] = 'post';
                    $forms['forms'][$form_name]['table'] = $form_name;
                    $forms['forms'][$form_name]['title'] = $form_name;
                    $forms['forms'][$form_name]['action'] = $site_url.'?form=order&json=submit';
                    $forms['forms'][$form_name]['prefix'] = $form_name;
                    $forms['forms'][$form_name]['style'] = 'stacked';
                    $forms['forms'][$form_name]['msg_ok'] = $lbl_order_ok;
                    $forms['forms'][$form_name]['msg_error'] = $lbl_order_err;
                    $forms['forms'][$form_name]['layout'] = 'list';
                    $forms['forms'][$form_name]['version'] = '';

                    $form_index++;
                    $forms['forms'][$form_name]['input'][$form_index]['label'] = 'Name';
                    $forms['forms'][$form_name]['input'][$form_index]['name'] = 'order_name';
                    $forms['forms'][$form_name]['input'][$form_index]['type'] = 'text';
                    $forms['forms'][$form_name]['input'][$form_index]['placeholder'] = '';
                    $form_index++;
                    $forms['forms'][$form_name]['input'][$form_index]['label'] = 'Phone / Email';
                    $forms['forms'][$form_name]['input'][$form_index]['name'] = 'order_phone';
                    $forms['forms'][$form_name]['input'][$form_index]['type'] = 'text';
                    $forms['forms'][$form_name]['input'][$form_index]['placeholder'] = '';

                    $form_index++;
                    $forms['forms'][$form_name]['input'][$form_index]['label'] = 'Address';
                    $forms['forms'][$form_name]['input'][$form_index]['name'] = 'order_address';
                    $forms['forms'][$form_name]['input'][$form_index]['type'] = 'textarea';
                    $forms['forms'][$form_name]['input'][$form_index]['placeholder'] = '';

                    $form_index++;
                    $forms['forms'][$form_name]['input'][$form_index]['label'] = 'Content';
                    $forms['forms'][$form_name]['input'][$form_index]['name'] = 'order_content';
                    $forms['forms'][$form_name]['input'][$form_index]['type'] = 'textarea';
                    $forms['forms'][$form_name]['input'][$form_index]['placeholder'] = '';

                    $form_index++;
                    $forms['forms'][$form_name]['input'][$form_index]['label'] = 'Buy Now';
                    $forms['forms'][$form_name]['input'][$form_index]['name'] = 'buy_now';
                    $forms['forms'][$form_name]['input'][$form_index]['type'] = 'button';
                    $forms['forms'][$form_name]['input'][$form_index]['placeholder'] = '';

                    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/forms.'.$form_name.'.json',json_encode($forms));


                    // TODO: PAGE - ORDER
                    $page_posts = null;
                    $var = 'form_order';
                    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
                    $page_posts['page'][0]['prefix'] = $var;
                    $page_posts['page'][0]['title'] = ($lbl_order);
                    $page_posts['page'][0]['img_bg'] = '';
                    $page_posts['page'][0]['parent'] = $_SESSION['PROJECT']['menu']['type'];
                    //$page_posts['page'][0]['lock'] = true;
                    $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';

                    $page_posts['page'][0]['scroll'] = true;
                    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
                    $page_posts['page'][0]['css'] = '#form_order .list .item {border: 0;}';
                    $page_posts['page'][0]['for'] = 'forms';
                    $page_posts['page'][0]['cache'] = 'false';
                    $page_posts['page'][0]['content'] = '
      
      <div class="list list">
      <form ng-submit="submitOrder()">

      <!-- input order_name -->
      <label class="item item-input item-stacked-label">
      <span class="input-label">Name</span>
      <input type="text" ng-model="form_order.order_name" name="order_name" placeholder="" ng-required="true" ng-bind-html="form_order.order_name=data_user.fullname"/>
      </label>
      <!-- ./input order_name -->

      <!-- input order_phone -->
      <label class="item item-input item-stacked-label">
      <span class="input-label">Phone / Email</span>
      <input type="text" ng-model="form_order.order_phone" name="order_phone" placeholder="" ng-required="true" ng-bind-html="form_order.order_phone=data_user.phone"/>
      </label>
      <!-- ./input order_phone -->

      <!-- input order_address -->
      <label class="item item-input item-stacked-label">
      <span class="input-label">Address</span>
      <textarea ng-model="form_order.order_address" name="order_address" placeholder="Address" ng-required="true" ng-bind-html="form_order.order_address=data_user.address"></textarea>
      </label>
      <!-- ./input order_address -->
     
     <!-- input order_content -->
      <label ng-show="false" class="item item-input item-stacked-label" ng-init="form_order.order_content = order_now">
      <span class="input-label">Content</span>
      <textarea ng-model="form_order.order_content" name="order_content" placeholder="Content" ng-required="true"></textarea>
      </label>
      <!-- ./input order_content -->

      <!-- input buy_now -->
      <div class="item item-text-wrap noborder">
      <button class="button button-small button-assertive ink">Buy Now</button>
      <a class="button button-small button-calm ink" href="#/'.$_SESSION['FILE_NAME'].'/form_user" >Edit</a>
      </div>
      <!-- ./input buy_now -->
      </form>

      </div>
      
      
     ';
     
         $page_posts['page'][0]['js'] = '
             
            
                $scope.data_user = {};
                $scope.show_form = true ;
                
                $scope.$on("$ionicView.afterEnter", function (){  
                       $ionicLoading.show();
                       localforage.getItem("data_user_session", function(err, data_user_session){
                            if(data_user_session === null){
                			    $scope.show_form = true ;
                		    }else{
                		        $scope.show_form = false ; 
                                var data_user = JSON.parse(data_user_session);
                                $scope.data_user = data_user ;
                                $rootScope.data_user = data_user ;
                                
                            }
                       }).then(function(data_user_session){ 
                     		$timeout(function() {
                        			$ionicLoading.hide();
                        	},500);
                       }).catch(function(err){
                	       $scope.show_form = true ;
                      		$timeout(function() {
                        		$ionicLoading.hide();
                        	}, 500);
                            console.log(err);
                	   })
                });    
               
 
                ';
     
                    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


                    // TODO: PHP-SQL GEN -> SETTING BACKEND
                    $z = -1;
                    $z++;
                    $php_sql['php_sql'][$z]['name'] = 'categorie';
                    $php_sql['php_sql'][$z]['limit'] = '100';
                    $php_sql['php_sql'][$z]['auth'] = 'false';
                    $php_sql['php_sql'][$z]['owned-by-me'] = 'false';
                    $z++;
                    $php_sql['php_sql'][$z]['name'] = 'product';
                    $php_sql['php_sql'][$z]['limit'] = '100';
                    $php_sql['php_sql'][$z]['auth'] = 'false';
                    $php_sql['php_sql'][$z]['owned-by-me'] = 'false';
                    $z++;

                    $php_sql['php_sql'][$z]['name'] = 'order';
                    $php_sql['php_sql'][$z]['limit'] = '100';
                    $php_sql['php_sql'][$z]['auth'] = 'false';
                    $php_sql['php_sql'][$z]['owned-by-me'] = 'false';

                    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/php_sql.json',json_encode($php_sql));
                    $php_sql_config['php_sql_config']['host'] = 'localhost';
                    $php_sql_config['php_sql_config']['dbase'] = 'db_'.$_SESSION['FILE_NAME'];
                    $php_sql_config['php_sql_config']['url'] = $site_url.'';
                    file_put_contents('projects/'.$_SESSION['FILE_NAME'].'/php_sql_config.json',json_encode($php_sql_config));
                    break;

            }


            break;
    }


    $custom_js['js']['directives'] = '
.run(function($ionicPlatform,$ionicLoading,$timeout,$rootScope,$state) {
	$ionicPlatform.ready(function() {
		$ionicLoading.show();
		localforage.getItem("data_user_session", function(err, data_user_session) {
			if (data_user_session === null) {
			     $state.go("'.$_SESSION['FILE_NAME'].'.form_user");
			} else {
				var data_user = JSON.parse(data_user_session);
				$rootScope.data_user = data_user;                
			}
		}).then(function(data_user_session) {
            if (data_user_session === null) {
			     $state.go("'.$_SESSION['FILE_NAME'].'.form_user");
			}else {
				var data_user = JSON.parse(data_user_session);
				$rootScope.data_user = data_user;                
			}
			$timeout(function() {
				$ionicLoading.hide();
			}, 500);
		}).
		catch (function(err) {
			$timeout(function() {
				$ionicLoading.hide();
                $state.go("'.$_SESSION['FILE_NAME'].'.form_user");
			}, 500);
		});
                
	})
})';
    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/js.json',json_encode($custom_js));

    // TODO: PAGE - FORM_USER

    $page_posts = null;
    $var = 'form_user';
    $page_posts['page'][0]['builder_link'] = @$_SERVER["HTTP_REFERER"];
    $page_posts['page'][0]['prefix'] = $var;
    $page_posts['page'][0]['img_bg'] = $background;
    $page_posts['page'][0]['parent'] = $var;
    $page_posts['page'][0]['menutype'] = $_SESSION['PROJECT']['menu']['type'].'-custom';
    $page_posts['page'][0]['title'] = '';
    $page_posts['page'][0]['table-code']['url_detail'] = '';
    $page_posts['page'][0]['table-code']['url_list'] = '';
    $page_posts['page'][0]['scroll'] = true;
    $page_posts['page'][0]['menu'] = $_SESSION['FILE_NAME'];
    $page_posts['page'][0]['js'] = '';
    $page_posts['page'][0]['cache'] = 'false';
    $page_posts['page'][0]['last_edit_by'] = 'menu';
    $page_posts['page'][0]['css'] = null;
    $page_posts['page'][0]['for'] = '-';
    $page_posts['page'][0]['lock'] = true;
    $page_posts['page'][0]['header-shrink'] = true;
    $page_posts['page'][0]['button_up'] = 'none';
    $page_posts['page'][0]['remove-has-header'] = true;
    $page_posts['page'][0]['title-tranparant'] = true;
    $page_posts['page'][0]['css'] = '
.hero > .content h2{color: #fff;text-shadow: 0 1px 0px #000;}
.social-login { position: fixed; bottom: 0;}
.app-icon {background-color: #fff;background-size:cover !important; border-radius: 50%;height:80px;margin: 0 auto;width:80px;}
.item-md-radio { color: #fff; font-weight:600}  
    
    ';
    $page_posts['page'][0]['content'] = '
  
<div class="hero flat">
    <div class="content">
        <div class="app-icon" style="background: url(\''.str_replace('output/'.$_SESSION["PROJECT"]['app']['prefix'].'/www/','',$_SESSION['PROJECT']['menu']['logo']).'\') center;"></div>
        <h2>'.$_SESSION["PROJECT"]['app']['name'].'</h2>
    </div>
</div>

<div class="list">

  <label class="item item-input item-md-label">
    <span class="input-label">Name</span>
    <input class="md-input" type="text" ng-model="data_user.fullname" ng-value="data_user.fullname">
  </label>
  
 
   <label class="item item-input item-md-label">
    <span class="input-label">Phone Number</span>
    <input class="md-input" type="text" ng-model="data_user.phone" ng-value="data_user.phone">
  </label>
  
  <label class="item item-input item-md-label">
    <span class="input-label">Address</span>
    <input class="md-input" type="text" ng-model="data_user.address" ng-value="data_user.address">
  </label>

</div>  
  
<div class="padding">
    <button ng-click="updateData(data_user)" class="button button-full button-assertive ink">Save</button>
</div>   

<br/>
<br/>
<br/>
    ';
    $page_posts['page'][0]['js'] = '
    
$scope.data_user = {};
$scope.show_form = true ;

$scope.$on("$ionicView.afterEnter", function (){  
       $ionicLoading.show();
       localforage.getItem("data_user_session", function(err, data_user_session){
            if(data_user_session === null){
			    $scope.show_form = true ;
		    }else{
		        $scope.show_form = false ; 
                var data_user = JSON.parse(data_user_session);
                $scope.data_user = data_user ;
                $rootScope.data_user = data_user ;
            }
       }).then(function(data_user_session){ 
     		$timeout(function() {
        			$ionicLoading.hide();
        	},500);
       }).catch(function(err){
	       $scope.show_form = true ;
      		$timeout(function() {
        		$ionicLoading.hide();
        	}, 500);
	   })
}); 

    
$scope.updateData = function(form){
    if(angular.isDefined(form)){
        $ionicLoading.show();
		var fullname = form.fullname || "demo";
		var address = form.address || "demo";
        localforage.setItem("data_user_session", JSON.stringify(form));
    	$timeout(function(){
    	   $ionicLoading.hide();   
           
           var confirmPopup = $ionicPopup.confirm({
             title: "Successfully",
             template: "The data has been successfully saved."
           });
    	
            confirmPopup.then(function(res) {
             if(res) {
               $state.go("'.$file_name.'.products");
             } else {
                // cancel
             }
            });
   
           
        },500);  
    }
}    
';
    file_put_contents(JSM_PATH.'/projects/'.$_SESSION['FILE_NAME'].'/page.'.$var.'.json',json_encode($page_posts));


    buildIonic($file_name);
    header('Location: ./?page=x-page-builder&prefix=eazy_setup_ecommerce');
    die();
}

$raw_data = array();
if(file_exists('projects/'.$_SESSION['FILE_NAME'].'/page_builder.ecommerce.json'))
{
    $_raw_data = json_decode(file_get_contents('projects/'.$_SESSION['FILE_NAME'].'/page_builder.ecommerce.json'),true);
    $raw_data = $_raw_data['page_builder']['ecommerce'];
}

if(!isset($raw_data['label_categories']))
{
    $raw_data['label_categories'] = 'Kategori';
}
if(!isset($raw_data['label_carts']))
{
    $raw_data['label_carts'] = 'Keranjang Belanja';
}
if(!isset($raw_data['label_products']))
{
    $raw_data['label_products'] = 'Produk';
}
if(!isset($raw_data['label_how_to_order']))
{
    $raw_data['label_how_to_order'] = 'Cara Pesan?';
}
if(!isset($raw_data['label_retrieval_error_title']))
{
    $raw_data['label_retrieval_error_title'] = 'Jaringan Error';
}
if(!isset($raw_data['label_retrieval_error_content']))
{
    $raw_data['label_retrieval_error_content'] = 'Terjadi error ketika mengkoleksi data';
}
if(!isset($raw_data['label_currency_symbol']))
{
    $raw_data['label_currency_symbol'] = 'Rp.';
}
if(!isset($raw_data['background']))
{
    $raw_data['background'] = 'data/images/background/transparent.png';
}
if(!isset($raw_data['label_no_result_found']))
{
    $raw_data['label_no_result_found'] = 'Tidak ada ditemukan...';
}
if(!isset($raw_data['label_pull_for_refresh']))
{
    $raw_data['label_pull_for_refresh'] = 'Tarik untuk menyegarkan';
}
if(!isset($raw_data['label_search']))
{
    $raw_data['label_search'] = 'Cari';
}
if(!isset($raw_data['label_order']))
{
    $raw_data['label_order'] = 'Order';
}
if(!isset($raw_data['backend_used']))
{
    $raw_data['backend_used'] = 'offline';
}
if(!isset($raw_data['site_url']))
{
    $raw_data['site_url'] = 'http://anaski.net/';
}

if(!isset($raw_data['label_order_ok']))
{
    $raw_data['label_order_ok'] = 'Pesanan telah berhasil dikirimkan';
}

if(!isset($raw_data['label_order_err']))
{
    $raw_data['label_order_err'] = 'Pesanan gagal terkirim, silahkan coba lagi';
}

if(!isset($raw_data['order_via']))
{
    $raw_data['order_via'] = 'email';
}

if(!isset($raw_data['contact']))
{
    $raw_data['contact'] = '081234545465';
}

if(!isset($raw_data['label_no_items']))
{
    $raw_data['label_no_items'] = 'Belum ada item yang dipesan';
}

if(!isset($raw_data['label_add_to_cart']))
{
    $raw_data['label_add_to_cart'] = 'Masukan Ke Keranjang';
}
if(!isset($raw_data['label_goto_checkout']))
{
    $raw_data['label_goto_checkout'] = 'Checkout';
}


if(!isset($raw_data['how_to_order']))
{
    $raw_data['how_to_order'] = '
<h1>Ketentuan Bayar Ditempat (COD)</h1>
<ul>
    <li>Pesanan akan diantarkan hanya untuk daerah Kinali dan sekitar</li>
    <li>Untuk pesanan lebih dari Rp.100.000,-, kami perlu memastikan pesanan itu. Jadi silahkan bayar uang tanda jadi</li>
</ul>
';
}

$backend_used[] = array('label' => 'No Backend (Offline App)','value' => 'offline');
$backend_used[] = array('label' => 'PHP-SQL Generator (rest-api.php)','value' => 'php-sql-generator');
//$backend_used[] = array('label' => 'WordPress Plugin Generator', 'value' => 'wp-plugin-generator');

$t = 0;
foreach($backend_used as $backend)
{
    $_backend_used[$t] = $backend;
    if($raw_data['backend_used'] == $backend['value'])
    {
        $_backend_used[$t]['active'] = true;
    }
    $t++;
}


$form_input .= '<hr/>';

$helper = null;
switch($raw_data['backend_used'])
{
    case 'offline':
        $helper = '<p>Use <a target="_blank" href="./?page=z-json" class="">JSON Editor</a> or <a href="./?page=z-json-raw" class="" target="_blank">JSON Raw Editor</a> for editing product and categories</p>';
        break;
    case 'php-sql-generator':
        $helper = '<p>Use <a target="_blank" href="./?page=z-php-sql-restapi-generator" class="">PHP SQL REST-API Generator</a> as JSON and <a href="./?page=z-php-sql-web-admin-generator" class="" target="_blank">Web Admin Generator</a> as admin dashboard</p>';
        break;
}

$_order_via = array();
$order_via[] = array('label' => 'Web Admin (required: PHP-SQL Generator)','value' => 'backend');
$order_via[] = array('label' => 'Direct SMS','value' => 'sms');
$order_via[] = array('label' => 'Direct WhatsApp','value' => 'whatsapp');
$order_via[] = array('label' => 'PHP Mailer','value' => 'email');

$t = 0;
foreach($order_via as $via)
{
    $_order_via[$t] = $via;
    if($raw_data['order_via'] == $via['value'])
    {
        $_order_via[$t]['active'] = true;
    }
    $t++;
}

// TODO: LAYOUT

$form_input .= $bs->FormGroup('ecommerce[backend_used]','horizontal','select','Backend Used?',$_backend_used,$helper,null,'6');
$form_input .= $bs->FormGroup('ecommerce[site_url]','horizontal','text','Site URL for Backend','http://anaski.net/api/rest-api.php','url example: <code>http://ihsana.net/api/rest-api.php</code>',null,'6',$raw_data['site_url']);
$form_input .= $bs->FormGroup('ecommerce[order_via]','horizontal','select','Order Via?',$_order_via,'get information order from buyer via?',null,'6');
$form_input .= $bs->FormGroup('ecommerce[contact]','horizontal','text','Phone Number or Link PHP Mailer','http://ihsana.net/api/php-mailer.php','phone number for order via sms/whatsapp, or link your php mailer (download source: <a target="_blank" href="./?page=z-others&prefix=php-mailer.phps">source code php-mailer</a>)',null,'6',$raw_data['contact']);

$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('ecommerce[label_currency_symbol]','horizontal','text','Currency Symbol','$','',null,'6',$raw_data['label_currency_symbol']);
$form_input .= $bs->FormGroup('ecommerce[background]','horizontal','text','Background','data/images/background/transparent.png','','data-type="image-picker"','6',$raw_data['background']);
$form_input .= $bs->FormGroup('ecommerce[how_to_order]','horizontal','textarea','How To Order','','','data-type="html"','6',$raw_data['how_to_order']);


$form_input .= '<hr/>';
$form_input .= $bs->FormGroup('ecommerce[label_categories]','horizontal','text','Categories','Categories','',null,'6',$raw_data['label_categories']);
$form_input .= $bs->FormGroup('ecommerce[label_products]','horizontal','text','Products','Products','',null,'5',$raw_data['label_products']);
$form_input .= $bs->FormGroup('ecommerce[label_carts]','horizontal','text','Shopping Cart','Shopping Cart','',null,'6',$raw_data['label_carts']);
$form_input .= $bs->FormGroup('ecommerce[label_how_to_order]','horizontal','text','How to order?','How to order?','',null,'6',$raw_data['label_how_to_order']);
$form_input .= $bs->FormGroup('ecommerce[label_retrieval_error_title]','horizontal','text','Retrieval Error Title','Network Error','',null,'6',$raw_data['label_retrieval_error_title']);
$form_input .= $bs->FormGroup('ecommerce[label_retrieval_error_content]','horizontal','text','Retrieval Error Content','An error occurred while collecting data.','',null,'5',$raw_data['label_retrieval_error_content']);
$form_input .= $bs->FormGroup('ecommerce[label_no_result_found]','horizontal','text','No Result Found','No results found...!','',null,'6',$raw_data['label_no_result_found']);
$form_input .= $bs->FormGroup('ecommerce[label_pull_for_refresh]','horizontal','text','Pull for refresh','Pull to refresh...','',null,'6',$raw_data['label_pull_for_refresh']);
$form_input .= $bs->FormGroup('ecommerce[label_search]','horizontal','text','Search','Filter','',null,'5',$raw_data['label_search']);
$form_input .= $bs->FormGroup('ecommerce[label_order]','horizontal','text','Order','Order','',null,'5',$raw_data['label_order']);
$form_input .= $bs->FormGroup('ecommerce[label_order_ok]','horizontal','text','Order OK','Order has been successfully submitted','',null,'6',$raw_data['label_order_ok']);
$form_input .= $bs->FormGroup('ecommerce[label_order_err]','horizontal','text','Order Error','Order failed to sent, please try again!','',null,'5',$raw_data['label_order_err']);
$form_input .= $bs->FormGroup('ecommerce[label_no_items]','horizontal','text','Empty Order','There are no items!','',null,'5',$raw_data['label_no_items']);

$form_input .= $bs->FormGroup('ecommerce[label_add_to_cart]','horizontal','text','Add To Cart','Add To Cart','',null,'5',$raw_data['label_add_to_cart']);
$form_input .= $bs->FormGroup('ecommerce[label_goto_checkout]','horizontal','text','Check Out','Check Out','',null,'5',$raw_data['label_goto_checkout']);

$footer .= '
<script src="./templates/default/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector : "#ecommerce_how_to_order_",
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : "",
        
    });
</script>
';

?>