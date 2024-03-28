<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8" />
    <title>Price Alert</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
    />
    <link rel="stylesheet" href="../static/css/indexStyle.css" />
  </head>

  <body>
    <header id="masthead">
      <div id="searchbar">
        <form action="/search" method="get">
          <input
            type="text"
            id="search"
            name="search"
            placeholder="Find Amazon Products"
          />
          <button type="submit">Search</button>
        </form>
      </div>
      <h1>Price Alert</h1>
      <div id="register-button">
        <a href="login.html">User Account</a>
      </div>
    </header>

    <div class="items">
      <!-- First row of items-->
      <div class="item">
        <a href="productpage.html">
          <img
            src="../static/images/exampleprod.jpg"
            alt="product image"
            class="product-image"
          />
        </a>
        <div class="item-background">
          <p class="price-value">Best Price</p>
          <p class="item-price">$00.00</p>
          <p>List Price: $00.00</p>
          <p>Average Price: $00.00</p>
          <button class="amazon-button">View at Amazon</button>
        </div>
      </div>
    </div>
  </body>
</html>
