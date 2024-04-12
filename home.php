<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <title>Price Alert</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
    <link rel="stylesheet" href="static/css/indexStyle.css" />

</head>

<body>
<header id="masthead">
    <div id="searchbar">
        <form action="search_newegg.php" method="get">
            <input type="text" id="search" name="search" placeholder="Search products on Newegg" />
        <button type="submit">Search</button>
        </form>
    </div>
    <h1>Price Alert</h1>
    <div id="register-button">
        <a href="templates/addproduct.html">Add Product</a>
    </div>
    <div id="register-button">
        <a href="templates/login.html">User Account</a>
    </div>
</header>

<div id="items-container" class="items">
    <?php
    include('scrape.php');

    // Get the search query from the URL
$searchQuery = urlencode($_GET['search']);

// Construct the URL for Newegg's search
$searchUrl = "https://www.newegg.ca/p/pl?d=$searchQuery";

// Fetch the search results page
$searchContent = file_get_contents($searchUrl);

// Check if the content was fetched successfully
if ($searchContent === false) {
    echo "Failed to retrieve search results.";
    exit;
}

    // URLs of the product pages
    $urls = array(
        "https://www.newegg.ca/msi-geforce-rtx-3060-rtx-3060-ventus-2x-12g-oc/p/N82E16814137632",
        "https://www.newegg.ca/msi-geforce-rtx-4090-rtx-4090-suprim-liquid-x-24g/p/N82E16814137759",
        "https://www.newegg.ca/gigabyte-geforce-rtx-4090-gv-n4090gaming-oc-24gd/p/N82E16814932550",
        "https://www.newegg.ca/msi-geforce-rtx-3060-rtx-3060-ventus-2x-12g-oc/p/N82E16814137632",
        "https://www.newegg.ca/msi-geforce-rtx-4090-rtx-4090-suprim-liquid-x-24g/p/N82E16814137759",
        "https://www.newegg.ca/gigabyte-geforce-rtx-4090-gv-n4090gaming-oc-24gd/p/N82E16814932550"
        // Add more URLs here if needed
    );

    echo "<p>Search performed at: <a href=\"$searchUrl\">$searchUrl</a></p>";

    // Scrape product information
    $products = scrapeNeweggProductInfo($urls);

    $numColumns = 3; // Define the number of columns
    $numProducts = count($products);
    $productsPerColumn = ceil($numProducts / $numColumns);

    for ($i = 0; $i < $numColumns; $i++):
    ?>
    <div class="column">
        <?php
        for ($j = $i * $productsPerColumn; $j < min(($i + 1) * $productsPerColumn, $numProducts); $j++):
            $product = $products[$j];
        ?>
            <div class="item">
                <a href="<?php echo $product['url']; ?>">
                    <img src="<?php echo $product['imageURL']; ?>" alt="Product Image" class="product-image" />
                </a>
                <div class="item-background">
                    <p class="price-value">Best Price</p>
                    <p class="item-name"><?php echo $product['productName']; ?></p>
                    <p>List Price: $<?php echo $product['price']; ?></p>
                    <p>Average Price: $00.00</p>
                    <a href="<?php echo $product['url']; ?>" target="_blank" class="amazon-button">View at Newegg</a>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <?php endfor; ?>
</div>

</body>
</html>