import { emitCartUpdated, emitUpdateWishlist } from "../mixins/events.js";
import storage from "../mixins/storage.js";

const cartItem = (products) => {
    const mappedProducts = products
        .map((jsonObj) => JSON.parse(jsonObj))
        .map(({ id, name, quantity }, index) => {
            return /*html*/ `
                <li class="d-inline-flex w-100">
                    <a class="flex-grow-1 ${data.selectedTeam == id ? "active" : ""
                }" aria-id="${id}" href="#">
                        ${quantity > 1 ? /*html*/`<small>${quantity}x</small>` : ""}
                        ${name}
                    </a>
                    <a class="d-inline-flex flex-shrink-1 my-auto bi bi-trash btn btn-danger bg-danger" cart-item href="#" aria-id="${index}"></a>
                </li>
        `;
        })

    mappedProducts.push(
        /*html*/`
            <li class="mt-2">
                <a id="checkoutBtn" href="#" class="btn btn-success bg-success">
                    Goto Checkout
                </a>
            </li>
        `
    );

    mappedProducts.push(
        /*html*/`
            <li class="mt-2">
                <a id="wishlistBtn" href="#" class="btn btn-success bg-success">
                    Save to wishlist
                </a>
            </li>
        `
    );

    return mappedProducts.join(" ");
};

const emptyCartTemplate = () => {
    return /*html*/ `
        <li>
            <a class="shop-cart user-select-none" disabled>This cart is empty</a>
        </li>
    `;
};

const shoppingCartTemplate = (products) => {
    return /*html*/ `
        <div class="dropdown">
            <a href="#" class="dropdown-button d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownCart">
                <i class="bi bi-cart">&nbsp;</i>
            </a>
            <ul class="${data.dropdownVisible ? "d-block" : ""
        } dropdown-content dropdown-team-select text-small shadow px-3 my-3">
                ${Array.isArray(products) && products.length > 0 ? cartItem(products) : emptyCartTemplate()}
            </ul>
        </div>
    `;
};

let data = store({
    products: [],
    selectedTeam: null,
    dropdownVisible: false,
});

let template = () => {
    let { products, selectedTeam } = data;

    if (!selectedTeam) {
        return "";
    }

    return shoppingCartTemplate(products);
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
    data.products = storage.get(`shops_${data.selectedTeam}_products`);
};

const updateProducts = () => {
    data.products = storage.get(`shops_${data.selectedTeam}_products`);
};

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);
    document.addEventListener("dashboard:cart-updated", updateProducts);

    /*
     * Remove event handlers on re-render
     */
    $("#dropdownCart").off("click");
    $(document).off("click");
    $("#dropdownUser1").off("click");
    $("a[cart-item][aria-id]").off("click");
    $("#wishlistBtn").off("click");

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".dropdown").length) {
            data.dropdownVisible = false;
        }
    });

    $("#dropdownCart").on("click", function (e) {
        data.dropdownVisible = !data.dropdownVisible;

        $("#dropdownUser1").removeClass("show");
        $("#dropdownUser1").attr("aria-expanded", false);
        $("#dropdownUser1 ~ ul").removeClass("show");

        e.stopImmediatePropagation();
    });

    $("#dropdownUser1").on("click", function (e) {
        data.dropdownVisible = false;
    });

    $("a[cart-item][aria-id]").on("click", function (e) {
        const storageId = $(this).attr('aria-id');

        const currentData = storage.get(`shops_${data.selectedTeam}_products`);
        currentData.splice(parseInt(storageId), 1)

        storage.set(`shops_${data.selectedTeam}_products`, currentData);

        emitCartUpdated();

        e.stopImmediatePropagation();
    });

    $("#wishlistBtn").on("click", function(e) {
        emitUpdateWishlist(data.products);
        e.stopImmediatePropagation();
    });
};

export default {
    data,
    template,
    addEvents,
};
