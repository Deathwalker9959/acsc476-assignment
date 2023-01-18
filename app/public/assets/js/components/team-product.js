import {
    data as actionsData,
    editProduct,
    removeProduct,
    presentProductDetails
} from "../mixins/item-actions.js";

let data = store({
    selectedTeam: null,
    products: null,
    isEditor: false,
});

const shopItemSpinner = () => {
    const spinner = /*html*/ `
    <div class="row row-cols-1 product-row">
        <div class="col d-flex py-3">
            <div class="my-auto flex-grow-1 placeholder-glow loading">
                <div class="col">
                    <span class="placeholder col-6 rounded-pill"></span>
                </div>
                <div class="col">
                    <span class="placeholder col-4 rounded-pill"></span>
                </div>
            </div>
            <div class="product-image my-auto placeholder-glow loading">
                <svg class="placeholder card-img" width="112" height="98" role="img" aria-label="Placeholder" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
            </div>
        </div>
    </div>
    `;

    return spinner.repeat(3);
};

const shopNoProducts = () => {
    return /*html*/ `
    <div class="row row-cols-1 product-row">
        <div class="col d-flex rounded-pill border-1 border">
            <div class="my-auto flex-grow-1">
                <div class="col text-center">
                    <span class="fs-4">No Products To Show</span>
                </div>
            </div>
        </div>
    </div>
    `;
};

const itemCategory = (categoryName) => {
    return /*html*/ `
    <div class="category mb-2 mt-3">
        <h2 class="fs-5">${categoryName}</h2>
    </div>
    `;
};

const shopItem = (productId, name, price, imgUrl, description = null) => {
    return /*html*/ `
        <a href="#" aria-id="${productId}" class="col d-flex py-3 stretched-link position-relative cursor-pointer text-reset text-decoration-none border-bottom border-light border-2 row-with-buttons">
            <div class="my-auto flex-grow-1">
                <h3 class="product-name">${name}</h3>
                ${description ? /*html*/`<p class="product-name">${description}</p>` : ""}
                <span class="product-price">${price}&euro;</span>
            </div>
            <div class="product-image my-auto">
                ${imgUrl
            ? /*html*/ `<img loading="lazy" src="${imgUrl}" width="112" height="98"></img>`
            : /*html*/ `
                <svg class="product-image my-auto card-img" width="112" height="98">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
                `
        }
            </div>
            ${data.isEditor
            ? /*html*/ `
                <div class="edit-remove-buttons">
                    <button class="edit-item btn btn-primary">
                        Edit Item
                        <br>
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="remove-item btn btn-danger">
                        Remove Item
                        <br>
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `
            : ""
        } 
        </a>
    `;
};

const processItems = (groupedItems) => {
    let output = [];
    let ungroupedOutput = [];
    for (const key in groupedItems) {
        if (groupedItems.hasOwnProperty(key)) {
            const group = groupedItems[key];
            if (key !== "ungrouped") {
                if (Array.isArray(group)) {
                    output.push(itemCategory(key));
                    group.forEach((item) => {
                        if (!item.category) {
                            output.push(
                                shopItem(item.id, item.name, item.price, item.photo_url, item.description)
                            );
                        } else {
                            output.push(
                                shopItem(item.id, item.name, item.price, item.photo_url, item.description)
                            );
                        }
                    });
                } else {
                    output.push(itemCategory(key));
                    output.push(processItems(group));
                }
            } else {
                group.forEach((item) => {
                    ungroupedOutput.push(
                        shopItem(item.id, item.name, item.price, item.photo_url, item.description)
                    );
                });
            }
        }
    }
    if (ungroupedOutput.length > 0) output.push(itemCategory("Uncategorized"));
    output = output.concat(ungroupedOutput);
    return output.join(" ");
};

const shopTemplate = () => {
    if (data.products.length <= 0) return shopNoProducts();

    const groupedItems = data.products.reduce((acc, item) => {
        if (item.category) {
            const categoryName = item.category.name;
            if (!acc[categoryName]) {
                acc[categoryName] = [];
            }
            acc[categoryName].push(item);
        } else {
            if (!acc["ungrouped"]) {
                acc["ungrouped"] = [];
            }
            acc["ungrouped"].push(item);
        }
        return acc;
    }, {});

    return /*html*/ `
        <div class="card row p-4">
            <div class="category-container" aria-type="products">
                ${processItems(groupedItems)}
            </div>
        </div>
    `;
};

let template = () => {
    let { selectedTeam, products } = data;

    if (!selectedTeam) {
        return "";
    }

    return /*html*/ `
        <div class="card-deck">
            ${products ? shopTemplate() : shopItemSpinner()}
        </div>
    `;
};

const updateTeamId = (e) => {
    data.selectedTeam = e.detail;
    actionsData.selectedTeam = e.detail;

    if (!data.selectedTeam) return;

    loadData();
};

const loadData = async () => {
    try {
        const response = await axios.get(
            `${data.isEditor ? "/partner" : ""}/api/shops/${data.selectedTeam
            }/products`
        );
        const products = response.data;
        data.products = products;
        actionsData.products = products;
    } catch (error) {
        console.error(error);
    }
};

const addEvents = () => {
    document.addEventListener("dashboard:team-id", updateTeamId);
    document.addEventListener("dashboard:products-updated", loadData);

    $(".category-container[aria-type='products'] a[aria-id]").off("click");
    $(".edit-remove-buttons button").off("click");

    if (data.isEditor) {
        $(".edit-remove-buttons button").on("click", function () {
            const action = $(this).hasClass("edit-item")
                ? "edit"
                : $(this).hasClass("remove-item")
                    ? "remove"
                    : null;
            const productId = $(this).closest("a").attr("aria-id");
            if (!productId) return;

            switch (action) {
                case "edit":
                    editProduct(productId);
                    break;
                case "remove":
                    removeProduct(productId);
                    break;
            }
        });
    } else {
        $(".category-container[aria-type='products'] a[aria-id]").on("click", function (e) {
            const productId = $(this).attr("aria-id");
            presentProductDetails(productId);
            e.stopImmediatePropagation();
        });
    }
};

export default {
    data,
    template,
    addEvents,
};
