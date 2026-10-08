<?php
    function productUrl($base, $overrides = []) {
        $params = array_merge($base, $overrides);
        
        foreach($params as $key => $value) {
            if ($value === null || $value === '' || ($key === 'minPrice') && (float)$value === 0.0) {
                unset($params[$key]);
            }
        }

        return '?' . http_build_query($params) . '#produtos';
    }

    function isNameMatching($name, $description, $category, $search) {
        if(!empty($search)){
            if(!str_contains($name, $search) &&
               !str_contains($description, $search) &&
               !str_contains($category, $search)) {
                    
               return false;
            }
        }
        return true;
    }

    function isCategoryMatching($chosenCategory, $productCategory) {
        if(!empty($chosenCategory) && $chosenCategory !== 'all'){
            if(mb_strtolower($chosenCategory, 'UTF-8') !== $productCategory){
                return false;
            }
        }
        return true;
    }

    function isWithinPriceRange($minPrice, $maxPrice, $productPrice) {
        if(!empty($minPrice) && $productPrice < (float)$minPrice){
            return false;
        }
        if(!empty($maxPrice) && $productPrice > (float)$maxPrice){
            return false;
        }

        return true;
    }

    $search   = trim($_GET['search']     ?? '');
    $category = trim($_GET['category'] ?? '');
    $minPrice = $_GET['minPrice'] ?? 0;
    $maxPrice = $_GET['maxPrice'] ?? null;

    if($minPrice < 0) $minPrice = 0;
    if(is_numeric($minPrice) && is_numeric($maxPrice)) {
        if($minPrice > $maxPrice) {
            $buffer = $minPrice;

            $minPrice = $maxPrice;
            $maxPrice = $buffer;
        }
    }
    
    $base = [
        'search'  => $search,
        'category' => $category,
        'minPrice' => $minPrice,
        'maxPrice' => $maxPrice,
    ];

    $allProducts = $db->getAllProducts();
 
    $filteredProducts = [];

    $lowerSearch   = mb_strtolower($search, 'UTF-8');
    foreach($allProducts as $product) {
        $nameMatch     = true;
        $categoryMatch = true;
        $priceMatch    = true; 

        $lowerName = mb_strtolower($product->getName(), 'UTF-8');
        $lowerDescription = mb_strtolower($product->getDescription(), 'UTF-8');
        $lowerCategory = mb_strtolower($product->getCategory(), 'UTF-8');
        $price    = $product->getPrice();

        $nameMatch = isNameMatching($lowerName, $lowerDescription, $lowerCategory, $lowerSearch);
        $categoryMatch = isCategoryMatching($category, $lowerCategory);
        $priceMatch = isWithinPriceRange($minPrice, $maxPrice, $price);

        if($nameMatch && $categoryMatch && $priceMatch){
            $filteredProducts[] = $product;
        }
    }
?>