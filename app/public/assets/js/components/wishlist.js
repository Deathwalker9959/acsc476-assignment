import { emitCartUpdated } from "../mixins/events.js";
import storage from "../mixins/storage.js";

const wishlistItem = (wishlist) => {
    let mappedwishlist = wishlist
        .map(({ attributes }, index) => {
            return /*html*/ `
                <li class="d-inline-flex w-100">
                    <a class="flex-grow-1" aria-id="${attributes.product_id}" href="#">
                        ${attributes.name}
                    </a>
                </li>
        `;
        })


    mappedwishlist = [
        /*html*/ `
                <li class="d-inline-flex w-100">
                    <h4 class="mx-auto my-auto text-white" href="#">
                        Wishlist
                    </h4>
                </li>
        `
    ].concat(mappedwishlist).concat(
        /*html*/`
        <li class="mt-2">
            <a id="deleteWishlist" href="#" class="btn btn-danger bg-danger">
                Delete wishlist
            </a>
        </li>
        `
    )

    return mappedwishlist.join(" ");
};

const emptywishlistTemplate = () => {
    return /*html*/ `
        <li>
            <a class="shop-cart user-select-none" disabled>This wishlist is empty</a>
        </li>
    `;
};

const wishlistTemplate = (wishlist) => {
    return /*html*/ `
        <div class="dropdown">
            <a href="#" class="dropdown-button d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownWishlist">
                <i class="fa fa-scroll">&nbsp;</i>
            </a>
            <ul class="${data.dropdownVisible ? "d-block" : ""
        } dropdown-content dropdown-team-select text-small shadow px-3 my-3">
                ${Array.isArray(wishlist) && wishlist.length > 0 ? wishlistItem(wishlist) : emptywishlistTemplate()}
            </ul>
        </div>
    `;
};

let data = store({
    wishlist: [],
    selectedTeam: null,
    dropdownVisible: false,
});

let template = () => {
    let { wishlist, selectedTeam } = data;

    if (!selectedTeam) {
        return "";
    }

    return wishlistTemplate(wishlist);
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
};


const loadData = async () => {
    try {
        const response = await axios.get(`/api/shops/${data.selectedTeam}/wishlist`);
        const wishlist = response.data;
        data.wishlist = wishlist;
    } catch (error) {
        console.error(error);
    }
};

const updateWishlist = async ({ detail }) => {
    const url = `api/shops/${data.selectedTeam}/wishlist`
    const headers = { 'Content-Type': 'application/json' }
    const body = {
        products: detail.map((jsonObj) => JSON.parse(jsonObj))
    };
    const response = await axios.put(url, body, { headers });

    loadData();
}

const deleteWishlist = async () => {
    const url = `api/shops/${data.selectedTeam}/wishlist`
    const headers = { 'Content-Type': 'application/json' }
    const response = await axios.delete(url, "", { headers });

    loadData();
}

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);
    document.addEventListener("dashboard:wishlist-updated", loadData);
    document.addEventListener("dashboard:update-wishlist", updateWishlist);

    /*
     * Remove event handlers on re-render
     */
    $("#dropdownWishlist").off("click");
    $("#deleteWishlist").off("click");
    $(document).off("click");
    $("#dropdownUser1").off("click");
    $("a[cart-item][aria-id]").off("click");

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".dropdown").length) {
            data.dropdownVisible = false;
        }
    });

    $("#dropdownWishlist").on("click", function (e) {
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

        const currentData = storage.get(`shops_${data.selectedTeam}_wishlist`);
        currentData.splice(parseInt(storageId), 1)

        storage.set(`shops_${data.selectedTeam}_wishlist`, currentData);

        emitCartUpdated();

        e.stopImmediatePropagation();
    });

    $("#deleteWishlist").on("click", function (e) {
        deleteWishlist();
        e.stopImmediatePropagation();
    });
};

export default {
    data,
    template,
    addEvents,
    loadData,
};
