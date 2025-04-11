function addToWishlist(productId) {
    fetch("wishlist_action.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=add&product_id=" + productId
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "added") {
            alert("Added to Wishlist!");
        } else if (data.trim() === "exists") {
            alert("Product is already in the Wishlist!");
        } else {
            alert("Error: " + data);
        }
    });
}
