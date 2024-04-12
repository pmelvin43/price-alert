<?php
// PHP function to scrape product information from multiple URLs
function scrapeNeweggProductInfo($urls) {
    $products = array(); // Array to store product information

    foreach ($urls as $url) {
        // Initialize cURL session
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute cURL session
        $response = curl_exec($curl);
        // Close cURL session
        curl_close($curl);

        // Check if response is valid
        if ($response !== false) {
            // Create DOM Document
            $dom = new DOMDocument();
            // Load HTML content into the DOM Document
            @$dom->loadHTML($response);

            // Create XPath object to navigate the DOM Document
            $xpath = new DOMXPath($dom);

            // Scrape product information
            $productName = $xpath->query("//h1[@class='product-title']")->item(0)->nodeValue;
            $price = $xpath->query("//li[@class='price-current']//strong")->item(0)->nodeValue;

            // Scrape product image URL
            $imageURL = "";
            $imageNode = $xpath->query("//img[@class='product-view-img-original']")->item(0);
            if ($imageNode) {
                $imageURL = $imageNode->getAttribute('src');
            }

            // Add scraped data to the products array
            $products[] = array(
                'productName' => trim($productName),
                'price' => trim($price),
                'imageURL' => trim($imageURL),
                'url' => $url // Add the URL of the product
            );
        }
    }

    // Return the array of scraped products
    return $products;
}
?>
