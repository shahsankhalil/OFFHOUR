document.addEventListener("DOMContentLoaded", () => {

    // Small visual feedback on "Add to Cart" buttons before the form submits.
    const addToCartButtons = document.querySelectorAll('.btn-add');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const originalText = button.innerText;
            button.innerText = "ADDED!";
            button.style.backgroundColor = "#fbbf24";

            setTimeout(() => {
                button.innerText = originalText;
                button.style.backgroundColor = "";
            }, 800);
        });
    });

});
