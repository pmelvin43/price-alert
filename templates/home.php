<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <title>Price Alert</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" />
    <link rel="stylesheet" href="../static/css/indexStyle.css" />
</head>

<body>
<header id="masthead">
    <div id="searchbar">
        <form action="/search" method="get">
            <input type="text" id="search" name="search" placeholder="Find Amazon Products" />
            <button type="submit">Search</button>
        </form>
    </div>
    <h1>Price Alert</h1>
    <div id="add-product-button">
        <a href="addproduct.html">Add Product</a>
    </div>
    <div id="register-button">
        <a href="login.html">User Account</a>
    </div>
</header>

<div id="items-container" class="items">
    <!-- Items will be populated here dynamically -->
</div>

<script>
    // Number of items you want to add
    var numberOfItems = 25; // Change this to the desired number of items
    var itemsPerRow = 5; // Number of items per row

    // Function to generate and append items
    function generateItems() {
        var container = document.getElementById('items-container');
        var counter = 0;
        var currentRow;

        for (var i = 0; i < numberOfItems; i++) {
            // Check if a new row needs to be created
            if (counter % itemsPerRow === 0) {
                currentRow = document.createElement('div');
                currentRow.className = 'row';
                container.appendChild(currentRow);
            }

            var itemDiv = document.createElement('div');
            itemDiv.className = 'item';
            itemDiv.innerHTML = `
                <a href="productpage.html">
                    <img src="../static/images/exampleprod.jpg" alt="product image" class="product-image" />
                </a>
                <div class="item-background">
                    <p class="price-value">Best Price</p>
                    <p class="item-price">$00.00</p>
                    <p>List Price: $00.00</p>
                    <p>Average Price: $00.00</p>
                    <button class="amazon-button">View at Amazon</button>
                </div>
            `;
            currentRow.appendChild(itemDiv);
            counter++;
        }
    }

    // Call the function to generate items
    generateItems();
</script>
</body>
</html>
